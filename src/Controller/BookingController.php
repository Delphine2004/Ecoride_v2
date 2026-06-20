<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Form\BookingType;
use App\Enum\BookingStatus;
use App\Enum\UserRole;
use App\Repository\BookingRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;


#[IsGranted(UserRole::PASSENGER->value)]
#[Route('/booking')]
final class BookingController extends AbstractController
{

    public function __construct(
        private Security $security
    ) {}

    #[Route(name: 'app_booking_index', methods: ['GET'])]
    public function index(
        BookingRepository $bookingRepository
    ): Response {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_booking_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $booking = new Booking();
        $booking->setStatus(BookingStatus::CONFIRMED);
        $booking->setPassenger($this->getUser());
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation confirmée.');
            return $this->redirectToRoute('app_dashboard_client', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_booking_show', methods: ['GET'])]
    public function show(
        Booking $booking
    ): Response {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/{id}/cancel', name: 'app_booking_cancel', methods: ['POST'])]
    public function cancel(
        Request $request,
        Booking $booking,
        EntityManagerInterface $entityManager
    ): Response {

        if (!$this->isCsrfTokenValid('cancel' . $booking->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $booking->setStatus(BookingStatus::CANCELLED);

        $entityManager->flush();

        if ($this->isGranted('ROLE_EMPLOYE')) {
            $this->addFlash('success', 'Annulation confirmée.');
            return $this->redirectToRoute('app_dashboard_user', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('success', 'Annulation confirmée.');
            return $this->redirectToRoute('app_dashboard_client', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
    }
}
