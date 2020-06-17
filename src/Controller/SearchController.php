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
        $stringToSearch=htmlspecialchars(trim($request->request->get('formSearch')));
        $resultSearch = ($userRepo->findbyCriteria($stringToSearch)); 
    
        return $this->render('search/index.html.twig', [
            'resultSearch' => $resultSearch,
            'stringToSearch' => $stringToSearch,
        ]);

    }
}
