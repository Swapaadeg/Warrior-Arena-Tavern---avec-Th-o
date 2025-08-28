<?php

namespace App\Controller;

use App\Entity\Teams;
use App\Entity\WATMatch;
use App\Entity\Characters;
use App\Entity\QueueTicket;
use App\Entity\User as Player;
use App\Entity\User;
use App\Repository\TeamsRepository;
use App\Service\MatchmakingService;
use App\Service\MatchmakingScheduler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/matchmaking')]
#[IsGranted('ROLE_USER')]
class MatchmakingController extends AbstractController
{
    private MatchmakingService $matchmakingService;
    private EntityManagerInterface $entityManager;

    public function __construct(MatchmakingService $matchmakingService, EntityManagerInterface $entityManager)
    {
        $this->matchmakingService = $matchmakingService;
        $this->entityManager = $entityManager;
    }

    private function getCurrentPlayer(): Player
    {
        $user = $this->getUser();
        if (!$user instanceof Player) {
            throw new \LogicException('L\'utilisateur connecté n\'est pas une instance de Player');
        }
        return $user;
    }

    #[Route('/join', name: 'matchmaking_join', methods: ['POST'])]
    public function joinQueue(Request $request, TeamsRepository $teamRepository, MatchmakingScheduler $scheduler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $teamId = $data['team_id'] ?? null;

        if (!$teamId) {
            return $this->json(['error' => 'Team ID requis'], 400);
        }

        $team = $teamRepository->find($teamId);
        $currentPlayer = $this->getCurrentPlayer();
        
        if (!$team || $team->getPlayer() !== $currentPlayer) {
            return $this->json(['error' => 'Équipe non trouvée ou non autorisée'], 404);
        }

        try {
            $ticket = $this->matchmakingService->joinQueue($currentPlayer, $team);
            
            // Déclencher le traitement du matchmaking
            $scheduler->scheduleProcessing('player_joined');
            
            return $this->json([
                'success' => true,
                'message' => 'Ajouté à la file d\'attente',
                'ticket_id' => $ticket->getId()
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/cancel', name: 'matchmaking_cancel', methods: ['POST'])]
    public function cancelQueue(MatchmakingScheduler $scheduler): JsonResponse
    {
        $currentPlayer = $this->getCurrentPlayer();
        $success = $this->matchmakingService->cancelQueue($currentPlayer);
        
        if ($success) {
            $scheduler->scheduleProcessing('player_left');
            return $this->json(['success' => true, 'message' => 'Recherche annulée']);
        }
        
        return $this->json(['error' => 'Aucune recherche en cours'], 400);
    }

    #[Route('/status', name: 'matchmaking_status', methods: ['GET'])]
    public function getStatus(): JsonResponse
    {
        $currentPlayer = $this->getCurrentPlayer();
        $status = $this->matchmakingService->getUserStatus($currentPlayer);
        
        if ($status) {
            return $this->json($status);
        }
        
        return $this->json(['status' => 'NOT_IN_QUEUE']);
    }

    #[Route('/process', name: 'matchmaking_process', methods: ['POST'])]
    public function processQueue(MatchmakingScheduler $scheduler): JsonResponse
    {
        $scheduler->scheduleImmediateProcessing('manual_trigger');
        
        return $this->json(['success' => true]);
    }



    #[Route('/results', name: 'matchmaking_results', methods: ['GET'])]
    public function getResults(): JsonResponse
    {
        $matches = $this->entityManager->getRepository(WATMatch::class)
            ->findBy(['status' => 'FINISHED'], ['finishedAt' => 'DESC'], 10);
        
        $results = [];
        foreach ($matches as $match) {
            $results[] = [
                'id' => $match->getId(),
                'team_a' => $match->getTeamA()->getName(),
                'team_b' => $match->getTeamB()->getName(),
                'winner' => $match->getWinnerTeam() ? $match->getWinnerTeam()->getName() : 'Aucun',
                'finished_at' => $match->getFinishedAt() ? $match->getFinishedAt()->format('H:i:s') : null
            ];
        }
        
        return $this->json([
            'matches' => $results
        ]);
    }
    #[Route('/team', name: 'api_matchmaking_team', methods: ['GET'])]
    public function getPlayerTeam(TeamsRepository $teamRepository): JsonResponse
    {
        $player = $this->getCurrentPlayer();
        $team = $teamRepository->findOneBy(['user' => $player]);

        if (!$team) {
            return $this->json([
                'team' => null,
                'characters' => []
            ]);
        }

        $characters = [];
        foreach ($team->getCharacterInstances() as $instance) {
            $template = $instance->getTemplate();
            $characters[] = [
                'id' => $template->getId(),
                'name' => $template->getName(),
                'role' => $template->getRole(),
                'hp' => $template->getHp(),
                'atk' => $template->getAtk(),
                'def' => $template->getDef(),
                'spd' => $template->getSpd(),
                'heal' => $template->getHeal(),
                'crit' => $template->getCrit(),
                'critDmg' => $template->getCritDmg()
            ];
        }

        return $this->json([
            'team' => [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'isLocked' => $team->isLocked()
            ],
            'characters' => $characters
        ]);
    }

    #[Route('/characters', name: 'api_matchmaking_characters', methods: ['GET'])]
    public function getAvailableCharacters(): JsonResponse
    {
        $characterRepository = $this->entityManager->getRepository(\App\Entity\Characters::class);
        $characters = $characterRepository->findAll();
        
        $data = array_map(function($character) {
            return [
                'id' => $character->getId(),
                'name' => $character->getName(),
                'role' => $character->getRole(),
                'hp' => $character->getHp(),
                'atk' => $character->getAtk(),
                'def' => $character->getDef(),
                'spd' => $character->getSpd(),
                'heal' => $character->getHeal(),
                'crit' => $character->getCrit(),
                'critDmg' => $character->getCritDmg()
            ];
        }, $characters);

        return $this->json($data);
    }
    #[Route('/team/create', name: 'api_matchmaking_team_create', methods: ['POST'])]
    public function createTeam(Request $request): JsonResponse
    {
        $player = $this->getCurrentPlayer();
        
        // Vérifier si le joueur a déjà une équipe
        $existingTeam = $this->entityManager->getRepository(Teams::class)
            ->findOneBy(['user' => $player]);

        if ($existingTeam) {
            return $this->json(['error' => 'Vous avez déjà une équipe'], 400);
        }

        $data = json_decode($request->getContent(), true);
        $teamName = 'Équipe de ' . $player->getUsername();

        $team = new Teams();
        $team->setUser($player);
        $team->getCharacters($teamName);

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'team' => [
                'id' => $team->getId(),
            ]
        ]);
    }
}
