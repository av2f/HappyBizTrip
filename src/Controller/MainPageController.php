<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use App\Repository\ConnectRepository;
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
    public function index(SubscriptionHistoryRepository $subHisto, UserRepository $userRepo) {
        return new Response($this->twig->render('main/index.html.twig', [
            'user' => $this->getUser(),
            'last_subscription' => $subHisto->findLastSubscriptionHistory($this->getUser()),
            'new_visit' => $userRepo->myCountNewVisit($this->getUser()->getId()),
            'friends' => $userRepo->myCountFriends($this->getUser()->getId(), "C"),
            'new_request'=>$userRepo->myCountNewRequest($this->getUser()->getId(), "W")
        ]));
    }

    /**
     * @Route("/visits/{page<\d+>?1}", name="show_visits")
     * 
     * Can access only if login ok
     * @isGranted("ROLE_USER")
     * 
     */
    public function showVisit(UserRepository $userRepo, VisitRepository $visitRepo, ConnectRepository $connectRepo, EntityManagerInterface $entityManager, int $page = 1) {
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $totProfile = $userRepo->myCountNewVisit($this->getUser()->getId());
        $paginator = $userRepo->myFindVisitors($this->getUser()->getId(), $offset);
        /*
        *  friends = List of users who are friends
        *   new_requester = List of requesters which ask to be friend
        *   new_requested = List of users whom profile request to be friend
        *   has_blacklisted = List of users who has been blacklisted
        *   backlisted = list of users who has blacklisted the user
        */
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
            'tot_profile' => $totProfile,
            'friends' => $userRepo->myFindListFriends($this->getUser()->getId(), "C"),
            'new_requester' => $userRepo->myFindListNewRequester($this->getUser()->getId(), "W"),
            'new_requested' => $userRepo->myFindListNewRequested($this->getUser()->getId(), "W"),
            'has_blacklisted' => $connectRepo->findBy(['requester'=>$this->getUser()->getId(), 'state'=>'B']),
            'blacklisted' => $connectRepo->findBy(['requested'=>$this->getUser()->getId(), 'state'=>'B']),
            'nb_page' => ceil(($totProfile) / $userRepo::PAGINATOR_PER_PAGE),
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
    public function showFriends(UserRepository $userRepo, int $page = 1) {
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $totProfile =  $userRepo->myCountFriends($this->getUser()->getId(), "C");

        return new Response($this->twig->render('main/friend.html.twig', [
            'paginator' => $userRepo->myFindFriends($this->getUser()->getId(), "C", $offset),
            'tot_profile' => $totProfile,
            'nb_page' => ceil(($totProfile) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page,
            'user_id' => $this->getUser()->getId()
        ]));
    }

    /**
     * show list of new requests submitted by users
     * 
     * @Route("/solicitations/{page<\d+>?1}", name="show_solicitations")
     *
     * @param UserRepository $userRepo
     * @param integer $page
     * @return void
     */
    public function showNewRequest(UserRepository $userRepo, int $page = 1) {
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $totProfile = $userRepo->myCountNewRequest($this->getUser()->getId(), "W");

        return new Response($this->twig->render('main/solicitation.html.twig', [
            'paginator' => $userRepo->myFindListNewRequester($this->getUser()->getId(), "W"),
            'tot_profile' => $totProfile,
            'nb_page' => ceil(($totProfile) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page,
            'user_id' => $this->getUser()->getId()
        ]));

    }
}