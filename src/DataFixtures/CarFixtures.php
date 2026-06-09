<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\User;

use App\Enum\CarBrand;
use App\Enum\CarColor;
use App\Enum\CarPower;

use DateTime;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CarFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {

        $roomData = [
            [
                'number' => '1',
                'brand' => CarBrand::RENAULT,
                'model' => 'Clio V',
                'color' => CarColor::Red,
                'year' => '2021',
                'power' => CarPower::ESSENCE,
                'seats' => '5',
                'registrationNumber' => 'AB123CD',
                'registrationDate' => '2021-03-15',
                'owner' => $this->getReference('client_1', User::class),
            ],
            [
                'number' => '2',
                'brand' => CarBrand::PEUGEOT,
                'model' => 'e-208',
                'color' => CarColor::BLUE,
                'year' => '2022',
                'power' => CarPower::ELECTRIC,
                'seats' => '5',
                'registrationNumber' => 'EF456GH',
                'registrationDate' => '2022-07-22',
                'owner' => $this->getReference('client_1', User::class),
            ],
            [
                'number' => '3',
                'brand' => CarBrand::VOLKSWAGEN,
                'model' => 'Golf VIII',
                'color' => CarColor::WHITE,
                'year' => '2022',
                'power' => CarPower::ESSENCE,
                'seats' => '5',
                'registrationNumber' => 'IJ789KL',
                'registrationDate' => '2022-01-10',
                'owner' => $this->getReference('client_2', User::class),
            ],
            [
                'number' => '4',
                'brand' => CarBrand::BMW,
                'model' => 'Série 3',
                'color' => CarColor::BLACK,
                'year' => '2023',
                'power' => CarPower::DIESEL,
                'seats' => '5',
                'registrationNumber' => 'MN012OP',
                'registrationDate' => '2023-05-30',
                'owner' => $this->getReference('client_2', User::class),
            ],
            [
                'number' => '5',
                'brand' => CarBrand::TESLA,
                'model' => 'Model 3',
                'color' => CarColor::GREEN,
                'year' => '2023',
                'power' => CarPower::ELECTRIC,
                'seats' => '5',
                'registrationNumber' => 'QR345ST',
                'registrationDate' => '2023-11-03',
                'owner' => $this->getReference('client_3', User::class),
            ],
            [
                'number' => '6',
                'brand' => CarBrand::FORD,
                'model' => 'Focus',
                'color' => CarColor::YELLOW,
                'year' => '2019',
                'power' => CarPower::DIESEL,
                'seats' => '5',
                'registrationNumber' => 'UV-678-WX',
                'registrationDate' => '20190918',
                'owner' => $this->getReference('client_3', User::class),
            ],
            [
                'number' => '7',
                'brand' => CarBrand::MERCEDESBENZ,
                'model' => 'Classe A',
                'color' => CarColor::ORANGE,
                'year' => '2022',
                'power' => CarPower::ESSENCE,
                'seats' => '5',
                'registrationNumber' => 'YZ901AB',
                'registrationDate' => '2022-04-07',
                'owner' => $this->getReference('client_3', User::class),
            ],
            [
                'number' => '8',
                'brand' => CarBrand::AUDI,
                'model' => 'e-tron GT',
                'color' => CarColor::BLUE,
                'year' => '2023',
                'power' => CarPower::ELECTRIC,
                'seats' => '5',
                'registrationNumber' => 'CD234EF',
                'registrationDate' => '2023-06-01',
                'owner' => $this->getReference('client_4', User::class),
            ],

        ];
        foreach ($roomData as $data) {
            $car = new Car();

            $car->setBrand($data['brand']);
            $car->setModel($data['model']);
            $car->setColor($data['color']);
            $car->setYear($data['year']);
            $car->setPower($data['power']);
            $car->setSeats($data['seats']);
            $car->setRegistrationNumber($data['registrationNumber']);
            $car->setRegistrationDate(new DateTime($data['registrationDate']));
            $car->setOwner($data['owner']);

            $this->addReference('car_' . $data['number'], $car);

            $manager->persist($car);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class,
        ];
    }
}
