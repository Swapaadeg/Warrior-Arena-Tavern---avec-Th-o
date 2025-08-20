<?php

namespace App\Controller;


use App\Entity\Characters;
use App\Form\CharacterType;
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
