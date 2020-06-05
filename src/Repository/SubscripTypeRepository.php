<?php

namespace App\Repository;

use App\Entity\SubscripType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubcripType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubcripType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubcripType[]    findAll()
 * @method SubcripType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscripTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscripType::class);
    }

    // /**
    //  * @return SubscripType[] Returns an array of SubscripType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubscripType
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
