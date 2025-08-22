<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BattleController extends AbstractController
{
	#[Route('/battle/{opponentId}', name: 'battle_show', methods: ['GET'])]
	public function show(UserRepository $userRepository, int $opponentId): Response
	{
		
		return $this->render('battle/index.html.twig', [

		]);
	}
}

