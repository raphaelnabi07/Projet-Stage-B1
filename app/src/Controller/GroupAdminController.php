<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/PP/Admin/Groupes')]
#[IsGranted('ROLE_ADMIN')]
class GroupAdminController extends AbstractController
{
    #[Route('/', name: 'rh_group_index', methods: ['GET', 'POST'])]
    #[Route('/{id}/modifier', name: 'rh_group_edit', methods: ['GET', 'POST'])]
    public function index(
        Group $group = null,
        Request $request,
        GroupRepository $groupRepository,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response {
        if (!$group) {
            $group = new Group();
        }

        // Traitement du formulaire lors de la soumission (POST)
        if ($request->isMethod('POST')) {
            $groupName = $request->request->get('group_name');
            $selectedUserIds = $request->request->all('selected_users');

            if (!empty($groupName)) {
                $group->setName($groupName);

                // On vide d'abord les membres existants du groupe pour éviter les doublons en mode édition
                foreach ($group->getUsers() as $existingUser) {
                    $group->removeUser($existingUser);
                }

                // On ajoute les utilisateurs sélectionnés
                foreach ($selectedUserIds as $userId) {
                    $user = $userRepository->find($userId);
                    if ($user) {
                        $group->addUser($user);
                    }
                }

                $em->persist($group);
                $em->flush();

                $this->addFlash('success', 'Le groupe a été enregistré avec succès.');
                return $this->redirectToRoute('rh_group_index');
            }
        }

        return $this->render('admin/index.html.twig', [
            'groups' => $groupRepository->findAll(),
            'users' => $userRepository->findAll(),
            'currentGroup' => $group, // Permet de savoir si on modifie ou si on crée
        ]);
    }
}