<?php

namespace App\Twig\Components;

use App\Entity\Car;
use App\Entity\User;

use App\Service\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class CarCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Car $car;

    #[LiveProp]
    public bool $deleted = false;

    #[LiveProp]
    public ?string $message = null;


    public function __construct(
        private CarService $carService
    ) {}

    #[LiveAction]
    public function delete(): void
    {

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        try {

            $this->carService->delete(
                $this->car,
                $user
            );

            $this->deleted = true;

            $this->message =
                'Voiture supprimée avec succès.';
        } catch (\Exception $e) {

            $this->message = $e->getMessage();
        }
    }
}
