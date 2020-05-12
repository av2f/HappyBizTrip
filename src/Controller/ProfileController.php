<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
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
     * @return Response
     * 
     */
    public function index(Request $request, User $user)
    {
        
        // create form
        $form = $this->createForm(ProfileType::class, $user);
        
        // handle the submit
        $form->handleRequest($request);

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
