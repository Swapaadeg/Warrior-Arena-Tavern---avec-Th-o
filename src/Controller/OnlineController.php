<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class OnlineController extends AbstractController
{
    #[Route('/online', name: 'online_lobby')]
    public function lobby(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $now = new \DateTimeImmutable();

        // consider users with updatedAt within last 5 minutes as "online"
        $threshold = $now->modify('-5 minutes');
        $candidates = $userRepository->createQueryBuilder('u')
            ->andWhere('u.updatedAt >= :t')
            ->setParameter('t', $threshold)
            ->andWhere('u != :me')
            ->setParameter('me', $user)
            ->orderBy('u.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('jouer/online.html.twig', [
            'candidates' => $candidates,
        ]);
    }

    #[Route('/online/find', name: 'online_find', methods: ['GET'])]
    public function find(Request $request, UserRepository $userRepository): JsonResponse
    {
        $now = new \DateTimeImmutable();
        $threshold = $now->modify('-5 minutes');
        $users = $userRepository->createQueryBuilder('u')
            ->andWhere('u.updatedAt >= :t')
            ->setParameter('t', $threshold)
            ->getQuery()
            ->getResult();

        $out = array_map(fn($u) => ['id' => $u->getId(), 'username' => (string)$u], $users);
        return new JsonResponse($out);
    }
}
