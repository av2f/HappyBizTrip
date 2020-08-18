<?php

namespace App\Repository;

use App\Entity\Messaging;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messaging::class);
    }

    public function myFindDiscussion(int $id1, int $id2) {

        $sql = "SELECT sender_id, receiver_id, message, created_at, readed_at, is_readed FROM messaging m WHERE sender_id = ? AND receiver_id = ?
            UNION
            SELECT sender_id, receiver_id, message, created_at, readed_at, is_readed FROM messaging m WHERE sender_id = ? AND receiver_id = ?
            ORDER BY created_at ASC";
    
        $rsm = new ResultSetMapping();

        $rsm->addEntityResult('App\Entity\Messaging', 'm');
        $rsm->addMetaResult('m', 'sender_id', 'sender_id', true);
        $rsm->addMetaResult('m', 'receiver_id', 'receiver_id', true);
        $rsm->addFieldResult('m', 'message', 'message');
        $rsm->addFieldResult('m', 'created_at', 'createdAt');
        $rsm->addFieldResult('m', 'readed_at', 'readedAt');
        $rsm->addFieldResult('m', 'is_readed', 'isReaded');
        
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $id1);
        $query->setParameter(2, $id2);
        $query->setParameter(3, $id2);
        $query->setParameter(4, $id1);

        return $query->getScalarResult();
    }

    /**
     * Update all messages not yet readed between sender and user connected to put
     * isReaded to true
     * readedAt to date now
     *
     * @param integer $senderId
     * @param integer $id
     * @return void
     */
    public function updateMessageToRead(int $senderId, int $id) {
        $today = new \DateTime(); 
        return $this->createQueryBuilder('m')
            -> update()
            -> set('m.isReaded', true)
            -> set('m.readedAt', '?1')
            -> where('(m.sender = :sender AND m.receiver = :receiver) AND m.isReaded = false')
            -> setParameter('sender', $senderId)
            -> setParameter('receiver', $id)
            -> setParameter(1, $today)
            ->getQuery()
            ->execute() 
        ;
    }
}
