<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
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
    public function index(SubscriptionHistoryRepository $subHisto)
    {
        $user = $this->getUser();

        $lastSubscription = $subHisto->findLastSubscriptionHistory($user);
        
        return new Response($this->twig->render('main/index.html.twig', [
            'user' => $user,
            'last_subscription' => $lastSubscription 
        ]));
    }

    /**
     * @Route("/visit/{page<\d+>?1}", name="show_visit")
     * 
     * Can access only if login ok
     * @isGranted("ROLE_USER")
     * 
     */
    public function showVisit(UserRepository $userRepo, int $page = 1)
    {
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $paginator = $userRepo->myFindVisitors($this->getUser()->getId(), $offset);
        
        // effacer dans la table visits les visits

        return new Response($this->twig->render('main/visit.html.twig', [
            'paginator' => $paginator,
            'nb_page' => ceil(count($paginator) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page,
            'user_id' => $this->getUser()->getId()
        ]));
    }
}
