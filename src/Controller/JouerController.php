<?php

namespace App\Controller;

use App\Repository\CharactersRepository;
use App\Entity\User;
use App\Entity\Teams;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'app_jouer')]
    public function index(Request $request, CharactersRepository $charactersRepository, EntityManagerInterface $entityManager): Response
    {
        $characters = $charactersRepository->findAll();

        if ($request->isMethod('POST')) {
            $teamIds = $request->request->all('team');
            if (is_array($teamIds) && count($teamIds) === 5) {
                $selectedCharacters = $charactersRepository->findBy(['id' => $teamIds]);
                $user = $this->getUser();
                if ($user instanceof \App\Entity\User) {
                    // Remove old team if exists
                    $oldTeam = $user->getTeam();
                    if ($oldTeam) {
                        $entityManager->remove($oldTeam);
                        $entityManager->flush();
                    }
                    // Create new team
                    $team = new Teams();
                    $team->setUser($user);
                    $totalPower = 0;
                    foreach ($selectedCharacters as $character) {
                        $team->addCharacter($character);
                        $totalPower += $character->getPower() ?? 0;
                    }
                    $team->setTotalPower($totalPower);
                    $entityManager->persist($team);
                    $user->setTeam($team);
                    $entityManager->flush();
                }
                $this->addFlash('success', 'Votre équipe a été sélectionnée et enregistrée !');
                return $this->render('jouer/index.html.twig', [
                    'characters' => $characters,
                    'selectedCharacters' => $selectedCharacters,
                ]);
            } else {
                $this->addFlash('danger', 'Vous devez sélectionner exactement 5 personnages.');
            }
        }

        return $this->render('jouer/index.html.twig', [
            'characters' => $characters,
        ]);
    }
}
