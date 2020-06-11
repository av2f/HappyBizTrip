<?php

namespace App\EventSubscriber;

use App\Entity\SubscriptionHistory;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubscriptionCheckSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security, SubscriptionRepository $repo, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->repo = $repo;
        $this->em = $em;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }

    
    /**
     * Verify if current subscription is still valid
     * if not :
     * 1. store the subscription in subscriptionHistory entity
     * 2. remove subscription from subscription entity
     * 3. put IsSubscribed to false
     *
     * @param ControllerEvent $event
     * @return void
     */
    public function onKernelController(ControllerEvent $event)
    {
        $user = new User();
        $user = $this->security->getUser();
        //$user = $this->user->get_current_user();

        if ($user && $user->getIsSubscribed()) {
            $subscription = $this->repo->findOneBy(['subscriber'=>$user]);
            $date = new \DateTime('now');
            $date = $date->format('Ymd');
            $endSubscriptionDate = $subscription->getSubscribEndAt()->format('Ymd');
            if ($endSubscriptionDate < $date) {     // end date of subscription postponed
                $subscriptionHistory = new SubscriptionHistory();
                $subscriptionHistory -> setSubscriber($subscription->getSubscriber())
                                    -> setSubscriberType($subscription->getSubscriberType())
                                    -> setSubscribPayAt($subscription->getSubscribPayAt())
                                    -> setSubscribBeginAt($subscription->getSubscribBeginAt())
                                    -> setSubscribEndAt($subscription->getSubscribEndAt());
                $this->em->persist($subscriptionHistory);
                $this->em->remove($subscription);
                $user->setIsSubscribed(false);
                $this->em->flush();
            }
        }
    }
}
