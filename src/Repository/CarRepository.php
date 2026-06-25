<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\User;
use App\Enum\CarBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function findCarsByDriver(
        int $driverId
    ): array {
        return $this->createQueryBuilder('c')
            ->where('c.owner = :driverId')
            ->andWhere('c.brand != :brand')
            ->setParameter('driverId', $driverId)
            ->setParameter('brand', CarBrand::NA->value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function isOwner(
        int $driverId,
        int $carId
    ): bool {
        return (bool) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.owner = :driverId')
            ->andWhere('c.id = :carId')
            ->setParameter('driverId', $driverId)
            ->setParameter('carId', $carId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function hasOtherCar(int $driverId, int $carId): bool
    {
        return (bool) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.owner = :driverId')
            ->andWhere('c.id != :carId')
            ->setParameter('driverId', $driverId)
            ->setParameter('carId', $carId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function hasActiveRide(int $carId): bool
    {
        $now = new \DateTimeImmutable();

        return (bool) $this->createQueryBuilder('c')
            ->select('COUNT(r.id)')
            ->join('c.rides', 'r')
            ->where('c.id = :id')
            ->andWhere('r.departureDate <= :now')
            ->andWhere('r.arrivalDate >= :now')
            ->setParameter('id', $carId)
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
