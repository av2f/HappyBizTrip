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
        
        $listConversation = $userRepo->myFindConversationList($this->getUser()->getId());
        
        dump(count($listConversation));

        if (count($listConversation) > 0) {
            foreach ($listConversation as $r) {
                dump($r);
            }
            // retrieve the fisrt conversation of list
            $discussion = $messageRepo->myFindDiscussion($this->getUser()->getId(), $listConversation[0]['u_id']);
            foreach($discussion as $d) {
                dump($d);
            }
        }
        
        die();

        return $this->render('messaging/index.html.twig', [
            'controller_name' => 'MessagingController',
        ]);
    }
}
