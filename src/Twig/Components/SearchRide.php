<?php

namespace App\Twig\Components;

use App\Entity\Ride;
use App\Repository\RideRepository;
use App\Form\SearchRideType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('search_ride')]
final class SearchRide extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;


    #[LiveProp]
    public array $rideIds = [];

    #[LiveProp]
    public bool $hasSearched = false;

    #[LiveProp]
    public bool $showDescription = true;

    public function __construct(
        private RideRepository $rideRepository,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(SearchRideType::class);
    }

    #[LiveAction]
    public function search(): void
    {
        $this->submitForm();

        $departureDate = $this->getForm()->get('departureDate')->getData();
        $departurePlace = $this->getForm()->get('departurePlace')->getData();
        $arrivalPlace = $this->getForm()->get('arrivalPlace')->getData();

        // Normalisation
        $departurePlace = mb_strtoupper(trim($departurePlace));
        $arrivalPlace = mb_strtoupper(trim($arrivalPlace));

        $rides = $this->rideRepository->findAvailableRides(
            $departureDate,
            $departurePlace,
            $arrivalPlace
        );

        // Stockage des id des Ride
        $this->rideIds = array_map(
            static fn(Ride $ride) => $ride->getId(),
            $rides
        );

        $this->hasSearched = true;
    }

    // reconstruction des résultats à partir de l'id
    public function getResults(): array
    {
        if (empty($this->rideIds)) {
            return [];
        }

        return $this->rideRepository->findBy([
            'id' => $this->rideIds
        ]);
    }
}
