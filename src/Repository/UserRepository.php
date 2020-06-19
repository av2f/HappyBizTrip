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
    
     public function findbyCriteria(string $criteria, int $offset) : Paginator
     {
        $query = $this->createQueryBuilder('u')
            ->andWhere('LOWER(u.pseudo) LIKE :criteria OR LOWER(u.lastName) LIKE :criteria OR LOWER(u.firstName) LIKE :criteria')
            ->setParameter('criteria', '%'.strtolower($criteria).'%')
            ->addSelect('u.id, u.slug, u.pseudo, u.lastName, u.firstName, u.avatar, u.profession, u.company')
            ->orderBy('u.pseudo', 'ASC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
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
