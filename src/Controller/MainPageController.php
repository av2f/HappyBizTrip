<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MainPageController extends AbstractController
{
    /**
     * @Route("/main", name="hbt_main")
     * 
     * Can access only if login ok
     * @isGranted("ROLE_USER")
     * 
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainPageController',
        ]);
    }
}
