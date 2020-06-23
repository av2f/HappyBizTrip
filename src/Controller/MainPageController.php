<?php

namespace App\Controller;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SubscriptionHistoryRepository;
use App\Repository\VisitRepository;
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
     * @Route("/visit/{slug}", name="show_visit")
     * 
     * Can access only if login ok
     * @isGranted("ROLE_USER")
     * 
     */
    public function showVisit(VisitRepository $visitRepo) {
        // récupérer la liste des nouvelles visites
        // effacer dans la table visits les visits
        // afficher la liste
        $visits = $visitRepo->findBy(['user' => $this->getUser()]);
        dd($visits);



    }
}
