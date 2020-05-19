<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{slug}", name="edit_profile")
     * 
     * @isGranted("ROLE_USER")
     * 
     * @return Response
     * 
     */
    public function editProfile(Request $request, User $profile)
    {
        // Authorization managed by voter
        $this->denyAccessUnlessGranted("PROFILE_EDIT", $profile);
        
        // create form
        $form = $this->createForm(ProfileType::class, $profile);
        
        // handle the submit
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'update.profile.successfull'
            );

            return $this->redirectToRoute('edit_profile', ['slug' => $profile->getSlug()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $profile
        ]);
    }
}
