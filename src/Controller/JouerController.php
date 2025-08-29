<?php

namespace App\Controller;

// No DTOs for the battle engine: implement simulation in the controller using entities.
use App\Entity\Teams;
use App\Entity\WATMatch;
use App\Service\CombatService;
use App\Repository\UserRepository;
use App\Service\MatchmakingService;
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
    public function index(Request $request, CharactersRepository $charactersRepository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
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
    public function battle(Request $request, UserRepository $userRepository, CombatService $combatService, EntityManagerInterface $em): Response
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

        $seed = null;
        $match = null;

        // D'abord, vérifier si un seed a été transmis par le formulaire (matchmaking)
        $matchSeed = $request->request->get('matchSeed');
        if ($matchSeed && is_numeric($matchSeed)) {
            $seed = (int)$matchSeed;
        } else {
            // Sinon, chercher un match existant entre ces deux joueurs
            $userTeam = $user->getTeam();
            $opponentTeam = $opponent->getTeam();

            if ($userTeam && $opponentTeam) {
                $match = $em->getRepository(WATMatch::class)->createQueryBuilder('m')
                    ->where('(m.teamA = :userTeam AND m.teamB = :opponentTeam) OR (m.teamA = :opponentTeam AND m.teamB = :userTeam)')
                    ->andWhere('m.status IN (:statuses)')
                    ->setParameter('userTeam', $userTeam)
                    ->setParameter('opponentTeam', $opponentTeam)
                    // Inclure RUNNING pour une transmission robuste même si la bataille démarre côté pair
                    ->setParameter('statuses', ['READY', 'QUEUED', 'RUNNING'])
                    ->orderBy('m.id', 'DESC')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();

                if ($match && $match->getSeed()) {
                    $seed = $match->getSeed();
                    // Marquer le match comme en cours
                    $match->setStatus('RUNNING');
                    $em->flush();
                }
            }
        }

        // Si toujours pas de seed trouvé (bataille directe), créer un match persistant avec seed partagé
        if ($seed === null) {
            $seed = random_int(100000, 999999);
            $userTeam = $user->getTeam();
            $opponentTeam = $opponent->getTeam();
            if ($userTeam && $opponentTeam) {
                $match = new WATMatch();
                $match->setTeamA($userTeam);
                $match->setTeamB($opponentTeam);
                $match->setStatus('RUNNING');
                $match->setSeed($seed);
                $em->persist($match);
                $em->flush();
            }
        }

        // Use the CombatService to simulate the battle with the seed
        $battleResult = $combatService->simulateBattle($user, $opponent, $seed);

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

    #[Route('/jouer/mm/cancel', name: 'jouer_mm_cancel', methods: ['POST'])]
    public function cancelMatchmaking(Request $request, MatchmakingService $matchmakingService): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $success = $matchmakingService->cancelQueue($user);
        
        return $this->json(['success' => $success]);
    }
#[Route('/jouer/mm/status', name: 'jouer_mm_status', methods: ['GET'])]
    public function matchmakingStatus(
        Request $request,
        MatchmakingService $matchmakingService,
        EntityManagerInterface $em,
        UserRepository $userRepository
    ): JsonResponse {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }
    
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }
    
        $team = $user->getTeam();
        if (!$team) {
            return $this->json(['status' => 'NOT_IN_QUEUE']);
        }
    
        // Still in queue?
        $queued = $matchmakingService->getUserStatus($user);
        if ($queued) {
            $resp = [
                'status'         => 'SEARCHING',
                'waiting_time'   => $queued['waiting_time'] ?? null,
                'players_in_queue' => $matchmakingService->getQueueSize(),
            ];
            // Optional: propose a random opponent if nobody available
            if (($resp['players_in_queue'] ?? 0) <= 1) {
                $cand = $userRepository->createQueryBuilder('u')
                    ->innerJoin('u.team', 't')
                    ->andWhere('u != :me')->setParameter('me', $user)
                    ->setMaxResults(1)
                    ->getQuery()->getOneOrNullResult();
                if ($cand) {
                    $resp['suggest_random_user'] = $cand->getId();
                }
            }
            return $this->json($resp);
        }
    
        // Not queued: check if a match exists for this team
        $match = $em->getRepository(WATMatch::class)->createQueryBuilder('m')
            ->where('m.teamA = :team OR m.teamB = :team')
            ->setParameter('team', $team)
            ->orderBy('m.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        if ($match) {
            $st = $match->getStatus();
            if (in_array($st, ['READY', 'QUEUED','RUNNING'])) {
                // Get opponent info
                $opponentTeam = $match->getTeamA() === $team ? $match->getTeamB() : $match->getTeamA();
                $opponent = $opponentTeam->getUser();
                
                return $this->json([
                    'status' => 'MATCH_FOUND', 
                    'match' => [
                        'id' => $match->getId(),
                        'opponent_id' => $opponent->getId(),
                        'opponent_username' => $opponent->getUsername(),
                        'opponent_team_id' => $opponentTeam->getId(),
                        'seed' => $match->getSeed() // Ajouter le seed pour debug
                    ]
                ]);
            }
            if (in_array($st, ['COMPLETED','DONE','FINISHED'])) {
                return $this->json(['status' => 'MATCH_RESULT_PENDING', 'match' => ['id' => $match->getId()]]);
            }
        }        return $this->json(['status' => 'NOT_IN_QUEUE']);
    }
    #[Route('/jouer/mm/process', name: 'jouer_mm_process', methods: ['POST'])]
    public function processMatchmaking(Request $request, MatchmakingService $matchmakingService): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        // Process the matchmaking queue directly
        try {
            $result = $matchmakingService->processQueue();
            return $this->json([
                'success' => true, 
                'message' => 'Matchmaking processing completed',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false, 
                'message' => 'Failed to process matchmaking: ' . $e->getMessage()
            ]);
        }
    }
}
