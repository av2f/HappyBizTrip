<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, User::class);
    }

    /**
     * find all users by pseudo or name or lastname with value like criteria
     * do not take into account case sensitive
     * 
     * Author : Frederic Parmentier
     * Date : 07/22/2020
     *
     * @param [type] $criteria
     * @return void
     */
     public function myFindbyCriteria(string $criteria, int $offset) : Paginator {
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

    /**
     * Retrieve number of new requests to be HappyBizFriends
     * Author : F. Parmentier
     * Date : 07/22/2020
     *
     * @param integer $id
     * @param string $state
     * @return void
     */
    public function myCountNewRequest(int $id, string $state) {
        return $this->createQueryBuilder('u')
            ->join('u.requesters', 'c')
            ->select('count(c.requester)')
            ->andWhere('c.requested = :id AND c.state = :state AND (u.isActive = true AND u.isDeleted = false)')
            ->setParameter('id', $id)
            ->setParameter('state', $state)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Retrieve the number of HappyBizFriend of the profile
     * Author : F. Parmentier
     * Date : 07/22/2020 
     *
     * @param integer $id
     * @param string $state
     * @return void
     */
    public function myCountFriends(int $id, string $state) {
        $sql = "SELECT (SELECT COUNT(u.id) AS cpt FROM user u INNER JOIN user_connect c ON u.id = c.requested_id WHERE c.requester_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false))
        + (SELECT COUNT(u.id) AS cpt FROM user u INNER JOIN user_connect c ON u.id = c.requester_id WHERE c.requested_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false))
        AS totalFriends";

        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('App\Entity\User', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addScalarResult('cpt', 'cpt');
        $rsm->addScalarResult('totalFriends', 'totalFriends');

        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requests');
        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requesters');

        $rsm->addFieldResult('c', 'state', 'state');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $query->setParameter(2, $state);
        $query->setParameter(3, $id);
        $query->setParameter(4, $state);

        return $query->getSingleScalarResult();
    }

    /**
     * Retrieve the number of new visit
     * Author : F. Parmentier
     * Date : 07/22/2020
     * 
     * @param integer $id
     * @return void
     */
    public function myCountNewVisit(int $id) {
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

     /**
     * Retrieve the list of new users who have visited the profile
     * Author : F. Parmentier
     * Date : 07/22/2020
     * 
     * mySql Request : SELECT id, pseudo, visit.viewed_at FROM user INNER JOIN visit ON user.id = visitor_id WHERE visited_id = 205
     * AND (visit.viewed_at IS NULL OR visit.viewed_at=CURRENT_DATE())
     *
     * @param integer $id
     * @param integer $offset
     * @return Paginator
     */
    public function myFindVisitors(int $id, int $offset) : Paginator {   
        $today = new \DateTime(); 
        $query = $this->createQueryBuilder('u')
            ->join('u.visitors','v')
            ->andwhere('v.visited = :id AND (v.viewedAt is NULL OR v.viewedAt BETWEEN :todayStart AND :todayEnd) AND (u.isActive = true AND u.isDeleted = false)')
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
    
    /**
     * Retrieve the list of HappyBizFriends of the profile
     * Author : F. Parmentier
     * Date : 07/22/2020
     * 
     * mySql request : SELECT id, pseudo, user_connect.state FROM user INNER JOIN user_connect ON user.id = requested_id WHERE requester_id = 4 AND user_connect.state="C"
     * UNION SELECT id, pseudo, user_connect.state FROM user INNER JOIN user_connect ON user.id = requester_id WHERE requested_id = 4 AND user_connect.state="C"
     *
     * @param integer $id
     * @param string $state
     * @param integer $offset
     * @return void
     */
    public function myFindFriends(int $id, string $state, int $offset) {
        $sql = "SELECT u.id, u.pseudo, u.first_name, u.last_name, u.slug, u.avatar, u.profession, u.company FROM user u INNER JOIN user_connect c ON u.id = c.requested_id WHERE c.requester_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false)
                UNION SELECT u.id, u.pseudo, u.first_name, u.last_name, u.slug, u.avatar, u.profession, u.company FROM user u INNER JOIN user_connect c ON u.id = c.requester_id WHERE c.requested_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false)
                ORDER BY pseudo ASC LIMIT ?,?";
        
        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('App\Entity\User', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'pseudo', 'pseudo');
        $rsm->addFieldResult('u', 'slug', 'slug');
        $rsm->addFieldResult('u', 'last_name', 'lastName');
        $rsm->addFieldResult('u', 'first_name', 'firstName');
        $rsm->addFieldResult('u', 'avatar', 'avatar');
        $rsm->addFieldResult('u', 'profession', 'profession');
        $rsm->addFieldResult('u', 'company', 'company');
        $rsm->addFieldResult('u', 'is_active', 'isActive');
        $rsm->addFieldResult('u', 'is_deleted', 'isDeleted');
        
        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requests');
        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requesters');

        $rsm->addFieldResult('c', 'state', 'state');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $query->setParameter(2, $state);
        $query->setParameter(3, $id);
        $query->setParameter(4, $state);
        $query->setParameter(5, $offset);
        $query->setParameter(6, self::PAGINATOR_PER_PAGE);

        return $query->getResult();
    }

    /**
     * Retrieve new requesters
     *
     * @param integer $id
     * @param integer $offset
     * @param string $state
     * @return Paginator
     */
    public function myFindNewRequest(int $id, int $offset, string $state) : Paginator {
        $query = $this->createQueryBuilder('u')
            ->join('u.requesters', 'c')
            ->andWhere('c.requested = :id AND c.state = :state AND (u.isActive = true AND u.isDeleted = false)')
            ->setParameter('id', $id)
            ->setParameter('state', $state)
            ->orderBy('u.pseudo', 'ASC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;
        return new Paginator($query);
    }

    /**
     * Retrieve the list of HappyBizFriends
     * Author : F. Parmentier
     * Date : 07/22/2020
     *
     * @param integer $id
     * @param string $stateFriend
     * @return void
     */
    public function myFindListFriends(int $id, string $stateFriend) {
        $sql = "SELECT u.id, u.pseudo, c.action_at, c.state FROM user u INNER JOIN user_connect c ON u.id = c.requested_id WHERE c.requester_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false)
                UNION SELECT u.id, u.pseudo, c.action_at, c.state FROM user u INNER JOIN user_connect c ON u.id = c.requester_id WHERE c.requested_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false)
                ORDER BY pseudo ASC";
        
        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('App\Entity\User', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'pseudo', 'pseudo');
        $rsm->addFieldResult('u', 'is_active', 'isActive');
        $rsm->addFieldResult('u', 'is_deleted', 'isDeleted');
        
        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requests');
        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requesters');

        $rsm->addFieldResult('c', 'action_at', 'actionAt');
        $rsm->addFieldResult('c', 'state', 'state');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $query->setParameter(2, $stateFriend);
        $query->setParameter(3, $id);
        $query->setParameter(4, $stateFriend);
        
        return$query->getScalarResult();
    }

    /**
     * Retrieve list of users who request a connection
     *
     * @param integer $id
     * @param string $state
     * @return void
     */
    public function myFindListNewRequester(int $id, string $state) {
        $sql = "SELECT u.id, u.pseudo, c.action_at, c.state FROM user u INNER JOIN user_connect c ON u.id = c.requester_id WHERE c.requested_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false)
                ORDER BY pseudo ASC";
        
        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('App\Entity\User', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'pseudo', 'pseudo');
        $rsm->addFieldResult('u', 'is_active', 'isActive');
        $rsm->addFieldResult('u', 'is_deleted', 'isDeleted');
        
        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requesters');

        $rsm->addFieldResult('c', 'action_at', 'actionAt');
        $rsm->addFieldResult('c', 'state', 'state');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $query->setParameter(2, $state);
        
        return $query->getScalarResult();
    }

    /**
     * Retrieve list of users whom user request a connection
     *
     * @param integer $id
     * @param string $state
     * @return void
     */
    public function myFindListNewRequested(int $id, string $state) {
        $sql = "SELECT u.id, u.pseudo, c.action_at, c.state FROM user u INNER JOIN user_connect c ON u.id = c.requested_id WHERE c.requester_id = ? AND c.state = ? AND (u.is_active = true AND u.is_deleted = false)
                ORDER BY pseudo ASC";
        
        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('App\Entity\User', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'pseudo', 'pseudo');
        $rsm->addFieldResult('u', 'is_active', 'isActive');
        $rsm->addFieldResult('u', 'is_deleted', 'isDeleted');
        
        $rsm->addJoinedEntityResult('App\Entity\Connect', 'c', 'u', 'requests');

        $rsm->addFieldResult('c', 'action_at', 'actionAt');
        $rsm->addFieldResult('c', 'state', 'state');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $query->setParameter(2, $state);
        
        return $query->getScalarResult();
    }

    public function myFindConversationList(int $id) {
        $sql = "SELECT * FROM (
                SELECT u.id, u.pseudo, u.slug, u.avatar, u.is_deleted, u.is_active, m.sender_id, m.message, m.created_at, m.is_readed FROM user u INNER JOIN messaging m ON u.id = receiver_id WHERE sender_id = ? AND (INSTR(m.deleted_by, ?) IS NULL) AND m.created_at =
                (
                    SELECT MAX(created_at) FROM messaging WHERE sender_id = ? AND receiver_id = id
                )
                UNION
                SELECT u.id, u.pseudo, u.slug, u.avatar, u.is_deleted, u.is_active, m.sender_id, m.message, m.created_at, m.is_readed FROM user u INNER JOIN messaging m ON u.id = sender_id WHERE receiver_id = ? AND (INSTR(m.deleted_by, ?) IS NULL) AND m.created_at =
                (
                    SELECT MAX(created_at) FROM messaging WHERE sender_id = id AND receiver_id = ?
                )
                ) AS RESULT
                HAVING created_at IN (SELECT MAX(created_at) FROM messaging WHERE (sender_id = id AND receiver_id = ?) OR (receiver_id = id AND sender_id = ?) GROUP BY id) ORDER BY created_at DESC";

        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('App\Entity\User', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'pseudo', 'pseudo');
        $rsm->addFieldResult('u', 'slug', 'slug');
        $rsm->addFieldResult('u', 'avatar', 'avatar');
        $rsm->addFieldResult('u', 'is_active', 'isActive');
        $rsm->addFieldResult('u', 'is_deleted', 'isDeleted');
        
        $rsm->addJoinedEntityResult('App\Entity\Messaging', 'm', 'u', 'receivers');
        $rsm->addJoinedEntityResult('App\Entity\Messaging', 'm', 'u', 'senders');

        $rsm->addMetaResult('m', 'sender_id', 'sender_id', true);
        $rsm->addFieldResult('m', 'message', 'message');
        $rsm->addFieldResult('m', 'created_at', 'createdAt');
        $rsm->addFieldResult('m', 'is_readed', 'isReaded');
        $rsm->addFieldResult('m', 'deleted_by', 'deletedBy');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id);
        $query->setParameter(2, $id);
        $query->setParameter(3, $id);
        $query->setParameter(4, $id);
        $query->setParameter(5, $id);
        $query->setParameter(6, $id);
        $query->setParameter(7, $id);
        $query->setParameter(8, $id);
        
        return $query->getScalarResult();
    }
}
