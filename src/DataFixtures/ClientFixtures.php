<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $clientData = [

            [
                'number' => '1',
                'lastName' => 'WAYNE',
                'firstName' => 'Bruce',
                'email' => 'batman@batman.com',
                'credit' => '10',
                'licence' => '527934098',
                'roles' => [
                    UserRole::PASSENGER,
                    UserRole::DRIVER,
                ],
            ],
            [
                'number' => '2',
                'lastName' => 'KENT',
                'firstName' => 'Clark',
                'email' => 'superman@dailyplanet.com',
                'credit' => '10',
                'licence' => '127987498',
                'roles' => [
                    UserRole::PASSENGER,
                    UserRole::DRIVER,
                ],
            ],
            [
                'number' => '3',
                'lastName' => 'STARK',
                'firstName' => 'Tony',
                'email' => 'ironman@starkindustries.com',
                'credit' => '10',
                'licence' => 'AG24896412S321',
                'roles' => [
                    UserRole::PASSENGER,
                    UserRole::DRIVER,
                ],
            ],
            [
                'number' => '4',
                'lastName' => 'PARKER',
                'firstName' => 'Peter',
                'email' => 'spiderman@bugle.com',
                'credit' => '10',
                'licence' => 'OP67856412F321',
                'roles' => [
                    UserRole::PASSENGER,
                    UserRole::DRIVER,
                ],
            ],
            [
                'number' => '5',
                'lastName' => 'GRANGER',
                'firstName' => 'Hermione',
                'email' => 'hermione@poudlard.com',
                'credit' => '20',
                'roles' => [
                    UserRole::PASSENGER
                ],
            ],
            [
                'number' => '6',
                'lastName' => 'BOND',
                'firstName' => 'James',
                'email' => 'bond007@mi6.co.uk',
                'credit' => '75',
                'roles' => [
                    UserRole::PASSENGER
                ],
            ]
        ];

        foreach ($clientData as $data) {
            $client = new User();
            $client->setFirstName($data['firstName']);
            $client->setLastName($data['lastName']);
            $client->setEmail($data['email']);
            $client->setRoles($data['roles']);
            $hashedPassword = $this->hasher->hashPassword($client, 'Azertyuiop12*');
            $client->setPassword($hashedPassword, true);

            $manager->persist($client);

            $this->addReference('client_' . $data['number'], $client);
        }
        $manager->flush();
    }
}
