<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Ride;
use App\Form\BookingType;
use App\Enum\BookingStatus;
use App\Enum\UserRole;
use App\Service\RideService;
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
        private Security $security,
        private RideService $rideService
    ) {}

    #[Route(name: 'app_booking_index', methods: ['GET'])]
    public function index(
        BookingRepository $bookingRepository
    ): Response {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_booking_new', methods: ['GET', 'POST'])]
    public function new(
        Ride $ride
    ): Response {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        try {
            $this->rideService->book($ride);

            $this->addFlash('success', 'Réservation confirmée.');
        } catch (\LogicException $e) {
            $this->addFlash(
                'error',
                $e->getMessage()
            );

            return $this->redirectToRoute('app_home');
        }
        return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
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
            return $this->redirectToRoute('app_dashboard_user', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('success', 'Annulation confirmée.');
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        }
    }
}
