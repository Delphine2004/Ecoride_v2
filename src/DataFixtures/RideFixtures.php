<?php

namespace App\DataFixtures;

use App\Entity\Ride;
use App\Entity\Car;
use App\Entity\User;

use App\Enum\RideStatus;

use DateTimeImmutable;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RideFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {

        $today = new DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

        $rideData = [
            // trajet du jour
            [
                'number' => '1',
                'departureDate' => $today->setTime(15, 15),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->setTime(16, 00),
                'arrivalPlace' => 'TOURS',
                'price' => '17',
                'availableSeats' => 0,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-8 days'),
                'driver' => $this->getReference('client_1', User::class),
                'car' => $this->getReference('car_4', Car::class),
            ],
            // Trajet à vérifier
            [
                'number' => '2',
                'departureDate' => $today->modify('-1 days')->setTime(10, 00),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('-1 days')->setTime(13, 15),
                'arrivalPlace' => 'CHARTRES',
                'price' => '40',
                'availableSeats' => 2,
                'status' => RideStatus::PENDING,
                'createdAt' => $today->modify('-1 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_3', Car::class),
            ],

            // à venir
            // User 1
            [
                'number' => '3',
                'departureDate' => $today->modify('+1 days')->setTime(10, 15),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('+1 days')->setTime(12, 00),
                'arrivalPlace' => 'LYON',
                'price' => '45',
                'availableSeats' => 1,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-4 days'),
                'driver' => $this->getReference('client_1', User::class),
                'car' => $this->getReference('car_2', Car::class),
            ],
            [
                'number' => '4',
                'departureDate' => $today->modify('+7 days')->setTime(7, 00),
                'departurePlace' => 'CAEN',
                'arrivalDate' => $today->modify('+7 days')->setTime(9, 30),
                'arrivalPlace' => 'PARIS',
                'price' => '17',
                'availableSeats' => 2,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-3 days'),
                'driver' => $this->getReference('client_1', User::class),
                'car' => $this->getReference('car_1', Car::class),
            ],

            [
                'number' => '5',
                'departureDate' => $today->modify('+8 days')->setTime(14, 45),
                'departurePlace' => 'AMIENS',
                'arrivalDate' => $today->modify('+8 days')->setTime(17, 15),
                'arrivalPlace' => 'PARIS',
                'price' => '12',
                'availableSeats' => 0,
                'status' => RideStatus::CANCELLED,
                'createdAt' => $today,
                'driver' => $this->getReference('client_1', User::class),
                'car' => $this->getReference('car_2', Car::class),
            ],
            // User 2
            [
                'number' => '6',
                'departureDate' => $today->modify('+3 days')->setTime(20, 15),
                'departurePlace' => 'CAEN',
                'arrivalDate' => $today->modify('+3 days')->setTime(22, 45),
                'arrivalPlace' => 'PARIS',
                'price' => '22',
                'availableSeats' => 2,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-2 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_3', Car::class),
            ],
            [
                'number' => '7',
                'departureDate' => $today->modify('+14 days')->setTime(10, 45),
                'departurePlace' => 'ROUEN',
                'arrivalDate' => $today->modify('+14 days')->setTime(13, 45),
                'arrivalPlace' => 'CAEN',
                'price' => '19',
                'availableSeats' => 1,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-3 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_3', Car::class),
            ],
            [
                'number' => '8',
                'departureDate' => $today->modify('+11 days')->setTime(7, 45),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('+11 days')->setTime(8, 45),
                'arrivalPlace' => 'CHARTRES',
                'price' => '18',
                'availableSeats' => 2,
                'status' => RideStatus::CANCELLED,
                'createdAt' => $today->modify('-6 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_4', Car::class),
            ],
            [
                'number' => '9',
                'departureDate' => $today->setTime(11, 30),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->setTime(14, 00),
                'arrivalPlace' => 'TOURS',
                'price' => '20',
                'availableSeats' => 4,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-2 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_3', Car::class),
            ],

            // User 3
            [
                'number' => '10',
                'departureDate' => $today->modify('+8 days')->setTime(19, 30),
                'departurePlace' => 'CAEN',
                'arrivalDate' => $today->modify('+8 days')->setTime(22, 00),
                'arrivalPlace' => 'PARIS',
                'price' => '22',
                'availableSeats' => 2,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-2 days'),
                'driver' => $this->getReference('client_3', User::class),
                'car' => $this->getReference('car_5', Car::class),
            ],
            [
                'number' => '11',
                'departureDate' => $today->modify('+13 days')->setTime(6, 45),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('+13 days')->setTime(8, 30),
                'arrivalPlace' => 'LYON',
                'price' => '15',
                'availableSeats' => 3,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today,
                'driver' => $this->getReference('client_3', User::class),
                'car' => $this->getReference('car_6', Car::class),
            ],
            [
                'number' => '12',
                'departureDate' => $today->modify('+1 days')->setTime(14, 00),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('+1 days')->setTime(16, 45),
                'arrivalPlace' => 'LYON',
                'price' => '35',
                'availableSeats' => 3,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-5 days'),
                'driver' => $this->getReference('client_3', User::class),
                'car' => $this->getReference('car_7', Car::class),
            ],

            // User 4
            [
                'number' => '13',
                'departureDate' => $today->modify('+11 days')->setTime(16, 15),
                'departurePlace' => 'METZ',
                'arrivalDate' => $today->modify('+11 days')->setTime(17, 15),
                'arrivalPlace' => 'STRASBOURG',
                'price' => '40',
                'availableSeats' => 4,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-5 days'),
                'driver' => $this->getReference('client_4', User::class),
                'car' => $this->getReference('car_8', Car::class),
            ],
            [
                'number' => '14',
                'departureDate' => $today->modify('+2 days')->setTime(20, 15),
                'departurePlace' => 'MONTPELLIER',
                'arrivalDate' => $today->modify('+2 days')->setTime(21, 45),
                'arrivalPlace' => 'TOULOUSE',
                'price' => '30',
                'availableSeats' => 2,
                'status' => RideStatus::CONFIRMED,
                'createdAt' => $today->modify('-1 days'),
                'driver' => $this->getReference('client_4', User::class),
                'car' => $this->getReference('car_8', Car::class),
            ],
            [
                'number' => '15',
                'departureDate' => $today->modify('+11 days')->setTime(9, 30),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('+11 days')->setTime(11, 30),
                'arrivalPlace' => 'ORLEANS',
                'price' => '22',
                'availableSeats' => 1,
                'status' => RideStatus::CANCELLED,
                'createdAt' => $today->modify('-5 days'),
                'driver' => $this->getReference('client_4', User::class),
                'car' => $this->getReference('car_8', Car::class),
            ],

            // Passés
            // User 1
            [
                'number' => '16',
                'departureDate' => $today->modify('-2 days')->setTime(9, 30),
                'departurePlace' => 'MONTPELLIER',
                'arrivalDate' => $today->modify('-2 days')->setTime(11, 30),
                'arrivalPlace' => 'TOULOUSE',
                'price' => '25',
                'availableSeats' => 1,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-6 days'),
                'driver' => $this->getReference('client_1', User::class),
                'car' => $this->getReference('car_1', Car::class),
            ],
            [
                'number' => '17',
                'departureDate' => $today->modify('-5 days')->setTime(12, 30),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('-5 days')->setTime(14, 30),
                'arrivalPlace' => 'ORLEANS',
                'price' => '22',
                'availableSeats' => 1,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-7 days'),
                'driver' => $this->getReference('client_1', User::class),
                'car' => $this->getReference('car_2', Car::class),
            ],
            [
                'number' => '18',
                'departureDate' => $today->modify('-9 days')->setTime(9, 30),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('-9 days')->setTime(12, 30),
                'arrivalPlace' => 'STRASBOURG',
                'price' => '21',
                'availableSeats' => 1,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-12 days'),
                'driver' => $this->getReference('client_1', User::class),
                'car' => $this->getReference('car_2', Car::class),
            ],
            // User 2
            [
                'number' => '19',
                'departureDate' => $today->modify('-4 days')->setTime(9, 30),
                'departurePlace' => 'STRASBOURG',
                'arrivalDate' => $today->modify('-4 days')->setTime(11, 30),
                'arrivalPlace' => 'LILLE',
                'price' => '18',
                'availableSeats' => 1,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-9 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_3', Car::class),
            ],
            [
                'number' => '20',
                'departureDate' => $today->modify('-6 days')->setTime(12, 30),
                'departurePlace' => 'LYON',
                'arrivalDate' => $today->modify('-6 days')->setTime(14, 30),
                'arrivalPlace' => 'ORLEANS',
                'price' => '24',
                'availableSeats' => 1,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-9 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_4', Car::class),
            ],
            [
                'number' => '21',
                'departureDate' => $today->modify('-9 days')->setTime(9, 30),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('-9 days')->setTime(12, 30),
                'arrivalPlace' => 'STRASBOURG',
                'price' => '22',
                'availableSeats' => 1,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-10 days'),
                'driver' => $this->getReference('client_2', User::class),
                'car' => $this->getReference('car_4', Car::class),
            ],
            // User 3
            [
                'number' => '22',
                'departureDate' => $today->modify('-2 days')->setTime(19, 30),
                'departurePlace' => 'CAEN',
                'arrivalDate' => $today->modify('-2 days')->setTime(22, 00),
                'arrivalPlace' => 'PARIS',
                'price' => '17',
                'availableSeats' => 2,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-2 days'),
                'driver' => $this->getReference('client_3', User::class),
                'car' => $this->getReference('car_5', Car::class),
            ],
            [
                'number' => '23',
                'departureDate' => $today->modify('-13 days')->setTime(6, 45),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('-13 days')->setTime(8, 30),
                'arrivalPlace' => 'LYON',
                'price' => '15',
                'availableSeats' => 3,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-13 days'),
                'driver' => $this->getReference('client_3', User::class),
                'car' => $this->getReference('car_6', Car::class),
            ],
            [
                'number' => '24',
                'departureDate' => $today->modify('-7 days')->setTime(14, 00),
                'departurePlace' => 'PARIS',
                'arrivalDate' => $today->modify('-7 days')->setTime(16, 45),
                'arrivalPlace' => 'LYON',
                'price' => '35',
                'availableSeats' => 3,
                'status' => RideStatus::COMPLETED,
                'createdAt' => $today->modify('-7 days'),
                'driver' => $this->getReference('client_3', User::class),
                'car' => $this->getReference('car_7', Car::class),
            ],
            // User 4 
            [
                'number' => '25',
                'departureDate' => $today->modify('-2 days')->setTime(20, 15),
                'departurePlace' => 'MONTPELLIER',
                'arrivalDate' => $today->modify('-2 days')->setTime(21, 45),
                'arrivalPlace' => 'TOULOUSE',
                'price' => '30',
                'availableSeats' => 2,
                'status' => RideStatus::CANCELLED,
                'createdAt' => $today->modify('-8 days'),
                'driver' => $this->getReference('client_4', User::class),
                'car' => $this->getReference('car_8', Car::class),
            ],
        ];


        foreach ($rideData as $data) {
            $ride = new Ride();

            $ride->setDepartureDate($data['departureDate']);
            $ride->setDeparturePlace($data['departurePlace']);
            $ride->setArrivalDate($data['arrivalDate']);
            $ride->setArrivalPlace($data['arrivalPlace']);
            $ride->setPrice($data['price']);
            $ride->setAvailableSeats($data['availableSeats']);
            $ride->setStatus($data['status']);
            $ride->setCommission('2');
            $ride->setCreatedAt($data['createdAt']);
            $ride->setDriver($data['driver']);
            $ride->setCar($data['car']);

            $this->addReference('ride_' . $data['number'], $ride);

            $manager->persist($ride);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
            CarFixtures::class
        ];
    }
}
