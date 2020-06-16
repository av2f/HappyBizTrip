<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"POST"})
     */
    public function index(Request $request, UserRepository $userRepo)
    {
        $result=trim($request->request->get('formSearch'));
        
        if(!empty($result)) {
            var_dump($result);
            $resultSearch = $userRepo->findbyCriteria($result);
            dd($resultSearch);
        }
        else {
            var_dump("c'est que du vent");
        }   
    
        
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }
}
