<?php

namespace App\Twig\Components;

use App\Entity\Ride;
use App\Entity\User;

use App\Repository\RideRepository;
use App\Form\SearchRideType;
use App\DTO\SearchRideDTO;
use App\Enum\UserRole;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('RideSearch')]
final class RideSearch extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public array $rideIds = [];

    #[LiveProp]
    public bool $hasSearched = false;

    #[LiveProp]
    public bool $showDescription = true;

    #[LiveProp]
    public ?string $message = null;

    public function __construct(
        private RideRepository $rideRepository,
    ) {}

    protected function instantiateForm(): FormInterface
    {

        $user = $this->getUser();

        $mode =  $user !== null
            && in_array(UserRole::EMPLOYEE->value, $user->getRoles(), true)
            ? 'searchByStaff'
            : 'searchByClient';

        return $this->createForm(
            SearchRideType::class,
            new SearchRideDTO(),
            ['mode' => $mode]
        );
    }

    #[LiveAction]
    public function search(): void
    {
        $this->hasSearched = true;
        $this->showDescription = false;

        $this->submitForm();

        $data = $this->getForm()->getData();
        $data->normalize();

        $user = $this->getUser();

        if (
            $user !== null
            && in_array(UserRole::EMPLOYEE->value, $user->getRoles(), true)
        ) {
            $rides = $this->rideRepository->findRidesByFields($data);
        } else {
            $rides = $this->rideRepository->findAvailableRides(
                $data->getDepartureDate(),
                $data->getDeparturePlace(),
                $data->getArrivalPlace()
            );
        }

        // Stockage des id des Ride
        $this->rideIds = array_map(
            static fn(Ride $ride) => $ride->getId(),
            $rides
        );
    }

    // Reconstruction des résultats à partir de l'id
    public function getResults(): array
    {
        if (!$this->hasSearched || empty($this->rideIds)) {
            return [];
        }

        return $this->rideRepository->findBy([
            'id' => $this->rideIds
        ]);
    }
}
