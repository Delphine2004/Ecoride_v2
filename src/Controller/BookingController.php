<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\User;
use App\Entity\Ride;
use App\Enum\UserRole;
use App\Service\RideService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;


#[IsGranted(UserRole::PASSENGER->value)]
#[Route('/booking')]
final class BookingController extends AbstractController
{

    public function __construct(
        private RideService $rideService,
        private Security $security
    ) {}

    #[Route(name: 'app_booking_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->security->getUser();

        return $this->render('booking/index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/new', name: 'app_booking_new', methods: ['GET', 'POST'])]
    public function new(
        Ride $ride
    ): Response {

        $user = $this->security->getUser();

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

    #[Route('/show/{id}', name: 'app_booking_show', methods: ['GET'])]
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
