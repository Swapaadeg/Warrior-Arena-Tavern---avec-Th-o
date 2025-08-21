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
		/** @var \App\Entity\User|null $user */
		$user = $this->getUser();
		if (!$user instanceof \App\Entity\User) {
			$this->addFlash('danger', 'Vous devez être connecté pour voir la bataille.');
			return $this->redirectToRoute('login');
		}

		$opponent = $userRepository->find($opponentId);
		if (!$opponent || !$opponent->getTeam()) {
			$this->addFlash('danger', "L'adversaire n'a pas d'équipe valide.");
			return $this->redirectToRoute('jouer');
		}
		if (!$user->getTeam()) {
			$this->addFlash('danger', "Vous n'avez pas encore d'équipe.");
			return $this->redirectToRoute('jouer');
		}

		$myTeam = $user->getTeam()->getCharacters();
		$oppTeam = $opponent->getTeam()->getCharacters();
		// Normalize to arrays for iteration/log building
		$my = is_array($myTeam) ? $myTeam : (method_exists($myTeam, 'toArray') ? $myTeam->toArray() : []);
		$opp = is_array($oppTeam) ? $oppTeam : (method_exists($oppTeam, 'toArray') ? $oppTeam->toArray() : []);

		// Build a simple battle log showing who attacks whom (character + team owner)
		$events = [];
		$rounds = min(5, min(count($my), count($opp)));
		for ($i = 0; $i < $rounds; $i++) {
			$me = $my[$i];
			$foe = $opp[$i];
			$events[] = sprintf('%s (You) is attacking %s (%s)', (string)$me->getName(), (string)$foe->getName(), (string)$opponent->getUsername());
			$events[] = sprintf('%s (%s) is attacking %s (You)', (string)$foe->getName(), (string)$opponent->getUsername(), (string)$me->getName());
		}

		return $this->render('battle/index.html.twig', [
			'my_team' => $myTeam,
			'opponent' => $opponent,
			'opponent_team' => $oppTeam,
			'events' => $events,
		]);
	}
}

