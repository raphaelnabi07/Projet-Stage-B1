<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CongeRepository; 
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

class PlatformeController extends AbstractController
{
    #[Route('/PP/User/Espace', name: 'app_user_platforme')]
    public function userIndex(): Response
    {
        return $this->render('platforme/user_index.html.twig');
    }

    #[Route('/PP/Admin/Espace', name: 'app_admin_dashboard')]
    public function adminIndex(): Response
    {
        return $this->render('platforme/admin_index.html.twig');
    }

    #[Route('/PP/User/demandes-en-attente', name: 'app_conges_attente_user', methods: ['GET'])]
    public function demandesUser(CongeRepository $congeRepository): Response
    {
        $conges = $congeRepository->findBy(['statut' => 'En attente']);
        return $this->render('user/user_attentes.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/PP/Admin/demandes-en-attente', name: 'app_conges_attente_admin', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function demandesAdmin(CongeRepository $congeRepository): Response
    {
        $conges = $congeRepository->findBy(['statut' => 'En attente']);
        return $this->render('admin/admin_attentes.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/PP/Admin/Conge/valider/{id}', name: 'app_admin_conge_valider', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function validerConge(int $id, CongeRepository $congeRepository, EntityManagerInterface $em): Response
    {
        $conge = $congeRepository->find($id);
        
        if ($conge) {
            $conge->setStatut('Validé');
            $em->flush();
        }

        return $this->redirectToRoute('app_conges_attente_admin');
    }

    #[Route('/PP/Admin/Conge/supprimer/{id}', name: 'app_admin_conge_supprimer', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function supprimerConge(int $id, CongeRepository $congeRepository, EntityManagerInterface $em): Response
    {
        $conge = $congeRepository->find($id);
        
        if ($conge) {
            $em->remove($conge);
            $em->flush();
        }

        return $this->redirectToRoute('app_conges_attente_admin');
    }
}
