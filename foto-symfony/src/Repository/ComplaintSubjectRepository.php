<?php

namespace App\Repository;

use App\Entity\ComplaintSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComplaintSubject>
 *
 * @method ComplaintSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintSubject[]    findAll()
 * @method ComplaintSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintSubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplaintSubject::class);
    }

//    /**
//     * @return ComplaintSubject[] Returns an array of ComplaintSubject objects
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

//    public function findOneBySomeField($value): ?ComplaintSubject
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
