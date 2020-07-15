<?php

namespace App\Repository;

use App\Entity\Connect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConnectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Connect::class);
    }

    public function myCountFriends(int $id) 
    {
        $today = new \DateTime(); 
        return $this->createQueryBuilder('c')
            ->select('count(c.requester)')
            ->andWhere('c.state = :state AND (c.requester = :id OR c.requested = :id)')
            ->setParameter('id', $id)
            ->setParameter('state', 'C')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function myCountNewRequest(int $id) 
    {
        $today = new \DateTime(); 
        return $this->createQueryBuilder('c')
            ->select('count(c.requester)')
            ->andWhere('c.state = :state AND c.requested = :id')
            ->setParameter('id', $id)
            ->setParameter('state', 'W')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}