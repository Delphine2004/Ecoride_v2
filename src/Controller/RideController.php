<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\RideType;
use App\Enum\UserRole;
use App\Enum\RideStatus;
use App\Service\EmailService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/ride')]
final class RideController extends AbstractController
{

    public function __construct(
        private Security $security
    ) {}

    #[IsGranted(UserRole::DRIVER->value)]
    #[Route(name: 'app_ride_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->security->getUser();

        return $this->render('ride/index.html.twig', [
            'user' => $user
        ]);
    }

    #[IsGranted(UserRole::DRIVER->value)]
    #[Route('/new', name: 'app_ride_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        EmailService $emailService
    ): Response {

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $ride = new Ride();
        $ride->setStatus(RideStatus::CONFIRMED);
        $ride->setCommission('2');
        $ride->setDriver($user);
        $form = $this->createForm(RideType::class, $ride, [
            'user' => $user,
            'mode' => 'create'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $user->spendCredit($ride->getCommission());
                $entityManager->persist($ride);
                $entityManager->flush();

                $this->addFlash('success', 'Trajet ajouté avec succés.');
                $emailService->sendConfirmationRide($user, $ride);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('app_dashboard_client');
            }
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('ride/new.html.twig', [
            'ride' => $ride,
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted(UserRole::PASSENGER->value)]
    #[Route('/show/{id}', name: 'app_ride_show', methods: ['GET'])]
    public function show(
        Ride $ride
    ): Response {
        return $this->render('ride/show.html.twig', [
            'ride' => $ride
        ]);
    }

    #[IsGranted(UserRole::DRIVER->value)]
    #[Route('/edit/{id}', name: 'app_ride_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Ride $ride,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(RideType::class, $ride, ['user' => $this->getUser(), 'mode' => 'update']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Voiture modifiée.');
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ride/edit.html.twig', [
            'ride' => $ride,
            'form' => $form->createView()
        ]);
    }
}
