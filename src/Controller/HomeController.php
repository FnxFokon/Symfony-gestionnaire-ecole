<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    // Création de la route pour mon espace
    #[Route('/mon-compte', name: 'app_mon-compte')]
    public function monCompte(): Response
    {
        // Si l'utilisateur est un admin
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('home/monCompteAdmin.html.twig', []);
        }

        // Si l'utilisateur est un admin
        if ($this->isGranted('ROLE_PROF')) {
            return $this->render('home/monCompteProf.html.twig', []);
        }

        // Si l'utilisateur est un admin
        if ($this->isGranted('ROLE_ELEVE')) {
            return $this->render('home/monCompteEleve.html.twig', []);
        }
    }
}
