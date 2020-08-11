<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Messaging;
use App\Repository\UserRepository;
use App\Repository\MessagingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagingController extends AbstractController
{
    /**
     * @Route("/messaging/{slug}", name="messaging")
     * 
     * @IsGranted("ROLE_USER")
     * 
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
     * @param Request $request
     * @param MessagingRepository $messageRepo
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepo
     * @return void
     */
    public function storeNewMessage(Request $request, MessagingRepository $messageRepo, UserRepository $userRepo,  EntityManagerInterface $em) { 
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('tok'.$this->getUser()->getId(), $data['_token'])) {
            // store new message
            $newMessage = new Messaging();
            $newMessage->setSender($this->getUser())
                ->setReceiver($userRepo->findOneBy(['id' => $data['receiver']]))
                ->setMessage(htmlspecialchars($data['message']))
            ;
            $em->persist($newMessage);
            $em->flush();
            // send new discussion feed
            //$discussions = $messageRepo->myFindDiscussion($this->getUser()->getId(), $data['receiver']);
            return new JsonResponse(['discussions' => $messageRepo->myFindDiscussion($this->getUser()->getId(), $data['receiver']), 'success' => '1'], 200);
        } else {
            return new JsonResponse(['error' => '2'], 400);
        }
    }

    /**
     * Retrieve the discussion feed
     * 
     * @Route("/api/discussion/{id}", name="discussion_feed", methods={"POST", "GET"})
     *
     * @param Request $request
     * @param MessagingRepository $messageRepo
     * @param User $profile
     * @return void
     */
    public function loadDiscussion(Request $request, MessagingRepository $messageRepo, User $profile){
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('tok'.$profile->getId(), $data['_token'])) {
            // !!!!!! il faut mettre à jour les message comme quoi ils sont lu
            return new JsonResponse(['discussions' => $messageRepo->myFindDiscussion($this->getUser()->getId(), $profile->getId()), 'success' => '1'], 200);
        } else {
            return new JsonResponse(['error' => '2'], 400);
        }
    }
}
