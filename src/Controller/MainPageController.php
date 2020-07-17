<?php

namespace App\Controller;

use App\Repository\ConnectRepository;
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
    public function index(SubscriptionHistoryRepository $subHisto, UserRepository $userRepo, ConnectRepository $connectRepo)
    {
        $lastSubscription = $subHisto->findLastSubscriptionHistory($this->getUser());
        $newVisit = $userRepo->myCountNewVisit($this->getUser()->getId());
        $friends = $connectRepo->myCountFriends($this->getUser()->getId());
        $newRequest = $connectRepo->myCountNewRequest($this->getUser()->getId());

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
