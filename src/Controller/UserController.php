<?php

namespace App\Controller;

use App\DTO\SearchBookingDTO;
use App\DTO\SearchRideDTO;
use App\Entity\User;
use App\Entity\Car;

use App\Enum\BookingStatus;
use App\Enum\RideStatus;
use App\Enum\UserRole;

use App\Form\UserType;

use App\Repository\UserRepository;
use App\Repository\BookingRepository;
use App\Repository\RideRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


final class UserController extends AbstractController
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        private string $uploadsUsersDirectory,
        private Security $security
    ) {
        $this->hasher = $hasher;
    }

    #[IsGranted(UserRole::EMPLOYEE->value)]
    #[Route('/user/search', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[IsGranted(UserRole::EMPLOYEE->value)]
    #[Route('/user', name: 'app_dashboard_user', methods: ['GET'])]
    public function dashboardUser(
        BookingRepository $bookingRepository
    ): Response {
        $user = $this->security->getUser();

        $criteria = new SearchBookingDTO();
        $criteria->status = BookingStatus::REPORTED;

        return $this->render('user/dashboard_user.html.twig', [
            'user' => $user,
            'bookings' => $bookingRepository->findBookingsByFields($criteria)
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
    #[Route('/client', name: 'app_dashboard_client', methods: ['GET'])]
    public function dashboardClient(
        RideRepository $rideRepository,
        BookingRepository $bookingRepository
    ): Response {

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $userId = $user->getId();

        return $this->render('user/dashboard_client.html.twig', [
            'user' => $user,
            'actionToDoBookings' => $bookingRepository->findActionsBookingByClient($userId),
            'actionToDoRides' => $rideRepository->findActionsRideByClient($userId),
            'upcomingBookings' => $bookingRepository->findUpcomingBookingByClient($userId),
            'upcomingRides' => $rideRepository->findUpcomingRideByClient($userId),
        ]);
    }

    #[IsGranted(UserRole::EMPLOYEE->value)]
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

            $this->addFlash('success', 'Utilisateur créé.');
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted(UserRole::EMPLOYEE->value)]
    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(
        User $user
    ): Response {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function editInfo(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $clientUpdate = $this->getUser() === $user && in_array(UserRole::PASSENGER->value, $user->getRoles())
            && $this->isGranted(UserRole::PASSENGER->value);

        $userUpdate = $this->getUser() === $user && in_array(UserRole::EMPLOYEE->value, $user->getRoles())
            && $this->isGranted(UserRole::EMPLOYEE->value);

        $adminUpdate = $this->getUser() === $user && in_array(UserRole::ADMIN->value, $user->getRoles())
            && $this->isGranted(UserRole::ADMIN->value);
        $userByAdminUpdate = in_array(UserRole::EMPLOYEE->value, $user->getRoles()) && $this->isGranted(UserRole::ADMIN->value);
        $clientByStaffUpdate = in_array(UserRole::PASSENGER->value, $user->getRoles()) && $this->isGranted(UserRole::EMPLOYEE->value);

        /*
        dd([
            'roles' => $this->getUser()->getRoles(),
            'allowed' => in_array(UserRole::PASSENGER->value, $user->getRoles())
                && $this->isGranted(UserRole::PASSENGER->value),
        ]);*/

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

            $this->addFlash('success', 'Annulation confirmée.');
            if ($clientUpdate) {
                return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'showDeleteForm' => true,
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
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

            $this->addFlash('success', 'Modifié avec succés.');
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'showDeleteForm' => false,
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
    #[Route('/edit/{id}/credit', name: 'app_user_credit', methods: ['GET', 'POST'])]
    public function addCredit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(UserType::class, $user, ['mode' => 'updateCredit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('picture')->getData();
            if ($uploadedFile) {
                $fileName = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($this->uploadsUsersDirectory, $fileName);
                $user->setPicture($fileName);

                $entityManager->persist($user);
            }


            $entityManager->flush();
            $this->addFlash('success', 'Crédit ajoutés avec succés.');
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'showDeleteForm' => false,
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
    #[Route('/edit/{id}/driver', name: 'app_user_driver', methods: ['GET', 'POST'])]
    public function becomeDriver(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ): Response {

        if ($user->getCars()->isEmpty()) {
            $user->addCar(new Car());
        }

        $form = $this->createForm(UserType::class, $user, ['mode' => 'becomeDriver']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérations des données
            $licence = $form->get('licence')->getData();

            // Assignation des valeurs
            $user->setLicence($licence);
            $user->addRole(UserRole::DRIVER->value);


            // La voiture est déjà remplie par le formulaire
            $user->getCars()->first();

            $entityManager->persist($user);
            $entityManager->flush();

            $token = $tokenStorage->getToken();

            if ($token) {
                $tokenStorage->setToken(
                    new UsernamePasswordToken(
                        $user,
                        'main',
                        $user->getRoles()
                    )
                );
            }

            $this->addFlash('success', 'Modifié avec succés.');
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'showDeleteForm' => false,
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
    #[Route('/history/{id}', name: 'app_user_history', methods: ['GET'])]
    public function history(
        User $user,
        RideRepository $rideRepository,
        BookingRepository $bookingRepository
    ): Response {
        $userId  = $user->getId();

        return $this->render('user/history.html.twig', [
            'user' => $user,
            'bookings' => $bookingRepository->findHistoryBookingByClient($userId),
            'rides' => $rideRepository->findHistoryRideClient($userId)
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        User $user,
        BookingRepository $bookingRepository,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ): Response {
        // Vérification que la requête est valide
        if (!$this->isCsrfTokenValid(
            'delete' . $user->getId(),
            $request->request->get('_token')
        )) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $userConnected = $this->getUser();

        // Vérification que l'utilisateur est connecté
        if (!$userConnected) {
            $this->addFlash('sucess', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        // Empêche un client de supprimer un autre compte
        if ($user !== $userConnected && !$this->isGranted('ROLE_EMPLOYE')) {
            $this->addFlash('sucess', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        // Vérification que l'utilisateur n'a pas une réservation en cours
        if ($bookingRepository->hasCurrentReservation($user)) {
            $this->addFlash(
                'sucess',
                'Vous ne pouvez pas supprimer votre compte pendant un séjour en cours.'
            );
            return $this->redirectToRoute('app_client_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        // Récupérer et annuler les réservations à venir
        try {
            $datas = [
                'passengerId' => $user->getId(),
                'status' => BookingStatus::CONFIRMED
            ];
            $searchBookingDto = new SearchBookingDTO($datas);

            $bookings = $bookingRepository->findBookingsByFields($searchBookingDto);

            foreach ($bookings as $booking) {
                $booking->setStatus(BookingStatus::CANCELLED);
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'sucess',
                'Une erreur est survenue.'
            );

            $this->addFlash('sucess', 'Une erreur est survenue.');
            return $this->redirectToRoute('app_client_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        $user->setFirstName('anonyme');
        $user->setLastName('anonyme');
        $user->setLogin('anonyme' . $user->getId());
        $user->setEmail('anonyme_' . $user->getId() . '@example.com');
        $hashedPassword = $this->hasher->hashPassword($user, 'Anonymised12*');
        $user->setPassword($hashedPassword);
        $user->setRoles([UserRole::ANONYMIZED]);


        $entityManager->flush();

        // Déconnexion
        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
