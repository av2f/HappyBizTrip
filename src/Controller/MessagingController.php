<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\MessagingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagingController extends AbstractController
{
    /**
     * @Route("/messaging/{slug}", name="messaging")
     */
    public function index(UserRepository $userRepo, MessagingRepository $messageRepo)
    {
        // Retrieve list of users whom user has discussion
        $listConversations = $userRepo->myFindConversationList($this->getUser()->getId());
        
        if (count($listConversations) > 0) {
            // retrieve the fisrt conversation of list (last written)
            $discussions = $messageRepo->myFindDiscussion($this->getUser()->getId(), $listConversations[0]['u_id']);
        } else {
            $discussions = [];
        }

        return $this->render('messaging/index.html.twig', [
            'list_conversations' => $listConversations,
            'discussions' => $discussions,
        ]);
    }

    
    /**
     * Retrieve new message, store it and send the whole discussion feed
     *
     * @Route("/api/storemsg", name="store_message", methods={"POST"})
     * 
     * @param MessagingRepository $messageRepo
     * @param EntityManagerInterface $em
     * @param User $profile
     * @return void
     */
    public function storeNewMessage(MessagingRepository $messageRepo, EntityManagerInterface $em, User $profile) {
        $discussions = $messageRepo->myFindDiscussion(41, 80);
        $response = $this->json($discussions, 200,[],[]);
        return $response;

    
    }
}
