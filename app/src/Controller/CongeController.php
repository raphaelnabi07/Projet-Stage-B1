<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use App\Repository\CongeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CongeController extends AbstractController
{
    #[Route('/Conge/Formulaire', name: 'app_conge')]
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
                return $this->redirectToRoute('app_user_platforme');
            }
        }
        return $this->render('conge/conge.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route("/api/conges", name: "api_conges_list", methods: ["GET"])]
    public function getCongesJson(CongeRepository $congeRepository): JsonResponse
    {
        $conges = $congeRepository->findAll();
                
        foreach ($conges as $conge) {
            $agent = $conge->getAgent();
            $titre = $agent->getNom() . " (" . $conge->getType() . ")"; 
            $dateFinCalendar = clone $conge->getDateFin();  
            $dateFinCalendar->modify('+1 day');

            $events[] = [
                'id'    => $conge->getId(),
                'title' => $titre, 
                'start' => $conge->getDate()->format('Y-m-d'),
                'end'   => $dateFinCalendar->format('Y-m-d'),
                'color' => $conge->getStatut() === 'En attente' ? '#ff9800' : '#000091',
            ];
        }

        return new JsonResponse($events);
    }
}