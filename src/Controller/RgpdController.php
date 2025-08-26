<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RgpdController extends AbstractController
{
    #[Route('/politique-confidentialite', name: 'app_politique_confidentialite')]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('rgpd/politique_confidentialite.html.twig');
    }

    #[Route('/conditions-generales', name: 'app_conditions_generales')]
    public function conditionsGenerales(): Response
    {
        return $this->render('rgpd/conditions_generales.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('rgpd/mentions_legales.html.twig');
    }

    #[Route('/page-404', name: 'app_404')]
    public function page404(): Response
    {
        return $this->render('rgpd/404.html.twig');
    }
}
