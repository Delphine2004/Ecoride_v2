<?php

namespace App\Twig\Components;

use App\Entity\Booking;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Form\SearchBookingType;
use App\DTO\SearchBookingDTO;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('searchBookingByStaff')]
final class SearchBookingByStaff extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public ?User $user = null;

    #[LiveProp]
    public array $bookingIds = [];

    #[LiveProp]
    public ?string $message = null;

    public function __construct(
        private BookingRepository $bookingRepository,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            SearchBookingType::class,
            new SearchBookingDTO()
        );
    }

    #[LiveAction]
    public function search(): void
    {
        $this->submitForm();

        $data = $this->getForm()->getData();

        $bookings = $this->bookingRepository->findBookingsByFields($data);

        // Stockage des id des Bookings
        $this->bookingIds = array_map(
            static fn(Booking $booking) => $booking->getId(),
            $bookings
        );
    }

    // reconstruction des résultats à partir de l'id
    public function getResults(): array
    {
        if (empty($this->bookingIds)) {
            return [];
        }

        return $this->bookingRepository->findBy([
            'id' => $this->bookingIds
        ]);
    }
}
