<?php

namespace App\Repository;

use App\Entity\UserBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBlock>
 *
 * @method UserBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBlock[]    findAll()
 * @method UserBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBlock::class);
    }

//    /**
//     * @return UserBlock[] Returns an array of UserBlock objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserBlock
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
