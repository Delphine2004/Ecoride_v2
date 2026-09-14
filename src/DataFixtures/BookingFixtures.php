<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Ride;
use App\Entity\User;

use App\Enum\BookingStatus;

use DateTimeImmutable;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {

        $today = new DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

        $bookingData = [
            // Réservations du jour
            [
                'number' => '1',
                'status' => BookingStatus::CONFIRMED,
                'createdAt' => $today->modify('-8 days'),
                'passenger' => $this->getReference('client_5', User::class),
                'ride' => $this->getReference('ride_1', Ride::class),
            ],
            [
                'number' => '2',
                'status' => BookingStatus::CONFIRMED,
                'createdAt' => $today->modify('-4 days'),
                'passenger' => $this->getReference('client_6', User::class),
                'ride' => $this->getReference('ride_1', Ride::class),
            ],
            [
                'number' => '3',
                'status' => BookingStatus::CANCELLED,
                'createdAt' => $today->modify('-8 days'),
                'passenger' => $this->getReference('client_6', User::class),
                'ride' => $this->getReference('ride_1', Ride::class),
            ],


            // Réservation à vérifier
            [
                'number' => '4',
                'status' => BookingStatus::PENDING,
                'createdAt' => $today->modify('-1 days'),
                'passenger' => $this->getReference('client_5', User::class),
                'ride' => $this->getReference('ride_2', Ride::class),
            ],
            [
                'number' => '5',
                'status' => BookingStatus::REPORTED,
                'createdAt' => $today->modify('-1 days'),
                'passenger' => $this->getReference('client_6', User::class),
                'ride' => $this->getReference('ride_2', Ride::class),
            ],

            // à venir
            // User 1
            [
                'number' => '6',
                'status' => BookingStatus::CONFIRMED,
                'createdAt' => $today->modify('-5 days'),
                'passenger' => $this->getReference('client_1', User::class),
                'ride' => $this->getReference('ride_8', Ride::class),
            ],
            [
                'number' => '7',
                'status' => BookingStatus::CANCELLED,
                'createdAt' => $today->modify('-5 days'),
                'passenger' => $this->getReference('client_1', User::class),
                'ride' => $this->getReference('ride_8', Ride::class),
            ],

            // User 5
            [
                'number' => '8',
                'status' => BookingStatus::CONFIRMED,
                'createdAt' => $today->modify('-7 days'),
                'passenger' => $this->getReference('client_5', User::class),
                'ride' => $this->getReference('ride_3', Ride::class),
            ],
            [
                'number' => '9',
                'status' => BookingStatus::CANCELLED,
                'createdAt' => $today->modify('-3 days'),
                'passenger' => $this->getReference('client_5', User::class),
                'ride' => $this->getReference('ride_3', Ride::class),
            ],


            // User 6
            [
                'number' => '10',
                'status' => BookingStatus::CONFIRMED,
                'createdAt' => $today->modify('-1 days'),
                'passenger' => $this->getReference('client_6', User::class),
                'ride' => $this->getReference('ride_3', Ride::class),
            ],
            [
                'number' => '11',
                'status' => BookingStatus::CANCELLED,
                'createdAt' => $today->modify('-2 days'),
                'passenger' => $this->getReference('client_6', User::class),
                'ride' => $this->getReference('ride_3', Ride::class),
            ],


            // Passés
            // User 1
            [
                'number' => '12',
                'status' => BookingStatus::FINALIZED,
                'createdAt' => $today->modify('-6 days'),
                'passenger' => $this->getReference('client_1', User::class),
                'ride' => $this->getReference('ride_16', Ride::class),
            ],
            [
                'number' => '13',
                'status' => BookingStatus::FINALIZED,
                'createdAt' => $today->modify('-7 days'),
                'passenger' => $this->getReference('client_1', User::class),
                'ride' => $this->getReference('ride_17', Ride::class),
            ],
            // User 5
            [
                'number' => '14',
                'status' => BookingStatus::FINALIZED,
                'createdAt' => $today->modify('-9 days'),
                'passenger' => $this->getReference('client_5', User::class),
                'ride' => $this->getReference('ride_19', Ride::class),
            ],
            [
                'number' => '15',
                'status' => BookingStatus::FINALIZED,
                'createdAt' => $today->modify('-2 days'),
                'passenger' => $this->getReference('client_5', User::class),
                'ride' => $this->getReference('ride_22', Ride::class),
            ],
            [
                'number' => '16',
                'status' => BookingStatus::FINALIZED,
                'createdAt' => $today->modify('-1 days'),
                'passenger' => $this->getReference('client_5', User::class),
                'ride' => $this->getReference('ride_23', Ride::class),
            ],
            // User 6
            [
                'number' => '17',
                'status' => BookingStatus::FINALIZED,
                'createdAt' => $today->modify('-7 days'),
                'passenger' => $this->getReference('client_6', User::class),
                'ride' => $this->getReference('ride_24', Ride::class),
            ],
            [
                'number' => '18',
                'status' => BookingStatus::FINALIZED,
                'createdAt' => $today->modify('-6 days'),
                'passenger' => $this->getReference('client_6', User::class),
                'ride' => $this->getReference('ride_17', Ride::class),
            ],

        ];


        foreach ($bookingData as $data) {
            $booking = new Booking();

            $booking->setStatus($data['status']);
            $booking->setCreatedAt($data['createdAt']);
            $booking->setPassenger($data['passenger']);
            $booking->setRide($data['ride']);

            $manager->persist($booking);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
            RideFixtures::class
        ];
    }
}
