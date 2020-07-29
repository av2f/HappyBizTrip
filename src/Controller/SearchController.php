<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\UserRepository;
use App\Repository\ConnectRepository;
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
    public function index(Request $request, UserRepository $userRepo, ConnectRepository $connectRepo, int $page, string $stringToSearch)
    {
        if (isset($_POST['formSearch'])) {
            $stringToSearch=$request->request->get('formSearch');
        }
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $paginator = $userRepo->myFindbyCriteria(htmlspecialchars(trim($stringToSearch)), $offset);
        /*
        *   friends = List of users who are friends
        *   new_requester = List of requesters which ask to be friend
        *   new_requested = List of users whom profile request to be friend
        *   has_blacklisted = List of users who has been blacklisted
        *   backlisted = list of users who has blacklisted the user
        */
        return new Response($this->twig->render('search/index.html.twig', [
            'paginator' => $paginator,
            'friends' => $userRepo->myFindListFriends($this->getUser()->getId(), "C"),
            'new_requester' => $userRepo->myFindListNewRequester($this->getUser()->getId(), "W"),
            'new_requested' => $userRepo->myFindListNewRequested($this->getUser()->getId(), "W"),
            'has_blacklisted' => $connectRepo->findBy(['requester'=>$this->getUser()->getId(), 'state'=>'B']),
            'blacklisted' => $connectRepo->findBy(['requested'=>$this->getUser()->getId(), 'state'=>'B']),
            'string_to_search' => $stringToSearch,
            'nb_page' => ceil(count($paginator) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page,
            'user_id' => $this->getUser()->getId()
        ]));

    }
}
