<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    /* public function myCountNewVisit(int $id) 
    {
        $today = new \DateTime(); 
        return $this->createQueryBuilder('v')
            ->select('count(v.visitor)')
            ->andWhere('v.visited = :id AND (v.viewedAt is NULL OR v.viewedAt BETWEEN :todayStart AND :todayEnd)')
            ->setParameter('id', $id)
            ->setParameter('todayStart', $today->format('Y-m-d 00:00:00'))
            ->setParameter('todayEnd', $today->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    } */

    public function myFindVisit(int $idVisited, int $idVisitor)
    {
        $today = new \DateTime();
        return $this->createQueryBuilder('v')
            ->andWhere('v.visited = :idVisited AND v.visitor = :idVisitor')
            ->setParameter('idVisited', $idVisited)
            ->setParameter('idVisitor', $idVisitor)
            ->getQuery()
            ->getResult()
        ;
    }
}