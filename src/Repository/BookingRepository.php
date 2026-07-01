<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\User;
use App\DTO\SearchBookingDTO;
use App\Enum\BookingStatus;

use DateTimeImmutable;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * @return Booking[] Returns an array of Booking objects
     */
    public function findBookingsByField(
        ?SearchBookingDTO $criteria,
        int $limit = 10,
        string $orderBy = 'DESC'
    ): array {

        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.passenger', 'u')->addSelect('u');

        if ($criteria->getBookingId()) {
            $qb->andWhere('b.id = :bookingId')
                ->setParameter('bookingId', $criteria->getBookingId());
        }

        if ($criteria->getPassengerId()) {
            $qb->andWhere('u.id = :passengerId')
                ->setParameter('passengerId', $criteria->getPassengerId());
        }

        if ($criteria->getLastName()) {
            $qb->andWhere('u.lastName LIKE :lastName')
                ->setParameter('lastName', '%' . $criteria->getLastName() . '%');
        }

        if ($criteria->getEmail()) {
            $qb->andWhere('u.email LIKE :email')
                ->setParameter('email', '%' . $criteria->getEmail() . '%');
        }

        if ($criteria->getStatuses()) {
            $qb->andWhere('b.status IN (:statuses)')
                ->setParameter('statuses', $criteria->getStatuses());
        }

        if ($criteria->getCreatedAt()) {
            $date = $criteria->getCreatedAt();

            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);

            $qb->andWhere('b.createdAt BETWEEN :createdAtStart AND :createdAtEnd')
                ->setParameter('createdAtStart', $start)
                ->setParameter('createdAtEnd', $end);
        }

        if ($criteria->getUpdatedAt()) {
            $date = $criteria->getUpdatedAt();

            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);

            $qb->andWhere('b.updatedAt BETWEEN :updatedAtStart AND :updatedAtEnd')
                ->setParameter('updatedAtStart', $start)
                ->setParameter('updatedAtEnd', $end);
        }

        return
            $qb
            ->orderBy('b.id', $orderBy)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function hasCurrentReservation(User $user): bool
    {
        return (bool) $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.user = :user')
            ->andWhere('b.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', BookingStatus::RUNNING)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findActionsBookingByClient(
        int $passengerId
    ): array {
        $today = new DateTimeImmutable('today');
        $tomorrow = $today->modify('+1 day');

        return $this->createQueryBuilder('b')
            ->leftJoin('b.ride', 'r')->addSelect('r')
            ->leftJoin('b.passenger', 'u')->addSelect('u')
            ->andWhere('u.id = :passengerId')
            ->andWhere(
                '(r.status IN (:alwaysVisibleStatuses))
        OR
        (
            r.status = :confirmed
            AND r.departureDate >= :today
            AND r.departureDate < :tomorrow
        )'
            )
            ->setParameter('passengerId', $passengerId)
            ->setParameter('alwaysVisibleStatuses', [BookingStatus::PENDING->value, BookingStatus::RUNNING->value])
            ->setParameter('confirmed', BookingStatus::CONFIRMED->value)
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findUpcomingBookingByClient(
        int $passengerId
    ): array {
        $tomorrow = new DateTimeImmutable('tomorrow');

        return $this->createQueryBuilder('b')
            ->leftJoin('b.ride', 'r')->addSelect('r')
            ->leftJoin('b.passenger', 'u')->addSelect('u')
            ->andWhere('u.id = :passengerId')
            ->andWhere('b.status = :status')
            ->andWhere('r.departureDate > :tomorrow')
            ->setParameter('passengerId', $passengerId)
            ->setParameter('status', BookingStatus::CONFIRMED->value)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
