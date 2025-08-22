<?php

namespace App\Controller;

use App\Entity\Characters;
use App\Form\CharacterType;
use App\Entity\Roles;
use App\Entity\Types;
use App\Repository\CharactersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PersoController extends AbstractController
{
    #[Route('/personnages', name: 'personnages')]
    public function index(Request $request, EntityManagerInterface $entityManager, CharactersRepository $charactersRepository): Response
    {
        $character = new Characters();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle role creation if provided
            $roleName = $form->get('roleName')->getData();
            if ($roleName) {
                $role = new Roles();
                $role->setName($roleName);
                $entityManager->persist($role);
                $character->setRole($role);
            }

            // Handle type creation if provided
            $typeName = $form->get('typeName')->getData();
            if ($typeName) {
                $type = new Types();
                $type->setName($typeName);
                $entityManager->persist($type);
                $character->setType($type);
            }

            $entityManager->persist($character);
            $entityManager->flush();

            $this->addFlash('success', 'Personnage ajouté avec succès !');
            return $this->redirectToRoute('personnages');
        }

        $characters = $charactersRepository->findAll();

        return $this->render('perso/index.html.twig', [
            'characters' => $characters,
            'characterForm' => $form->createView(),
        ]);
    }
}
