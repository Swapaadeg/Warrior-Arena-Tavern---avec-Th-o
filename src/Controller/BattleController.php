<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\WeaponsRepository;
use App\Repository\CharactersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class BattleController extends AbstractController
{
    #[Route('/battle/{opponentId}', name: 'battle_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(
        UserRepository $userRepository, 
        WeaponsRepository $weaponsRepository,
        CharactersRepository $charactersRepository,
        int $opponentId
    ): Response {
        $currentUser = $this->getUser();
        $opponent = $userRepository->find($opponentId);
        
        if (!$opponent) {
            throw $this->createNotFoundException('Adversaire introuvable');
        }

        // Récupérer 3 armes aléatoires
        $randomWeapons = $weaponsRepository->findRandomWeapons(3);
        
        // Récupérer les équipes des deux joueurs
        $playerTeam = $charactersRepository->findBy(['user' => $currentUser]);
        $opponentTeam = $charactersRepository->findBy(['user' => $opponent]);

        return $this->render('battle/index.html.twig', [
            'opponent' => $opponent,
            'playerTeam' => $playerTeam,
            'opponentTeam' => $opponentTeam,
            'randomWeapons' => $randomWeapons,
        ]);
    }

    #[Route('/api/battle/calculate-stats', name: 'api_battle_calculate_stats', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function calculateStats(): JsonResponse
    {
        // Cette route servira à calculer les stats combinés via AJAX
        return new JsonResponse(['status' => 'success']);
    }
}

