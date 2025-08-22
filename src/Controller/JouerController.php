<?php

namespace App\Controller;

use App\Battle\BattleEngine;
use App\Dto\{BattleSetupDTO, TeamDTO, UnitDTO};
use App\Entity\Teams;
use App\Repository\CharactersRepository;
use App\Repository\UserRepository;
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
                foreach ($team->getCharacters() as $oldChar) { $team->removeCharacter($oldChar); }
                $totalPower = 0;
                foreach ($selectedCharacters as $character) { $team->addCharacter($character); $totalPower += $character->getPower() ?? 0; }
                $team->setTotalPower($totalPower);
                $entityManager->flush();

                $qb = $userRepository->createQueryBuilder('u')
                    ->innerJoin('u.team', 't')
                    ->andWhere('u != :me')->setParameter('me', $user);
                $opponents = $qb->getQuery()->getResult();
                $opponent = null; if (count($opponents) > 0) { $opponent = $opponents[random_int(0, count($opponents)-1)]; }

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

    #[Route('/jouer/battle', name: 'jouer_battle', methods: ['POST'])]
    public function battle(Request $request, UserRepository $userRepository): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        if (!$user instanceof \App\Entity\User) { $this->addFlash('danger', 'Vous devez être connecté pour jouer.'); return $this->redirectToRoute('login'); }
        $opponentId = $request->request->get('opponentId');
        $opponent = $userRepository->find($opponentId);
        if (!$opponent || !$opponent->getTeam()) { $this->addFlash('danger', "L'adversaire n'a pas d'équipe valide."); return $this->redirectToRoute('jouer'); }

        $myTeam = $user->getTeam() ? $user->getTeam()->getCharacters() : [];
        $oppTeam = $opponent->getTeam()->getCharacters();
        $toDto = function($c, $prefix, $teamId) {
            $hp = $c->getHP() ?? 1; $hp = max(0, (int)$hp);
            $power = $c->getPower() ?? 1; $def = $c->getDefense() ?? 0;
            return new UnitDTO($prefix.$c->getId(), (string)$c->getName(), $hp, $hp, (int)$power, (int)$def, $teamId);
        };
        $leftArr = is_array($myTeam) ? $myTeam : (method_exists($myTeam, 'toArray') ? $myTeam->toArray() : []);
        $rightArr = is_array($oppTeam) ? $oppTeam : (method_exists($oppTeam, 'toArray') ? $oppTeam->toArray() : []);
        $leftDto = new TeamDTO('left', array_map(fn($c) => $toDto($c, 'L', 'left'), $leftArr));
        $rightDto = new TeamDTO('right', array_map(fn($c) => $toDto($c, 'R', 'right'), $rightArr));
        $setup = new BattleSetupDTO($leftDto, $rightDto, 42);
        $result = BattleEngine::run($setup);

        return $this->render('jouer/battle.html.twig', [
            'my_team' => $myTeam,
            'opponent' => $opponent,
            'opponent_team' => $oppTeam,
            'frames' => $result->frames,
            'result' => $result,
            'unit_map' => array_merge(
                array_combine(array_map(fn($c) => 'L'.$c->getId(), $leftArr), $leftArr),
                array_combine(array_map(fn($c) => 'R'.$c->getId(), $rightArr), $rightArr)
            ),
            'unit_owners' => array_merge(
                array_combine(array_map(fn($c) => 'L'.$c->getId(), $leftArr), array_fill(0, count($leftArr), 'You')),
                array_combine(array_map(fn($c) => 'R'.$c->getId(), $rightArr), array_fill(0, count($rightArr), (string)$opponent->getUsername()))
            ),
        ]);
    }
}
