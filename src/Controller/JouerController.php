<?php

namespace App\Controller;

use App\Repository\CharactersRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Teams;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'jouer')]
    public function index(Request $request, CharactersRepository $charactersRepository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $characters = $charactersRepository->findAll();

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
                // Remove all previous characters from the team
                foreach ($team->getCharacters() as $oldChar) {
                    $team->removeCharacter($oldChar);
                }
                $totalPower = 0;
                foreach ($selectedCharacters as $character) {
                    $team->addCharacter($character);
                    $totalPower += $character->getPower() ?? 0;
                }
                $team->setTotalPower($totalPower);
                $entityManager->flush();

                // Matchmaking: pick a random user (not self) with a team
                $qb = $userRepository->createQueryBuilder('u')
                    ->innerJoin('u.team', 't')
                    ->andWhere('u != :me')
                    ->setParameter('me', $user);
                $opponents = $qb->getQuery()->getResult();
                $opponent = null;
                if (count($opponents) > 0) {
                    $opponent = $opponents[random_int(0, count($opponents)-1)];
                }

                return $this->render('jouer/match.html.twig', [
                    'my_team' => $user->getTeam() ? $user->getTeam()->getCharacters() : [],
                    'opponent' => $opponent,
                    'opponent_team' => $opponent && $opponent->getTeam() ? $opponent->getTeam()->getCharacters() : [],
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
