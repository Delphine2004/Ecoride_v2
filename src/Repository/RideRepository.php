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
    public function findRidesByFields(
        ?SearchRideDTO $criteria,
        int $limit = 10,
        string $orderBy = 'DESC'
    ): array {

        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.driver', 'u')->addSelect('u')
            ->leftJoin('r.car', 'c')->addSelect('c');

        if ($criteria->getRideId()) {
            $qb->andWhere('r.id = :id')
                ->setParameter('id', $criteria->getRideId());
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

        if ($criteria->getStatus()) {
            $qb->andWhere('r.status = :status')
                ->setParameter('status', $criteria->getStatus());
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
            ->orderBy('r.departureDate', $orderBy)
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

        // A FAIRE : rajouter availableseat != de 0

        $start = $departureDate->setTime(0, 0, 0);
        $end   = $departureDate->setTime(23, 59, 59);

        return $this->createQueryBuilder('r')
            ->leftJoin('r.driver', 'u')->addSelect('u')
            ->leftJoin('r.car', 'c')->addSelect('c')
            ->andWhere('r.departureDate BETWEEN :start AND :end')
            ->andWhere('r.departurePlace = :departurePlace')
            ->andWhere('r.arrivalPlace = :arrivalPlace')
            ->andWhere('r.availableSeats != 0')
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
        int $driverId
    ): array {
        $today = new DateTimeImmutable('today');
        $tomorrow = $today->modify('+1 day');

        return $this->createQueryBuilder('r')
            ->leftJoin('r.driver', 'u')->addSelect('u')
            ->andWhere('u.id = :driverId')
            ->andWhere(
                '(r.status IN (:alwaysVisibleStatuses))
        OR
        (
            r.status = :confirmed
            AND r.departureDate >= :today
            AND r.departureDate < :tomorrow
        )'
            )
            ->setParameter('driverId', $driverId)
            ->setParameter('alwaysVisibleStatuses', [
                RideStatus::PENDING->value,
                RideStatus::RUNNING->value,
            ])
            ->setParameter('confirmed', RideStatus::CONFIRMED->value)
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findUpcomingRideByClient(
        int $driverId
    ): array {
        $tomorrow = new DateTimeImmutable('tomorrow');

        return $this->createQueryBuilder('r')
            ->leftJoin('r.driver', 'u')->addSelect('u')
            ->andWhere('u.id = :driverId')
            ->andWhere('r.status = :status')
            ->andWhere('r.departureDate > :tomorrow')
            ->setParameter('driverId', $driverId)
            ->setParameter('status', RideStatus::CONFIRMED->value)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findHistoryRideByClient(
        int $driverId
    ): array {

        return $this->createQueryBuilder('b')
            ->leftJoin('b.ride', 'r')->addSelect('r')
            ->leftJoin('r.driver', 'u')->addSelect('u')
            ->andWhere('u.id = :driverId')
            ->setParameter('passengerId', $driverId)
            ->setParameter('statuses', [RideStatus::CANCELLED->value, RideStatus::COMPLETED->value])
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
