<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\MessagingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/api/storemsg", name="store_message", methods={"GET", "POST"})
     * 
     * @param MessagingRepository $messageRepo
     * @param EntityManagerInterface $em
     * @param User $profile
     * @return void
     */
    public function storeNewMessage(MessagingRepository $messageRepo) {
        $discussions = $messageRepo->myFindDiscussion(201, 318);
        //$discussions = $messageRepo->findAll();
        
        // return = $this->json($discussions, 200,[],[]);
        return new JsonResponse(['discussions' => $discussions, 'success' => '1'], 200);
    
    }
}
