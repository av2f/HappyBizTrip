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
            $stringToSearch=$request->request->get('formSearch');
        }
        $offset = ($page-1) * $userRepo::PAGINATOR_PER_PAGE;
        $paginator = $userRepo->myFindbyCriteria(htmlspecialchars(trim($stringToSearch)), $offset);
         // List of users who are friends or blacklisted
         $listFriendAndBlackList = $userRepo->myFindListFriendAndBlackList($this->getUser()->getId(), "C", "B");
         // List of requesters which ask to be friend
         $listNewRequester = $userRepo->myFindListNewRequester($this->getUser()->getId(), "W");
         // List of users whom profile request to be friend
         $listNewRequested = $userRepo->myFindListNewRequested($this->getUser()->getId(), "W");
 
    
        return new Response($this->twig->render('search/index.html.twig', [
            'paginator' => $paginator,
            'friends_blackList' => $listFriendAndBlackList,
            'new_requester' => $listNewRequester,
            'new_requested' => $listNewRequested,
            'string_to_search' => $stringToSearch,
            'nb_page' => ceil(count($paginator) / $userRepo::PAGINATOR_PER_PAGE),
            'page' => $page,
            'user_id' => $this->getUser()->getId()
        ]));

    }
}
