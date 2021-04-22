<?php
/**
 * HomeController.php
 * Manage the home page
 * 
 * @category Controller
 * 
 * @author Frederic Parmentier <fparmentier@free.fr>
 * 
 */

namespace App\Controller;

use App\Entity\User;
use Twig\Environment;
use App\Form\HomeRegisterType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    /**
     * @Route("/", name="homepage", methods={"POST"})
     * 
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager,
        LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler)
    {   
        $user = new User;
        
        // create form
        $form = $this->createForm(HomeRegisterType::class, $user);
        
        // handle the submit
        $form->handleRequest($request);
        
        if ($form -> isSubmitted() && $form -> isValid()) {
            // encode password
            $password=$passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // store the new user
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'registration.message_successfull'
            );

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator, // authenticator whose onAuthenticationSuccess you want to use
                'main'          // name firewall in security.yaml
            );
        }
        
        // if already logged, redirect directly to main page
        if ($this->getUser()) {
            return $this->redirectToRoute('hbt_main');
        }

        return new Response($this->twig->render('home/index.html.twig', [
            'locale' => $request->getLocale(),
            'form' => $form->createView()
        ]));
    }
}
