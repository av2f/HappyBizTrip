<?php

namespace App\Controller;

use App\Repository\MessagingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
