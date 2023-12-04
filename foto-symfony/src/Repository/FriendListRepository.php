<?php

namespace App\Repository;

use App\Entity\FriendList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FriendList>
 *
 * @method FriendList|null find($id, $lockMode = null, $lockVersion = null)
 * @method FriendList|null findOneBy(array $criteria, array $orderBy = null)
 * @method FriendList[]    findAll()
 * @method FriendList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FriendList::class);
    }

//    /**
//     * @return FriendList[] Returns an array of FriendList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FriendList
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
