<?php

namespace App\Twig\Components;

use App\Entity\Ride;
use App\Entity\User;
use App\Repository\RideRepository;
use App\Form\SearchRideType;
use App\DTO\SearchRideDTO;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent('RideSearchByStaff')]
final class RideSearchByStaff extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public ?User $user = null;

    #[LiveProp]
    public array $rideIds = [];

    #[LiveProp]
    public ?string $message = null;

    public function __construct(
        private RideRepository $rideRepository,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            SearchRideType::class,
            new SearchRideDTO(),
            ['mode' => 'searchByStaff']
        );
    }


    #[LiveAction]
    public function search(): void
    {
        $this->submitForm();

        $data = $this->getForm()->getData();
        $data->normalize();

        $rides = $this->rideRepository->findRidesByFields($data);

        // Stockage des id des Rides
        $this->rideIds = array_map(
            static fn(Ride $ride) => $ride->getId(),
            $rides
        );
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
