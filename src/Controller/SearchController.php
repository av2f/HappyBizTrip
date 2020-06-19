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
     * @Route("/search", name="search", methods={"POST", "GET"})
     */
    public function index(Request $request, UserRepository $userRepo)
    {
        if (isset($_POST['formSearch'])) {
            $stringToSearch=htmlspecialchars(trim($request->request->get('formSearch')));
        }
        else {
            $stringToSearch = $request->query->get('stringToSearch');
        }
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $userRepo->findbyCriteria($stringToSearch, $offset);
    
        return new Response($this->twig->render('search/index.html.twig', [
            'paginator' => $paginator,
            'stringToSearch' => $stringToSearch,
            'nbPage' => ceil(count($paginator) / $userRepo::PAGINATOR_PER_PAGE)
        ]));

    }
}
