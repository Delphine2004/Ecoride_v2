<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\User;
use App\Enum\CarBrand;
use App\Enum\CarColor;
use App\Enum\CarPower;

use Exception;

use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CarService
{
    public function __construct(
        private CarRepository $carRepository,
        private EntityManagerInterface $entityManagerInterface,
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    public function delete(Car $car, User $user): void
    {
        // Empêche un client de supprimer la voiture d'un autre
        if (!$this->carRepository->isOwner($user->getId(), $car->getId()) && !$this->authorizationChecker->isGranted('ROLE_EMPLOYE')) {
            throw new Exception('Vous n\'êtes pas autorisé.');
        }

        // Vérifier qu'il y a encore une voiture
        if (!$this->carRepository->hasOtherCar($user->getId(), $car->getId())) {
            throw new Exception(
                'Vous devez avoir au moins une voiture. Ajoutez en une avant de supprimer celle-ci.'
            );
        }

        // Vérifier que la voiture n'est pas attaché à un trajet
        if ($this->carRepository->hasActiveRide($car->getId())) {
            throw new Exception(
                'Vous ne pouvez pas supprimer votre voiture pendant un trajet en cours.'
            );
        }


        $car->setBrand(CarBrand::NA);
        $car->setModel('NA');
        $car->setColor(CarColor::NA);
        $car->setYear('NA');
        $car->setPower(CarPower::NA);
        $car->setSeats(0);
        $car->setRegistrationNumber('NA');
        $car->setRegistrationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $car->setOwner(null);

        $this->entityManagerInterface->flush();
    }
}
