<?php

namespace App\Controller\Admin;

use App\Entity\Characters;
use App\Form\CharacterType;
use App\Repository\CharactersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/characters')]
class CharacterAdminController extends AbstractController
{
    #[Route('/', name: 'admin_characters_index')]
    public function index(CharactersRepository $repo): Response
    {
        return $this->render('admin/characters/index.html.twig', [
            'characters' => $repo->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_characters_edit')]
    public function edit(Request $request, Characters $character, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Personnage modifié avec succès.');
            return $this->redirectToRoute('admin_characters_index');
        }
        return $this->render('admin/characters/edit.html.twig', [
            'form' => $form->createView(),
            'character' => $character,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_characters_delete', methods: ['POST'])]
    public function delete(Request $request, Characters $character, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_char_' . $character->getId(), $request->request->get('_token'))) {
            $em->remove($character);
            $em->flush();
            $this->addFlash('success', 'Personnage supprimé avec succès.');
        }
        return $this->redirectToRoute('admin_characters_index');
    }
}
