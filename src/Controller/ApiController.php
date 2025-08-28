<?php

namespace App\Controller;

use App\Entity\WATMatch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/matchmaking/match/{id}', name: 'api_matchmaking_match', methods: ['GET'])]
    public function getMatchResult(int $id, EntityManagerInterface $em): JsonResponse
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) {
            return $this->json(['error' => 'Not authenticated'], 401);
        }

        $match = $em->getRepository(WATMatch::class)->find($id);
        if (!$match) {
            return $this->json(['error' => 'Match not found'], 404);
        }

        // Check if user is involved in this match
        $userTeam = $user->getTeam();
        if (!$userTeam || ($match->getTeamA() !== $userTeam && $match->getTeamB() !== $userTeam)) {
            return $this->json(['error' => 'Not authorized'], 403);
        }

        // Determine if user won
        $isWinner = false;
        $winnerTeam = $match->getWinner();
        if ($winnerTeam && $winnerTeam === $userTeam) {
            $isWinner = true;
        }

        return $this->json([
            'id' => $match->getId(),
            'status' => $match->getStatus(),
            'is_winner' => $isWinner,
            'team_a_id' => $match->getTeamA()->getId(),
            'team_b_id' => $match->getTeamB()->getId(),
            'winner_id' => $winnerTeam ? $winnerTeam->getId() : null
        ]);
    }
}
