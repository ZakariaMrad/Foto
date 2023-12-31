<?php

namespace App\Repository;

use App\Entity\ComplaintStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComplaintStatus>
 *
 * @method ComplaintStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintStatus[]    findAll()
 * @method ComplaintStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplaintStatus::class);
    }

//    /**
//     * @return ComplaintStatus[] Returns an array of ComplaintStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ComplaintStatus
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
