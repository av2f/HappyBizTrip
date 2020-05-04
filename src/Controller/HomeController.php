<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\HomeRegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * 
     */
    public function index(Request $request)
    {
        $locale = $request->getLocale();

        $user = new User;
        
        // create form
        $form = $this->createForm(HomeRegisterType::class, $user);
        
        // handle the submit
        $form->handleRequest($request);
        
        return $this->render('home/index.html.twig', [
            'locale' => $locale,
            'form' => $form->createView()
        ]);
    }
}
