<?php

namespace App\Repository;

use App\Entity\Document;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findByUser(Utilisateur $user)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(?string $search, ?string $type, ?int $auteurId, ?int $categorieId): array
    {
        $qb = $this->createQueryBuilder('d');

        if ($search) {
            $qb->andWhere('d.titreDoc LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        }

        if ($type) {
            $entityClass = 'App\Entity\\' . ucfirst($type);
            $qb->andWhere('d INSTANCE OF :type')
            ->setParameter('type', $entityClass);
        }

        if ($auteurId) {
            $qb->andWhere('d.auteur = :auteurId')
            ->setParameter('auteurId', $auteurId);
        }

        if ($categorieId) {
            $qb->join('d.categories', 'cat')
            ->andWhere('cat.id = :categorieId')
            ->setParameter('categorieId', $categorieId);
        }

        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return Document[] Returns an array of Document objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Document
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
