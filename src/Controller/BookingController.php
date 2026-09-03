<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Enum\UserRole;

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

    #[Route('/show/{id}', name: 'app_booking_show', methods: ['GET'])]
    public function show(
        Booking $booking
    ): Response {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking
        ]);
    }
}
