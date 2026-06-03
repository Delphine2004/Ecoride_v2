<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Form\RideType;

use App\Enum\RideStatus;

use App\Repository\RideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ride')]
final class RideController extends AbstractController
{
    #[Route(name: 'app_ride_index', methods: ['GET'])]
    public function index(
        RideRepository $rideRepository
    ): Response {
        return $this->render('ride/index.html.twig', [
            'rides' => $rideRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ride_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $ride = new Ride();
        $ride->setStatus(RideStatus::AVAILABLE->value);
        $form = $this->createForm(RideType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($ride);
            $entityManager->flush();

            $this->addFlash('success', 'Trajet ajouté avec succés.');
            // A FAIRE - Changer Redirection
            return $this->redirectToRoute('app_ride_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ride/new.html.twig', [
            'ride' => $ride,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_ride_show', methods: ['GET'])]
    public function show(
        Ride $ride
    ): Response {
        return $this->render('ride/show.html.twig', [
            'ride' => $ride,
        ]);
    }

    #[Route('/{id}/start', name: 'app_ride_start', methods: ['POST'])]
    public function start(
        Request $request,
        Ride $ride,
        EntityManagerInterface $entityManager
    ): Response {

        if (!$this->isCsrfTokenValid('start' . $ride->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $ride->setStatus(RideStatus::RUNNING->value);

        $entityManager->flush();

        $this->addFlash('success', 'Départ confirmé.');
        // A FAIRE - Changer Redirection
        return $this->redirectToRoute('app_user_dashboard', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/stop', name: 'app_ride_stop', methods: ['POST'])]
    public function stop(
        Request $request,
        Ride $ride,
        EntityManagerInterface $entityManager
    ): Response {

        if (!$this->isCsrfTokenValid('stop' . $ride->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $ride->setStatus(RideStatus::PENDING->value);

        $entityManager->flush();

        $this->addFlash('success', 'Arrêt confirmé.');
        // A FAIRE - Changer Redirection
        return $this->redirectToRoute('app_user_dashboard', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/cancel', name: 'app_ride_cancel', methods: ['POST'])]
    public function cancel(
        Request $request,
        Ride $ride,
        EntityManagerInterface $entityManager
    ): Response {

        if (!$this->isCsrfTokenValid('cancel' . $ride->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $ride->setStatus(RideStatus::CANCELLED->value);

        $entityManager->flush();

        $this->addFlash('success', 'Annulation confirmée.');
        // A FAIRE - Changer Redirection
        return $this->redirectToRoute('app_user_dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
