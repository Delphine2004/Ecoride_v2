<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\User;
use App\Form\RideType;
use App\Enum\UserRole;
use App\Enum\RideStatus;
use App\Repository\RideRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;


#[IsGranted(UserRole::DRIVER->value)]
#[Route('/ride')]
final class RideController extends AbstractController
{

    public function __construct(
        private Security $security
    ) {}

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
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $ride = new Ride();
        $ride->setStatus(RideStatus::CONFIRMED);
        $form = $this->createForm(RideType::class, $ride, [
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($ride);
            $entityManager->flush();

            $this->addFlash('success', 'Trajet ajouté avec succés.');
            return $this->redirectToRoute('app_dashboard_client', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
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

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $ride->setStatus(RideStatus::RUNNING);

        $entityManager->flush();

        $this->addFlash('success', 'Départ confirmé.');
        return $this->redirectToRoute('app_dashboard_client', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
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

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $ride->setStatus(RideStatus::PENDING);

        $entityManager->flush();

        $this->addFlash('success', 'Arrêt confirmé.');
        return $this->redirectToRoute('app_dashboard_client', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
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

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $ride->setStatus(RideStatus::CANCELLED);

        $entityManager->flush();

        $this->addFlash('success', 'Annulation confirmée.');
        if ($this->isGranted('ROLE_EMPLOYE')) {
            return $this->redirectToRoute('app_dashboard_user', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_dashboard_client', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
