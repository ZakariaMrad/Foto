<?php

namespace App\Repository;

use App\Entity\BanState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BanState>
 *
 * @method BanState|null find($id, $lockMode = null, $lockVersion = null)
 * @method BanState|null findOneBy(array $criteria, array $orderBy = null)
 * @method BanState[]    findAll()
 * @method BanState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BanStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BanState::class);
    }

//    /**
//     * @return BanState[] Returns an array of BanState objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BanState
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
