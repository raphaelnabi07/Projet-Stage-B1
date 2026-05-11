<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlatformeController extends AbstractController
{
    #[Route('/pompier/espace', name: 'app_user_platform')]
    public function userIndex(): Response
    {
        return $this->render('platforme/user_index.html.twig');
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminIndex(): Response
    {
        return $this->render('platforme/admin_index.html.twig');
    }
}
