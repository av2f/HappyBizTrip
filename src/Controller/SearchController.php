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
     * @Route("/search/{stringToSearch?""}/{page<\d+>?1}", name="search", methods={"POST", "GET"})
     */
    public function index(Request $request, UserRepository $userRepo, int $page, string $stringToSearch)
    {
        if (isset($_POST['formSearch'])) {
            $stringToSearch=htmlspecialchars(trim($request->request->get('formSearch')));
        }
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $paginator = $userRepo->findbyCriteria($stringToSearch, $offset);
    
        return new Response($this->twig->render('search/index.html.twig', [
            'paginator' => $paginator,
            'string_to_search' => $stringToSearch,
            'nb_page' => ceil(count($paginator) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page
        ]));

    }
}
