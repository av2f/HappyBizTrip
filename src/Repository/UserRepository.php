<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 10;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    
    /**
     * find all users by pseudo or name or lastname with value like criteria
     * do not take into account case sensitive
     * 
     * Author : Frederic Parmentier
     *
     * @param [type] $criteria
     * @return void
     */
    
     public function myFindbyCriteria(string $criteria, int $offset) : Paginator
     {
        $query = $this->createQueryBuilder('u')
            ->andWhere('(LOWER(u.pseudo) LIKE :criteria OR LOWER(u.lastName) LIKE :criteria OR LOWER(u.firstName) LIKE :criteria) AND (u.isActive = true AND u.isDeleted = false)')
            ->setParameter('criteria', '%'.strtolower($criteria).'%')
            ->orderBy('u.pseudo', 'ASC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
    }

     /*
    Requete mySQL : SELECT id, pseudo, visit.viewed_at FROM user INNER JOIN visit ON user.id = visitor_id WHERE visited_id = 205
    AND (visit.viewed_at IS NULL OR visit.viewed_at=CURRENT_DATE())
    */
    public function myFindVisitors(int $id, int $offset) : Paginator
    {
        $today = new \DateTime(); 
        $query = $this->createQueryBuilder('u')
            ->join('u.visitors','v')
            ->andwhere('v.visited = :id AND (v.viewedAt is NULL OR v.viewedAt BETWEEN :todayStart AND :todayEnd)')
            ->setParameter('id', $id)
            ->setParameter('todayStart', $today->format('Y-m-d 00:00:00'))
            ->setParameter('todayEnd', $today->format('Y-m-d 23:59:59'))
            ->orderBy('u.pseudo', 'ASC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;
         
        return new Paginator($query);
    }

    public function myCountNewVisit(int $id) 
    {
        $today = new \DateTime(); 
        return $this->createQueryBuilder('u')
            ->join('u.visitors', 'v')
            ->select('count(v.visitor)')
            ->andWhere('v.visited = :id AND (v.viewedAt is NULL OR v.viewedAt BETWEEN :todayStart AND :todayEnd) AND (u.isActive = true AND u.isDeleted = false)')
            ->setParameter('id', $id)
            ->setParameter('todayStart', $today->format('Y-m-d 00:00:00'))
            ->setParameter('todayEnd', $today->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
       

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
