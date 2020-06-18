<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }
    
    /**
     * @Route("/search/{page<\d+>?1}", name="search", methods={"POST", "GET"})
     */
    public function index(Request $request, UserRepository $userRepo, int $page)
    {
        $stringToSearch=htmlspecialchars(trim($request->request->get('formSearch')));
        $offset = 0;
        $resultSearch = $userRepo->findbyCriteria($stringToSearch, $offset); 
    
        return new Response($this->twig->render('search/index.html.twig', [
            'resultSearch' => $resultSearch,
            'stringToSearch' => $stringToSearch,
        ]));

    }
}
