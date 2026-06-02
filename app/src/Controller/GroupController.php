<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/PP/User/Groupes')]
#[IsGranted('ROLE_USER')]
class GroupController extends AbstractController
{
    #[Route('/', name: 'group_index')]
    public function index(): Response
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();
        
        // Grâce au ManyToMany, on récupère directement tous ses groupes
        $myGroups = $user->getGroupes();

        return $this->render('user/index.html.twig', [
            'groups' => $myGroups,
        ]);
    }
}
