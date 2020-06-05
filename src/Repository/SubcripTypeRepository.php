<?php

namespace App\Repository;

use App\Entity\SubcripType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubcripType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubcripType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubcripType[]    findAll()
 * @method SubcripType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubcripTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubcripType::class);
    }

    // /**
    //  * @return SubcripType[] Returns an array of SubcripType objects
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
    public function findOneBySomeField($value): ?SubcripType
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
