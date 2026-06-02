<?php

namespace App\Repository;

use App\Entity\User;
use App\Enum\UserRole;
use App\DTO\SearchUser;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByFieldAndRole(
        ?SearchUser $criteria,
        UserRole $role,
        int $limit = 10,
        string $orderBy = 'DESC'
    ): array {
        $qb = $this->createQueryBuilder('u');

        if ($criteria->getUserId()) {
            $qb->andWhere('u.id = :id')
                ->setParameter('id', $criteria->getUserId());
        }

        if ($criteria->getLastName()) {
            $qb->andWhere('u.lastName LIKE :lastName')
                ->setParameter('lastName', '%' . $criteria->getLastName() . '%');
        }

        if ($criteria->getEmail()) {
            $qb->andWhere('u.email LIKE :email')
                ->setParameter('email', '%' . $criteria->getEmail() . '%');
        }

        $qb->andWhere('u.roles = :role')
            ->setParameter('role', $role->value);

        return $qb->orderBy('u.id', $orderBy)

            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
