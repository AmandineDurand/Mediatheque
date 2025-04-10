<?php

namespace App\Repository;

use App\Entity\Contentieux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contentieux>
 *
 * @method Contentieux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contentieux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contentieux[]    findAll()
 * @method Contentieux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentieuxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contentieux::class);
    }

//    /**
//     * @return Contentieux[] Returns an array of Contentieux objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Contentieux
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
