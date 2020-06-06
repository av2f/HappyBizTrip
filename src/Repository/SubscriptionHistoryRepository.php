<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\SubscriptionHistory;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method SubscriptionHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubscriptionHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubscriptionHistory[]    findAll()
 * @method SubscriptionHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriptionHistory::class);
    }

    /**
     * Retrieve the last subscription history of $subscriber
     * Return Null if no subscription
     * Author : F. Parmentier
     * Date : 2020/06/06
     *
     * @param User $subscriber
     * @return void
     */
    public function findLastSubscriptionHistory(User $subscriber){
        return $this->createQueryBuilder('s')
            ->where('s.subscriber= :user')
            ->orderBy('s.subscribEndAt', 'DESC')
            ->setParameter('user', $subscriber)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return SubscriptionHistory[] Returns an array of SubscriptionHistory objects
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
    public function findOneBySomeField($value): ?SubscriptionHistory
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
