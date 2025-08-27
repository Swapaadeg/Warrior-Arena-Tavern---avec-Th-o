<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Teams;
use App\Repository\CharactersRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'jouer', methods: ['GET','POST'])]
    public function index(Request $request, CharactersRepository $charactersRepository, EntityManagerInterface $em): Response
    {
        $characters = $charactersRepository->findAllOrderedByRole();

        if ($request->isMethod('POST')) {
            /** @var \App\Entity\User|null $user */
            $user = $this->getUser();
            if (!$user instanceof \App\Entity\User) {
                $this->addFlash('danger', 'Vous devez être connecté pour jouer.');
                return $this->redirectToRoute('login');
            }

            // Récupère et nettoie la sélection
            $teamIds = (array) $request->request->all('team');
            $teamIds = array_values(array_unique(array_map('intval', $teamIds)));

            if (count($teamIds) !== 5) {
                $this->addFlash('danger', 'Vous devez sélectionner exactement 5 personnages.');
                return $this->render('jouer/index.html.twig', ['characters' => $characters]);
            }

            $selectedCharacters = $charactersRepository->findBy(['id' => $teamIds]);
            if (count($selectedCharacters) !== 5) {
                $this->addFlash('danger', 'Sélection invalide.');
                return $this->render('jouer/index.html.twig', ['characters' => $characters]);
            }

            // Équipe de l’utilisateur
            $team = $user->getTeam();
            if (!$team) {
                $team = new Teams();
                $team->setUser($user);
                $em->persist($team);
                $user->setTeam($team);
            }

            // Réinitialise et ajoute les 5 personnages
            foreach ($team->getCharacters() as $old) {
                $team->removeCharacter($old);
            }
            $totalPower = 0;
            foreach ($selectedCharacters as $ch) {
                $team->addCharacter($ch);
                $totalPower += (int) ($ch->getPower() ?? 0);
            }
            $team->setTotalPower($totalPower);
            $em->flush();

            // Affiche la page de matchmaking avec l’id d’équipe
            return $this->render('jouer/match.html.twig', [
                'my_team'    => $team->getCharacters(),
                'my_team_id' => $team->getId(),
            ]);
        }

        return $this->render('jouer/index.html.twig', [
            'characters' => $characters,
        ]);
    }

    #[Route('/jouer/battle', name: 'jouer_battle', methods: ['POST'])]
    public function battle(Request $request, UserRepository $users): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            $this->addFlash('danger', 'Vous devez être connecté pour jouer.');
            return $this->redirectToRoute('login');
        }

        $opponentId = (int) $request->request->get('opponentId', 0);
        if ($opponentId <= 0) {
            $this->addFlash('danger', 'Adversaire invalide.');
            return $this->redirectToRoute('jouer');
        }

        $opponent = $users->find($opponentId);
        if (!$opponent || !$opponent->getTeam()) {
            $this->addFlash('danger', "L'adversaire n'a pas d’équipe valide.");
            return $this->redirectToRoute('jouer');
        }

        $myTeam = $user->getTeam() ? $user->getTeam()->getCharacters() : [];
        $oppTeam = $opponent->getTeam()->getCharacters();

        $leftArr  = is_array($myTeam) ? $myTeam : (method_exists($myTeam, 'toArray') ? $myTeam->toArray() : []);
        $rightArr = is_array($oppTeam) ? $oppTeam : (method_exists($oppTeam, 'toArray') ? $oppTeam->toArray() : []);

        // État initial simplifié
        $stateLeft = [];
        foreach ($leftArr as $c) {
            $id = 'L' . $c->getId();
            $hp = max(1, (int) ($c->getHP() ?? 1));
            $stateLeft[$id] = [
                'entity'  => $c,
                'hp'      => $hp,
                'maxHp'   => $hp,
                'alive'   => true,
                'name'    => (string) $c->getName(),
                'power'   => (int) ($c->getPower() ?? 1),
                'defense' => (int) ($c->getDefense() ?? 0),
                'role'    => $c->getRole() ? (string) $c->getRole()->getName() : '',
            ];
        }
        $stateRight = [];
        foreach ($rightArr as $c) {
            $id = 'R' . $c->getId();
            $hp = max(1, (int) ($c->getHP() ?? 1));
            $stateRight[$id] = [
                'entity'  => $c,
                'hp'      => $hp,
                'maxHp'   => $hp,
                'alive'   => true,
                'name'    => (string) $c->getName(),
                'power'   => (int) ($c->getPower() ?? 1),
                'defense' => (int) ($c->getDefense() ?? 0),
                'role'    => $c->getRole() ? (string) $c->getRole()->getName() : '',
            ];
        }

        // Simulation courte
        $frames = [];
        $maxTicks = 20;
        for ($tick = 0; $tick < $maxTicks; $tick++) {
            $actions = [];
            $events = [];
            $roundEvents = [];

            $aliveLeft = array_filter($stateLeft, fn($s) => $s['alive']);
            $aliveRight = array_filter($stateRight, fn($s) => $s['alive']);
            if (!$aliveLeft || !$aliveRight) { break; }

            // Effet de round à partir du 3e
            $roundNumber = $tick + 1;
            if ($roundNumber >= 3) {
                $roundDamage = $roundNumber * 5;
                foreach ($stateLeft as &$s) {
                    if (!$s['alive']) continue;
                    $s['hp'] = max(0, $s['hp'] - $roundDamage);
                    $s['alive'] = $s['hp'] > 0;
                    $roundEvents[] = $s['name'] . ' (You) took ' . $roundDamage . ' dmg (round)';
                } unset($s);
                foreach ($stateRight as &$s) {
                    if (!$s['alive']) continue;
                    $s['hp'] = max(0, $s['hp'] - $roundDamage);
                    $s['alive'] = $s['hp'] > 0;
                    $roundEvents[] = $s['name'] . ' (' . $opponent->getUsername() . ') took ' . $roundDamage . ' dmg (round)';
                } unset($s);

                $aliveLeft = array_filter($stateLeft, fn($s) => $s['alive']);
                $aliveRight = array_filter($stateRight, fn($s) => $s['alive']);
                if (!$aliveLeft || !$aliveRight) {
                    $hpByUnit = [];
                    foreach ($stateLeft as $id => $s) $hpByUnit[$id] = $s['hp'];
                    foreach ($stateRight as $id => $s) $hpByUnit[$id] = $s['hp'];
                    $frames[] = ['tick'=>$tick,'round'=>$roundNumber,'starter'=>null,'actions'=>[],'hpByUnit'=>$hpByUnit,'events'=>[],'roundEvents'=>$roundEvents];
                    break;
                }
            }

            $firstTeam = random_int(0,1) === 0 ? 'left' : 'right';

            $play = function(string $team, array &$ally, array &$enemy) use (&$actions, &$events, $opponent) {
                $order = array_keys(array_filter($ally, fn($s)=>$s['alive']));
                shuffle($order);
                foreach ($order as $actorId) {
                    if (!$ally[$actorId]['alive']) continue;
                    $role = strtolower($ally[$actorId]['role'] ?? '');
                    if (str_contains($role, 'heal')) {
                        $targets = array_keys(array_filter($ally, fn($s,$k)=>$s['alive'] && $s['hp']<$s['maxHp'] && $k!==$actorId, ARRAY_FILTER_USE_BOTH));
                        if (!$targets) continue;
                        $t = $targets[array_rand($targets)];
                        $amount = max(1, (int)$ally[$actorId]['power']);
                        $ally[$t]['hp'] = min($ally[$t]['maxHp'], $ally[$t]['hp'] + $amount);
                        $actions[] = ['actorId'=>$actorId,'kind'=>'HEAL','targetId'=>$t,'amount'=>$amount];
                        $events[] = $ally[$actorId]['name'] . ' healed ' . $ally[$t]['name'] . ' for ' . $amount;
                    } else {
                        $aliveEnemies = array_keys(array_filter($enemy, fn($s)=>$s['alive']));
                        if (!$aliveEnemies) break;
                        $tanks = array_keys(array_filter($enemy, fn($s)=>$s['alive'] && str_contains(strtolower($s['role'] ?? ''), 'tank')));
                        $t = $tanks ? $tanks[array_rand($tanks)] : $aliveEnemies[array_rand($aliveEnemies)];
                        $isCrit = random_int(1,100) <= 10;
                        $dmg = $isCrit ? max(1, (int)floor($ally[$actorId]['power'] * 1.5))
                                       : max(1, $ally[$actorId]['power'] - (int)floor(($enemy[$t]['defense'] ?? 0) * 0.5));
                        $enemy[$t]['hp'] = max(0, $enemy[$t]['hp'] - $dmg);
                        $enemy[$t]['alive'] = $enemy[$t]['hp'] > 0;
                        $actions[] = ['actorId'=>$actorId,'kind'=>'ATTACK','targetId'=>$t,'amount'=>$dmg,'critical'=>$isCrit];
                        $events[] = $ally[$actorId]['name'] . ' attacked ' . $enemy[$t]['name'] . ' for ' . $dmg . ($isCrit ? ' (CRIT!)' : '');
                    }
                }
            };

            if ($firstTeam === 'left') { $play('left', $stateLeft, $stateRight); $play('right', $stateRight, $stateLeft); }
            else { $play('right', $stateRight, $stateLeft); $play('left', $stateLeft, $stateRight); }

            $hpByUnit = [];
            foreach ($stateLeft as $id => $s) $hpByUnit[$id] = $s['hp'];
            foreach ($stateRight as $id => $s) $hpByUnit[$id] = $s['hp'];

            $frames[] = [
                'tick' => $tick,
                'round' => $roundNumber,
                'starter' => $firstTeam,
                'actions' => $actions,
                'hpByUnit' => $hpByUnit,
                'events' => $events,
                'roundEvents' => $roundEvents,
            ];

            $aliveLeft = array_filter($stateLeft, fn($s)=>$s['alive']);
            $aliveRight = array_filter($stateRight, fn($s)=>$s['alive']);
            if (!$aliveLeft || !$aliveRight) break;
        }

        $winner = 'draw';
        $leftAlive = count(array_filter($stateLeft, fn($s)=>$s['alive']));
        $rightAlive = count(array_filter($stateRight, fn($s)=>$s['alive']));
        if ($leftAlive > $rightAlive) $winner = 'left';
        if ($rightAlive > $leftAlive) $winner = 'right';

        $result = ['winnerTeamId' => $winner, 'ticks' => count($frames)];

        $unitMap = array_merge(
            array_combine(array_map(fn($c)=>'L'.$c->getId(), $leftArr), $leftArr),
            array_combine(array_map(fn($c)=>'R'.$c->getId(), $rightArr), $rightArr)
        );
        $unitOwners = array_merge(
            array_combine(array_map(fn($c)=>'L'.$c->getId(), $leftArr), array_fill(0, count($leftArr), 'You')),
            array_combine(array_map(fn($c)=>'R'.$c->getId(), $rightArr), array_fill(0, count($rightArr), (string)$opponent->getUsername()))
        );
        $setup = [
            'left'  => array_map(fn($id,$s)=>['id'=>$id,'name'=>$s['name'],'hp'=>$s['hp'],'maxHp'=>$s['maxHp'],'power'=>$s['power'],'defense'=>$s['defense'],'role'=>$s['role'],'owner'=>'You'], array_keys($stateLeft), $stateLeft),
            'right' => array_map(fn($id,$s)=>['id'=>$id,'name'=>$s['name'],'hp'=>$s['hp'],'maxHp'=>$s['maxHp'],'power'=>$s['power'],'defense'=>$s['defense'],'role'=>$s['role'],'owner'=>(string)$opponent->getUsername()], array_keys($stateRight), $stateRight),
            'seed'  => 42,
        ];

        return $this->render('jouer/battle.html.twig', [
            'my_team'        => $myTeam,
            'opponent'       => $opponent,
            'opponent_team'  => $oppTeam,
            'setup'          => $setup,
            'unit_map'       => $unitMap,
            'unit_owners'    => $unitOwners,
            'frames'         => $frames,
            'result'         => $result,
        ]);
    }
}
