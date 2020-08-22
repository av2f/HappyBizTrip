<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Twig\Environment;
use App\Repository\MessagingRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $messagingRepository;
    private $security;

    public function __construct(Security $security, Environment $twig, MessagingRepository $messagingRepository) {
        $this->security = $security;
        $this->twig = $twig;
        $this->messagingRepository = $messagingRepository;
    }
    
    
    /**
     * Create the global variable unread_messages to be available in twig
     * This value is the number of unreaded messaged for the user connected
     * which is displayed in header 
     *
     * @param ControllerEvent $event
     * @return void
     */
    public function onKernelController(ControllerEvent $event)
    {
        $user = new User();
        $user = $this->security->getUser();
        if ($user) {
            $this->twig->addGlobal('unread_messages', $this->messagingRepository->myCountUnreadMessage($user->getId()));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
