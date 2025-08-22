<?php

namespace App\Controller;

// No DTOs for the battle engine: implement simulation in the controller using entities.
use App\Entity\Teams;
use App\Repository\CharactersRepository;
use App\Repository\RolesRepository;
use App\Repository\TypesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'jouer')]
    public function index(Request $request, CharactersRepository $charactersRepository, RolesRepository $rolesRepository, TypesRepository $typesRepository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        // Récupérer les filtres depuis la requête
        $roleFilter = $request->query->get('role');
        $typeFilter = $request->query->get('type');
        
        // Récupérer tous les rôles et types pour les filtres
        $allRoles = $rolesRepository->findAll();
        $allTypes = $typesRepository->findAll();
        
        // Construire la requête avec tri par rôle (Tank > Heal > DPS)
        $qb = $charactersRepository->createQueryBuilder('c')
            ->leftJoin('c.role', 'r')
            ->leftJoin('c.type', 't')
            ->addSelect('r', 't');
            
        // Appliquer les filtres si présents
        if ($roleFilter) {
            $qb->andWhere('r.id = :role')->setParameter('role', $roleFilter);
        }
        if ($typeFilter) {
            $qb->andWhere('t.id = :type')->setParameter('type', $typeFilter);
        }
        
        // Tri personnalisé : Tank (1) > Heal (2) > DPS (3)
        $qb->addOrderBy('CASE 
            WHEN LOWER(r.name) = \'tank\' THEN 1 
            WHEN LOWER(r.name) IN (\'heal\', \'healer\', \'soigneur\') THEN 2 
            ELSE 3 
        END', 'ASC')
        ->addOrderBy('c.name', 'ASC'); // Tri secondaire par nom
        
        $characters = $qb->getQuery()->getResult();
        
        // Si c'est une requête Ajax, retourner seulement les personnages en JSON
        if ($request->headers->get('X-Requested-With') === 'XMLHttpRequest') {
            return $this->json($this->serializeCharacters($characters));
        }

        if ($request->isMethod('POST')) {
            $teamIds = $request->request->all('team');
            if (is_array($teamIds) && count($teamIds) === 5) {
                $selectedCharacters = $charactersRepository->findBy(['id' => $teamIds]);
                /** @var \App\Entity\User|null $user */
                $user = $this->getUser();
                if (!$user instanceof \App\Entity\User) {
                    $this->addFlash('danger', 'Vous devez être connecté pour jouer.');
                    return $this->redirectToRoute('login');
                }
                $team = $user->getTeam();
                if (!$team) {
                    $team = new Teams();
                    $team->setUser($user);
                    $entityManager->persist($team);
                    $user->setTeam($team);
                }
                foreach ($team->getCharacters() as $oldChar) { $team->removeCharacter($oldChar); }
                $totalPower = 0;
                foreach ($selectedCharacters as $character) { $team->addCharacter($character); $totalPower += $character->getPower() ?? 0; }
                $team->setTotalPower($totalPower);
                $entityManager->flush();

                $qb = $userRepository->createQueryBuilder('u')
                    ->innerJoin('u.team', 't')
                    ->andWhere('u != :me')->setParameter('me', $user);
                $opponents = $qb->getQuery()->getResult();
                $opponent = null; if (count($opponents) > 0) { $opponent = $opponents[random_int(0, count($opponents)-1)]; }

                return $this->render('jouer/match.html.twig', [
                    'my_team' => $user->getTeam() ? $user->getTeam()->getCharacters() : [],
                    'opponent' => $opponent,
                    'opponent_team' => $opponent && $opponent->getTeam() ? $opponent->getTeam()->getCharacters() : [],
                ]);
            } else {
                $this->addFlash('danger', 'Vous devez sélectionner exactement 5 personnages.');
            }
        }

        return $this->render('jouer/index.html.twig', [
            'characters' => $characters,
            'roles' => $allRoles,
            'types' => $allTypes,
            'current_role' => $roleFilter,
            'current_type' => $typeFilter,
        ]);
    }

    #[Route('/jouer/battle', name: 'jouer_battle', methods: ['POST'])]
    public function battle(Request $request, UserRepository $userRepository): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) { $this->addFlash('danger', 'Vous devez être connecté pour jouer.'); return $this->redirectToRoute('login'); }
        $opponentId = $request->request->get('opponentId');
        $opponent = $userRepository->find($opponentId);
        if (!$opponent || !$opponent->getTeam()) { $this->addFlash('danger', "L'adversaire n'a pas d'équipe valide."); return $this->redirectToRoute('jouer'); }

        $myTeam = $user->getTeam() ? $user->getTeam()->getCharacters() : [];
        $oppTeam = $opponent->getTeam()->getCharacters();
        $leftArr = is_array($myTeam) ? $myTeam : (method_exists($myTeam, 'toArray') ? $myTeam->toArray() : []);
        $rightArr = is_array($oppTeam) ? $oppTeam : (method_exists($oppTeam, 'toArray') ? $oppTeam->toArray() : []);

        // Build initial unit state from entities. Use 'L'/'R' prefixes for ids.
        $stateLeft = [];
        foreach ($leftArr as $c) {
            $id = 'L'.$c->getId();
            $stateLeft[$id] = [
                'entity' => $c,
                'hp' => max(0, (int)($c->getHP() ?? 1)),
                'maxHp' => max(1, (int)($c->getHP() ?? 1)),
                'alive' => true,
                'name' => (string)$c->getName(),
                'power' => (int)($c->getPower() ?? 1),
                'defense' => (int)($c->getDefense() ?? 0),
                'role' => $c->getRole() ? (string)$c->getRole()->getName() : '',
            ];
        }
        $stateRight = [];
        foreach ($rightArr as $c) {
            $id = 'R'.$c->getId();
            $stateRight[$id] = [
                'entity' => $c,
                'hp' => max(0, (int)($c->getHP() ?? 1)),
                'maxHp' => max(1, (int)($c->getHP() ?? 1)),
                'alive' => true,
                'name' => (string)$c->getName(),
                'power' => (int)($c->getPower() ?? 1),
                'defense' => (int)($c->getDefense() ?? 0),
                'role' => $c->getRole() ? (string)$c->getRole()->getName() : '',
            ];
        }

        // Simple simulator: each tick, within each team the units act in random order.
        $frames = [];
        $maxTicks = 20;
        for ($tick = 0; $tick < $maxTicks; $tick++) {
            $actions = [];
            $events = [];
            $roundEvents = [];

            // per-round aggregates
            $damageTakenLeft = 0;
            $damageTakenRight = 0;
            $healedLeft = 0;
            $healedRight = 0;

            // helper to check alive counts
            $aliveLeft = array_filter($stateLeft, fn($s) => $s['alive']);
            $aliveRight = array_filter($stateRight, fn($s) => $s['alive']);
            if (count($aliveLeft) === 0 || count($aliveRight) === 0) { break; }

            // Round-based global damage: starting from round 3, all alive units lose roundNumber * 5 HP
            $roundNumber = $tick + 1;
        if ($roundNumber >= 3) {
                $roundDamage = $roundNumber * 5;
                // Apply to left
                foreach ($stateLeft as $id => &$s) {
                    if (!$s['alive']) continue;
                    $s['hp'] = max(0, $s['hp'] - $roundDamage);
                    $s['alive'] = $s['hp'] > 0;
                    $owner = 'You';
                    $roundEvents[] = $s['name'] . ' (' . $owner . ') took ' . $roundDamage . ' damage from round effect';
            // count round AoE as damage received for left
            $damageTakenLeft += $roundDamage;
                }
                unset($s);
                // Apply to right
                foreach ($stateRight as $id => &$s) {
                    if (!$s['alive']) continue;
                    $s['hp'] = max(0, $s['hp'] - $roundDamage);
                    $s['alive'] = $s['hp'] > 0;
                    $owner = (string)$opponent->getUsername();
                    $roundEvents[] = $s['name'] . ' (' . $owner . ') took ' . $roundDamage . ' damage from round effect';
            // count round AoE as damage received for right
            $damageTakenRight += $roundDamage;
                }
                unset($s);
                // re-check alive counts early
                $aliveLeft = array_filter($stateLeft, fn($s) => $s['alive']);
                $aliveRight = array_filter($stateRight, fn($s) => $s['alive']);
                if (count($aliveLeft) === 0 || count($aliveRight) === 0) {
                    // build hp snapshot and push a frame capturing the round damage before ending
                    $hpByUnit = [];
                    foreach ($stateLeft as $id => $s) $hpByUnit[$id] = $s['hp'];
                    foreach ($stateRight as $id => $s) $hpByUnit[$id] = $s['hp'];
                    $frames[] = ['tick' => $tick, 'round' => $roundNumber, 'starter' => null, 'actions' => [], 'hpByUnit' => $hpByUnit, 'events' => [], 'roundEvents' => $roundEvents];
                    break;
                }
            }

            // Randomize which team acts first this tick: 'left' or 'right'
            $firstTeam = random_int(0, 1) === 0 ? 'left' : 'right';

            // closure to play actions for a team
            $playTeamActions = function(string $team, array &$actorState, array &$enemyState) use (&$actions, &$events, $opponent, &$damageTakenLeft, &$damageTakenRight, &$healedLeft, &$healedRight) {
                $order = array_keys(array_filter($actorState, fn($s) => $s['alive']));
                shuffle($order);
                foreach ($order as $actorId) {
                    if (!$actorState[$actorId]['alive']) continue;
                    $role = strtolower($actorState[$actorId]['role'] ?? '');
                    if (str_contains($role, 'heal')) {
                        // only heal teammates that are damaged
                        $teammates = array_keys(array_filter($actorState, fn($s, $k) => $s['alive'] && $k !== $actorId && $s['hp'] < $s['maxHp'], ARRAY_FILTER_USE_BOTH));
                        // if no damaged teammates, healer skips
                        if (count($teammates) === 0) continue;
                        $targetId = $teammates[array_rand($teammates)];
                        $amount = max(1, (int)$actorState[$actorId]['power']);
                        $actorState[$targetId]['hp'] = min($actorState[$targetId]['maxHp'], $actorState[$targetId]['hp'] + $amount);
                        $actorState[$targetId]['alive'] = $actorState[$targetId]['hp'] > 0;
                        $actorOwner = $team === 'left' ? 'You' : (string)$opponent->getUsername();
                        $targetOwner = $actorOwner;
                        $actions[] = ['actorId' => $actorId, 'kind' => 'HEAL', 'targetId' => $targetId, 'amount' => $amount];
                        $events[] = $actorState[$actorId]['name'] . ' (' . $actorOwner . ') healed ' . $actorState[$targetId]['name'] . ' (' . $targetOwner . ') for ' . $amount;
                        // aggregate heal stats
                        if ($team === 'left') $healedLeft += $amount; else $healedRight += $amount;
                    } else {
                        // Prefer alive tanks on the enemy team if any exist
                        $aliveEnemies = array_keys(array_filter($enemyState, fn($s) => $s['alive']));
                        if (count($aliveEnemies) === 0) break;
                        $tankCandidates = array_keys(array_filter($enemyState, fn($s) => $s['alive'] && str_contains(strtolower($s['role'] ?? ''), 'tank')));
                        if (count($tankCandidates) > 0) {
                            $targetId = $tankCandidates[array_rand($tankCandidates)];
                        } else {
                            $targetId = $aliveEnemies[array_rand($aliveEnemies)];
                        }
                        // 10% chance to land a critical hit: deals 50% more damage and ignores defense
                        $isCrit = random_int(1, 100) <= 10;
                        if ($isCrit) {
                            $dmg = max(1, (int) floor($actorState[$actorId]['power'] * 1.5));
                        } else {
                            $dmg = max(1, $actorState[$actorId]['power'] - (int)floor($enemyState[$targetId]['defense'] * 0.5));
                        }
                        $enemyState[$targetId]['hp'] = max(0, $enemyState[$targetId]['hp'] - $dmg);
                        $enemyState[$targetId]['alive'] = $enemyState[$targetId]['hp'] > 0;
                        $actorOwner = $team === 'left' ? 'You' : (string)$opponent->getUsername();
                        $targetOwner = $team === 'left' ? (string)$opponent->getUsername() : 'You';
                        $actions[] = ['actorId' => $actorId, 'kind' => 'ATTACK', 'targetId' => $targetId, 'amount' => $dmg, 'critical' => $isCrit];
                        $events[] = $actorState[$actorId]['name'] . ' (' . $actorOwner . ') attacked ' . $enemyState[$targetId]['name'] . ' (' . $targetOwner . ') for ' . $dmg . ($isCrit ? ' (CRIT!)' : '');
                        // aggregate damage taken for the target's team
                        if ($team === 'left') {
                            $damageTakenRight += $dmg;
                        } else {
                            $damageTakenLeft += $dmg;
                        }
                    }
                }
            };

            if ($firstTeam === 'left') {
                $playTeamActions('left', $stateLeft, $stateRight);
                $playTeamActions('right', $stateRight, $stateLeft);
            } else {
                $playTeamActions('right', $stateRight, $stateLeft);
                $playTeamActions('left', $stateLeft, $stateRight);
            }

            // build hp snapshot
            $hpByUnit = [];
            foreach ($stateLeft as $id => $s) $hpByUnit[$id] = $s['hp'];
            foreach ($stateRight as $id => $s) $hpByUnit[$id] = $s['hp'];

            // include starter and round info so the template can highlight whose turn it was this tick
            // append per-team summary of damage/heal to events so it appears at end of round in the log
            $events[] = 'Summary: Your team took ' . $damageTakenLeft . ' damage and was healed for ' . $healedLeft . ' this round.';
            $events[] = 'Summary: ' . (string)$opponent->getUsername() . "'s team took " . $damageTakenRight . ' damage and was healed for ' . $healedRight . ' this round.';

            $frames[] = ['tick' => $tick, 'round' => $roundNumber, 'starter' => $firstTeam, 'actions' => $actions, 'hpByUnit' => $hpByUnit, 'events' => $events, 'roundEvents' => $roundEvents];

            // stop if one side down
            $aliveLeft = array_filter($stateLeft, fn($s) => $s['alive']);
            $aliveRight = array_filter($stateRight, fn($s) => $s['alive']);
            if (count($aliveLeft) === 0 || count($aliveRight) === 0) break;
        }

        // determine winner
        $leftAliveCount = count(array_filter($stateLeft, fn($s) => $s['alive']));
        $rightAliveCount = count(array_filter($stateRight, fn($s) => $s['alive']));
        $winner = 'draw';
        if ($leftAliveCount > $rightAliveCount) $winner = 'left';
        if ($rightAliveCount > $leftAliveCount) $winner = 'right';

        $result = ['winnerTeamId' => $winner, 'ticks' => count($frames)];

        $unitMap = array_merge(
            array_combine(array_map(fn($c) => 'L'.$c->getId(), $leftArr), $leftArr),
            array_combine(array_map(fn($c) => 'R'.$c->getId(), $rightArr), $rightArr)
        );

        $unitOwners = array_merge(
            array_combine(array_map(fn($c) => 'L'.$c->getId(), $leftArr), array_fill(0, count($leftArr), 'You')),
            array_combine(array_map(fn($c) => 'R'.$c->getId(), $rightArr), array_fill(0, count($rightArr), (string)$opponent->getUsername()))
        );

        // Build a minimal setup structure for the template debug view
        $setup = [
            'left' => array_map(fn($id, $s) => ['id' => $id, 'name' => $s['name'], 'hp' => $s['hp'], 'maxHp' => $s['maxHp'], 'power' => $s['power'], 'defense' => $s['defense'], 'role' => $s['role'], 'owner' => 'You'], array_keys($stateLeft), $stateLeft),
            'right' => array_map(fn($id, $s) => ['id' => $id, 'name' => $s['name'], 'hp' => $s['hp'], 'maxHp' => $s['maxHp'], 'power' => $s['power'], 'defense' => $s['defense'], 'role' => $s['role'], 'owner' => (string)$opponent->getUsername()], array_keys($stateRight), $stateRight),
            'seed' => 42,
        ];

        return $this->render('jouer/battle.html.twig', [
            'my_team' => $myTeam,
            'opponent' => $opponent,
            'opponent_team' => $oppTeam,
            'setup' => $setup,
            'unit_map' => $unitMap,
            'unit_owners' => $unitOwners,
            'frames' => $frames,
            'result' => $result,
        ]);
    }
    
    /**
     * Sérialise les personnages pour les requêtes Ajax
     */
    private function serializeCharacters(array $characters): array
    {
        $serialized = [];
        foreach ($characters as $character) {
            $serialized[] = [
                'id' => $character->getId(),
                'name' => $character->getName(),
                'hp' => $character->getHP(),
                'power' => $character->getPower(),
                'defense' => $character->getDefense(),
                'description' => $character->getDescription(),
                'imageName' => $character->getImageName(),
                'role' => $character->getRole() ? [
                    'id' => $character->getRole()->getId(),
                    'name' => $character->getRole()->getName()
                ] : null,
                'type' => $character->getType() ? [
                    'id' => $character->getType()->getId(),
                    'name' => $character->getType()->getName()
                ] : null,
            ];
        }
        return $serialized;
    }
}
