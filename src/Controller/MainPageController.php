<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SubscriptionHistoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainPageController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    /**
     * @Route("/main", name="hbt_main")
     * 
     * Can access only if login ok
     * @isGranted("ROLE_USER")
     * 
     */
    public function index(SubscriptionHistoryRepository $subHisto, UserRepository $userRepo)
    {
        $lastSubscription = $subHisto->findLastSubscriptionHistory($this->getUser());
        $newVisit = $userRepo->myCountNewVisit($this->getUser()->getId());
        $friends = $userRepo->myCountFriends($this->getUser()->getId(), "C");
        $newRequest = $userRepo->myCountNewRequest($this->getUser()->getId(), "W");

        return new Response($this->twig->render('main/index.html.twig', [
            'user' => $this->getUser(),
            'last_subscription' => $lastSubscription,
            'new_visit' => $newVisit,
            'friends' => $friends,
            'new_request'=>$newRequest
        ]));
    }

    /**
     * @Route("/visit/{page<\d+>?1}", name="show_visit")
     * 
     * Can access only if login ok
     * @isGranted("ROLE_USER")
     * 
     */
    public function showVisit(UserRepository $userRepo, VisitRepository $visitRepo, EntityManagerInterface $entityManager, int $page = 1)
    {
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $paginator = $userRepo->myFindVisitors($this->getUser()->getId(), $offset);
        // List of users who are friends or blacklisted
        $listFriendAndBlackList = $userRepo->myFindListFriendAndBlackList($this->getUser()->getId(), "C", "B");
        // List of requesters which ask to be friend
        $listNewRequester = $userRepo->myFindListNewRequester($this->getUser()->getId(), "W");
        // List of users whom profile request to be friend
        $listNewRequested = $userRepo->myFindListNewRequested($this->getUser()->getId(), "W");

        if (count($paginator) > 0) {
            $today = new \DateTime();
            foreach($paginator as $p) {
                $visit = $visitRepo->findOneBy(['visited'=> $this->getUser(), 'visitor' => $p]);
                if ($visit->getViewedAt() == Null) {
                    $visit->setViewedAt($today);
                }               
            }
            $entityManager->flush();
        }

        return new Response($this->twig->render('main/visit.html.twig', [
            'paginator' => $paginator,
            'friends_blackList' => $listFriendAndBlackList,
            'new_requester' => $listNewRequester,
            'new_requested' => $listNewRequested,
            'nb_page' => ceil(count($paginator) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page,
            'user_id' => $this->getUser()->getId()
        ]));
    }

    /**
     * @Route("/HappyBizFriends/{page<\d+>?1}", name="show_friends")
     * 
     * Can access only if login ok
     * @isGranted("ROLE_USER")
     * 
     */
    public function showFriends(UserRepository $userRepo, int $page = 1)
    {
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $paginator = $userRepo->myFindFriends($this->getUser()->getId(), "C", $offset);

        return new Response($this->twig->render('main/friend.html.twig', [
            'paginator' => $paginator,
            'nb_page' => ceil(count($paginator) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page,
            'user_id' => $this->getUser()->getId()
        ]));
    }
}
