<?php

namespace App\Controller;

// No DTOs for the battle engine: implement simulation in the controller using entities.
use App\Entity\Teams;
use App\Entity\WATMatch;
use App\Service\CombatService;
use App\Repository\UserRepository;
use App\Service\MatchmakingService;
use App\Repository\WeaponsRepository;
use App\Repository\CharactersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'jouer')]
    public function index(Request $request, CharactersRepository $charactersRepository, EntityManagerInterface $entityManager, UserRepository $userRepository, WeaponsRepository $weaponsRepository): Response
    {
        $characters = $charactersRepository->findAllOrderedByRole();

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

                return $this->render('jouer/match.html.twig', [
                    'my_team' => $user->getTeam() ? $user->getTeam()->getCharacters() : [],
                    'my_team_id' => $team->getId(),
                    'opponent' => null, // No direct opponent - will use matchmaking
                    'opponent_team' => null,
                ]);
            } else {
                $this->addFlash('danger', 'Vous devez sélectionner exactement 5 personnages.');
            }
        }

        return $this->render('jouer/index.html.twig', [
            'characters' => $characters,
        ]);
    }

    #[Route('/jouer/battle', name: 'jouer_battle', methods: ['POST'])]
    public function battle(Request $request, UserRepository $userRepository, CombatService $combatService): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) { 
            $this->addFlash('danger', 'Vous devez être connecté pour jouer.'); 
            return $this->redirectToRoute('login'); 
        }
        
        $opponentId = $request->request->get('opponentId');
        $opponent = $userRepository->find($opponentId);
        if (!$opponent || !$opponent->getTeam()) { 
            $this->addFlash('danger', "L'adversaire n'a pas d'équipe valide."); 
            return $this->redirectToRoute('jouer'); 
        }

        // Use the CombatService to simulate the battle
        $battleResult = $combatService->simulateBattle($user, $opponent);

        return $this->render('jouer/battle.html.twig', $battleResult);
    }

    // Matchmaking endpoints for the match.html.twig template
    #[Route('/jouer/mm/join/{id}', name: 'jouer_mm_join', methods: ['POST'])]
    public function joinMatchmaking(int $id, Request $request, MatchmakingService $matchmakingService, UserRepository $userRepository): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $team = $user->getTeam();
        if (!$team || $team->getId() !== $id) {
            return $this->json(['error' => 'Invalid team'], 400);
        }

        try {
            // Try to join the matchmaking queue
            $matchmakingService->joinQueue($user, $team);
            
            // Check if we get matched immediately or suggest random opponent
            $status = $matchmakingService->getUserStatus($user);
            
            if ($status && $status['status'] === 'SEARCHING') {
                // Still searching - check if we should suggest a random opponent
                $qb = $userRepository->createQueryBuilder('u')
                    ->innerJoin('u.team', 't')
                    ->andWhere('u != :me')->setParameter('me', $user);
                $opponents = $qb->getQuery()->getResult();
                
                if (count($opponents) > 0) {
                    $randomOpponent = $opponents[random_int(0, count($opponents)-1)];
                    return $this->json([
                        'matched' => false,
                        'suggest_random_user' => $randomOpponent->getId(),
                        'status' => 'searching'
                    ]);
                } else {
                    return $this->json([
                        'matched' => false,
                        'status' => 'searching'
                    ]);
                }
            }
            
            return $this->json(['matched' => false, 'status' => 'searching']);
            
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/jouer/mm/process', name: 'jouer_mm_process', methods: ['POST'])]
    public function processMatchmaking(
        Request $request,
        MatchmakingService $matchmakingService,
        UserRepository $userRepository
    ): Response {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $opponentId = $request->request->get('opponentId');
        $opponent = $userRepository->find($opponentId);
        if (!$opponent || !$opponent->getTeam()) {
            return $this->json(['error' => "L'adversaire n'a pas d'équipe valide"], 400);
        }

        $myTeam = $user->getTeam();
        $oppTeam = $opponent->getTeam();

        if (!$myTeam || !$oppTeam) {
            return $this->json(['error' => 'Une des deux équipes est manquante'], 400);
        }

        // État initial des équipes
        $stateLeft = [];
        $stateRight = [];
        foreach ($myTeam->getCharacters() as $char) {
            $stateLeft['L'.$char->getId()] = [
                'name' => $char->getName(),
                'hp' => $char->getHp(),
                'maxHp' => $char->getHp(),
                'power' => $char->getPower(),
                'defense' => $char->getDefense(),
                'role' => $char->getRole(),
                'alive' => true,
            ];
        }
        foreach ($oppTeam->getCharacters() as $char) {
            $stateRight['R'.$char->getId()] = [
                'name' => $char->getName(),
                'hp' => $char->getHp(),
                'maxHp' => $char->getHp(),
                'power' => $char->getPower(),
                'defense' => $char->getDefense(),
                'role' => $char->getRole(),
                'alive' => true,
            ];
        }

        // Ici tu pourrais ajouter ta logique de simulation des frames
        $frames = []; // TODO: remplir avec des étapes d’animation/combat

        // Détermination du vainqueur (simple comparaison vivants restants)
        $leftAliveCount = count(array_filter($stateLeft, fn($s) => $s['alive']));
        $rightAliveCount = count(array_filter($stateRight, fn($s) => $s['alive']));
        $winner = 'draw';
        if ($leftAliveCount > $rightAliveCount) $winner = 'left';
        if ($rightAliveCount > $leftAliveCount) $winner = 'right';

        $result = [
            'winnerTeamId' => $winner,
            'ticks' => count($frames),
        ];

        // Mappages utiles
        $unitMap = array_merge($stateLeft, $stateRight);
        $unitOwners = array_merge(
            array_fill_keys(array_keys($stateLeft), 'You'),
            array_fill_keys(array_keys($stateRight), $opponent->getUsername())
        );

        // Structure setup pour debug/template
        $setup = [
            'left' => array_map(
                fn($id, $s) => ['id' => $id] + $s + ['owner' => 'You'],
                array_keys($stateLeft),
                $stateLeft
            ),
            'right' => array_map(
                fn($id, $s) => ['id' => $id] + $s + ['owner' => $opponent->getUsername()],
                array_keys($stateRight),
                $stateRight
            ),
            'seed' => 42,
        ];

        return $this->render('jouer/battle.html.twig', [
            'opponent' => $opponent,
            'opponent_team' => $oppTeam,
            'setup' => $setup,
            'unit_map' => $unitMap,
            'unit_owners' => $unitOwners,
            'frames' => $frames,
            'result' => $result,
        ]);
    }
}
