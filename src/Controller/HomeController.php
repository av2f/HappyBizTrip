<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\HomeRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\LoginFormAuthenticator;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage", methods={"POST"})
     * 
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager,
        LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler)
    {
        $locale = $request->getLocale();
        $user = new User;
        
        // create form
        $form = $this->createForm(HomeRegisterType::class, $user);
        
        // handle the submit
        $form->handleRequest($request);
        
        if ($form -> isSubmitted() && $form -> isValid()){
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

        return $this->render('home/index.html.twig', [
            'locale' => $locale,
            'form' => $form->createView()
        ]);
    }
}
