<?php

namespace App\Repository;

use App\Entity\Car;
use App\Entity\User;
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
            ->andWhere('c.owner = :driverId')
            ->setParameter('driverId', $driverId)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function isOwner(
        User $user,
        int $carId
    ): bool {
        return (bool) $this->createQueryBuilder('c')
            ->select('COUNT(c.user)')
            ->where('c.user = :user')
            ->where('c.id = :carId')
            ->setParameter('user', $user)
            ->setParameter('carId', $carId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function hasCar(
        User $user
    ): bool {
        return (bool) $this->createQueryBuilder('c')
            ->select('COUNT(c.user)')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
