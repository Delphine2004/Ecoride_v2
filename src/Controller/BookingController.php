<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Ride;
use App\Enum\UserRole;
use App\Form\SearchBookingType;
use App\Service\RideService;
use App\Repository\BookingRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted(UserRole::PASSENGER->value)]
#[Route('/booking')]
final class BookingController extends AbstractController
{

    public function __construct(
        private RideService $rideService
    ) {}

    #[Route(name: 'app_booking_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        BookingRepository $bookingRepository,
    ): Response {

        $user = $this->getUser();

        $form = $this->createForm(SearchBookingType::class);
        $form->handleRequest($request);

        $bookings = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $bookings = $bookingRepository->findBookingsByFields(
                $data
            );
        }

        return $this->render('booking/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'bookings' => $bookings
        ]);
    }

    #[Route('/new/{id}', name: 'app_booking_new', methods: ['GET', 'POST'])]
    public function new(
        Ride $ride,
        User $user
    ): Response {

        try {
            $this->rideService->book($ride, $user);
            $this->addFlash('success', 'Réservation confirmée.');
            return $this->redirectToRoute('app_dashboard_client', [], Response::HTTP_SEE_OTHER);
        } catch (\LogicException $e) {
            $this->addFlash(
                'error',
                $e->getMessage()
            );
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/{id}', name: 'app_booking_show', methods: ['GET'])]
    public function show(
        Booking $booking,
        User $user
    ): Response {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'user' => $user
        ]);
    }
}
