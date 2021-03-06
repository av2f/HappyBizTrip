<?php

namespace App\Repository;

use App\Entity\InterestType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InterestType|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterestType|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterestType[]    findAll()
 * @method InterestType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterestTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterestType::class);
    }

    /**
     * Query to retrieve interest type order by id
     *
     * @return void
     */
    public function findInterestTypeOrder() {
        return $this->createQueryBuilder('i')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function myFindInterestTypeIcon() {
        return $this->createQueryBuilder('i')
            ->select('i.id, i.iconType')
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return InterestType[] Returns an array of InterestType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InterestType
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
