<?php

namespace App\Repository;

use App\Entity\Reçoit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reçoit>
 *
 * @method Reçoit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reçoit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reçoit[]    findAll()
 * @method Reçoit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReçoitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reçoit::class);
    }

//    /**
//     * @return Reçoit[] Returns an array of Reçoit objects
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

//    public function findOneBySomeField($value): ?Reçoit
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
