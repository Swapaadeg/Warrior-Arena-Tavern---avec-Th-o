<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\MatchmakingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MatchmakingController extends AbstractController
{
    #[Route('/jouer/mm/{id}/status', name: 'jouer_mm_status', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function status(int $id, MatchmakingService $svc): JsonResponse
    {
        $snap = $svc->snapshot($this->getUser(), $id);
        return $this->json($snap);
    }

    #[Route('/jouer/mm/{id}/ready', name: 'jouer_mm_ready', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function ready(int $id, MatchmakingService $svc): JsonResponse
    {
        $svc->markReady($this->getUser(), $id);
        return $this->json(['success' => true]);
    }

    #[Route('/jouer/mm/{id}/launch', name: 'jouer_mm_launch', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function launch(int $id, MatchmakingService $svc): JsonResponse
    {
        $out = $svc->clickLaunch($this->getUser(), $id);
        return $this->json($out);
    }

    // {id} here is the team id
    #[Route('/jouer/mm/join/{id}', name: 'jouer_mm_join', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function join(int $id, MatchmakingService $svc): JsonResponse
    {
        $result = $svc->joinQueue($this->getUser(), $id);
        return $this->json($result);
    }

    #[Route('/jouer/mm/cancel', name: 'jouer_mm_cancel', methods: ['POST'])]
    public function cancel(MatchmakingService $svc): JsonResponse
    {
        $svc->cancel($this->getUser());
        return $this->json(['ok' => true]);
    }
}
