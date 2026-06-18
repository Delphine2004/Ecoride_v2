<?php

namespace App\Repository;

use App\Entity\Ride;
use App\DTO\SearchRideDTO;
use App\Enum\RideStatus;

use DateTimeImmutable;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ride>
 */
class RideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ride::class);
    }

    /**
     * @return Ride[] Returns an array of Ride objects
     */
    public function findRidesByField(
        ?SearchRideDTO $criteria,
        int $limit = 10,
        string $orderBy = 'DESC'
    ): array {

        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.driver', 'u')->addSelect('u');

        if ($criteria->getRideId()) {
            $qb->andWhere('r.id = :rideId')
                ->setParameter('rideId', $criteria->getRideId());
        }

        if ($criteria->getDriverId()) {
            $qb->andWhere('u.id = :driverId')
                ->setParameter('driverId', $criteria->getDriverId());
        }

        if ($criteria->getDeparturePlace()) {
            $qb->andWhere('r.departurePlace = :departurePlace')
                ->setParameter('departurePlace', $criteria->getDeparturePlace());
        }

        if ($criteria->getArrivalPlace()) {
            $qb->andWhere('r.arrivalPlace = :arrivalPlace')
                ->setParameter('arrivalPlace', $criteria->getArrivalPlace());
        }

        if ($criteria->getStatuses()) {
            $qb->andWhere('r.status IN (:statuses)')
                ->setParameter('statuses', $criteria->getStatuses());
        }

        if ($criteria->getDepartureDate()) {
            $date = $criteria->getDepartureDate();

            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);

            $qb->andWhere('r.departureDate BETWEEN :departureDateStart AND :departureDateEnd')
                ->setParameter('departureDateStart', $start)
                ->setParameter('departureDateEnd', $end);
        }

        if ($criteria->getArrivalDate()) {
            $date = $criteria->getArrivalDate();

            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);

            $qb->andWhere('r.arrivalDate BETWEEN :arrivalDateStart AND :arrivalDateEnd')
                ->setParameter('arrivalDateStart', $start)
                ->setParameter('arrivalDateEnd', $end);
        }

        if ($criteria->getCreatedAt()) {
            $date = $criteria->getCreatedAt();

            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);

            $qb->andWhere('r.createdAt BETWEEN :createdAtStart AND :createdAtEnd')
                ->setParameter('createdAtStart', $start)
                ->setParameter('createdAtEnd', $end);
        }

        if ($criteria->getUpdatedAt()) {
            $date = $criteria->getUpdatedAt();

            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);

            $qb->andWhere('r.updatedAt BETWEEN :updatedAtStart AND :updatedAtEnd')
                ->setParameter('updatedAtStart', $start)
                ->setParameter('updatedAtEnd', $end);
        }

        return
            $qb
            ->orderBy('r.id', $orderBy)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAvailableRides(
        DateTimeImmutable $departureDate,
        string $departurePlace,
        string $arrivalPlace
    ): array {

        $start = $departureDate->setTime(0, 0, 0);
        $end   = $departureDate->setTime(23, 59, 59);

        return $this->createQueryBuilder('r')
            ->leftJoin('r.driver', 'u')->addSelect('u')
            ->leftJoin('r.car', 'c')->addSelect('c')
            ->andWhere('r.departureDate BETWEEN :start AND :end')
            ->andWhere('r.departurePlace = :departurePlace')
            ->andWhere('r.arrivalPlace = :arrivalPlace')
            ->andWhere('r.status = :status')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('departurePlace', $departurePlace)
            ->setParameter('arrivalPlace', $arrivalPlace)
            ->setParameter('status', RideStatus::CONFIRMED->value)
            ->getQuery()
            ->getResult();
    }

    public function findActionsRideByClient(
        SearchRideDTO $criteria
    ): array {
        $today = new DateTimeImmutable('today');

        return $this->createQueryBuilder('r')
            ->leftJoin('r.driver', 'u')->addSelect('u')
            ->andWhere('u.id = :driverId')
            ->andWhere('r.status IN (:statuses)')
            ->andWhere('r.departureDate >= :today')
            ->setParameter('driverId', $criteria->getDriverId())
            ->setParameter('statuses', $criteria->getStatuses())
            ->setParameter('today', $today)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
