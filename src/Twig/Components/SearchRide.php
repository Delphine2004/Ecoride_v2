<?php

namespace App\Twig\Components;

use App\Repository\RideRepository;
use App\Form\SearchRideType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\Component\Form\FormInterface;

#[AsLiveComponent('search_ride')]
final class SearchRide extends AbstractController // On étend AbstractController pour avoir accès à $this->createForm
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public array $results = [];

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
        $formData = $this->getForm()->getData();

        $this->results = $this->rideRepository->findRidesByField($formData);
    }
}
