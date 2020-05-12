<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="hbt_profile")
     * 
     * @isGranted("ROLE_USER")
     * 
     */
    public function index(User $user)
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user
        ]);
    }
}
