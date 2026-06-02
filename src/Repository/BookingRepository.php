<?php

namespace App\Repository;

use App\Entity\Booking;
use App\DTO\SearchBooking;
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
        ?SearchBooking $criteria,
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

        if ($criteria->getStatus()) {
            $qb->andWhere('b.status = :status')
                ->setParameter('status', $criteria->getStatus()->value);
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
}
