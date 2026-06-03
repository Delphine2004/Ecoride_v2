<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserRole;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/user', name: 'app_dashboard_user', methods: ['GET'])]
    public function dashboardUser(): Response
    {
        return $this->render('user/dashboard_user.html.twig');
    }

    #[Route('/client', name: 'app_dashboard_client', methods: ['GET'])]
    public function dashboardClient(): Response
    {
        return $this->render('user/dashboard_client.html.twig');
    }

    #[Route('/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function newUser(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $user->setRoles([UserRole::EMPLOYEE]);
        $form = $this->createForm(UserType::class, $user, ['mode' => 'createUser']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation confirmée.');
            // A FAIRE - Changer Redirection
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(
        User $user
    ): Response {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function editInfo(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $clientUpdate = $this->getUser() === $user && $this->isGranted(UserRole::PASSENGER);
        $userUpdate = $this->getUser() === $user && $this->isGranted(UserRole::EMPLOYEE);
        $adminUpdate = $this->getUser() === $user && $this->isGranted(UserRole::ADMIN);
        $userByAdminUpdate = $user->getRoles() === UserRole::EMPLOYEE && $this->isGranted(UserRole::ADMIN);
        $clientByStaffUpdate = $user->getRoles() === UserRole::PASSENGER && $this->isGranted(UserRole::EMPLOYEE);

        $mode = '';

        if ($clientUpdate) {
            $mode = 'updateClient';
        } else if ($userUpdate) {
            $mode = 'updateUser';
        } else if ($adminUpdate) {
            $mode = 'updateAdmin';
        } else if ($userByAdminUpdate) {
            $mode = 'updateUserByAdmin';
        } else if ($clientByStaffUpdate) {
            $mode = 'updateClientByStaff';
        }

        $form = $this->createForm(UserType::class, $user, ['mode' => $mode]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Modifié avec succés.');
            // A FAIRE - Changer Redirection
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}/picture', name: 'app_user_picture', methods: ['GET', 'POST'])]
    public function editPicture(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(UserType::class, $user, ['mode' => 'updatePicture']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}/credit', name: 'app_user_credit', methods: ['GET', 'POST'])]
    public function addCredit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(UserType::class, $user, ['mode' => 'updateCredit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    // A FAIRE
    #[Route('/edit/{id}/driver', name: 'app_user_driver', methods: ['GET', 'POST'])]
    public function becomeDriver(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(UserType::class, $user, ['mode' => 'becomeDriver']); // A FAIRE
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Modifié avec succés.');
            // A FAIRE - Changer Redirection
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
