<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CongeController extends AbstractController
{
    #[Route('/conge/formulaire', name: 'app_conge')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conge = new Conge();

        $form = $this->createForm(CongeType::class, $conge);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $conge->setAgent($this->getUser());
            $conge->setStatut('En attente');

            $entityManager->persist($conge);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande est enregistrée !');
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('app_admin_dashboard');
            }else {
                return $this->redirectToRoute('app_user_platform');
            }
        }
        return $this->render('conge/conge.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
