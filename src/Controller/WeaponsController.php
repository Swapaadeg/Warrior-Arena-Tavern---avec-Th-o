<?php

namespace App\Controller;

use App\Entity\Weapons;
use App\Repository\WeaponsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weapons')]
class WeaponsController extends AbstractController
{
    #[Route('/', name: 'app_weapons_index', methods: ['GET'])]
    public function index(WeaponsRepository $weaponsRepository): Response
    {
        return $this->json([
            'weapons' => $weaponsRepository->findAll(),
            'message' => 'Liste des armes disponibles'
        ]);
    }

    #[Route('/{id}', name: 'app_weapons_show', methods: ['GET'])]
    public function show(Weapons $weapon): Response
    {
        return $this->json([
            'weapon' => $weapon,
            'message' => 'Détails de l\'arme'
        ]);
    }
}
