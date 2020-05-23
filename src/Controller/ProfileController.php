<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{slug}", name="edit_profile", methods={"POST","GET"})
     * 
     * @isGranted("ROLE_USER")
     * 
     * @return Response
     * 
     */
    public function editProfile(Request $request, User $profile)
    {
        // Authorization managed by voter
        $this->denyAccessUnlessGranted('edit', $profile);
        
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

    /** 
     * 
     * User decided to delete his account
     * Author : Frederic Parmentier
     * Date : 05/20/2020
     * 
     * @Route("/profile/{id}/delete", name="delete_profile", methods={"POST"})
     * 
     * @IsGranted("delete", subject="profile")
     * 
     * @return Response
     * 
    */
    
    public function deleteProfile(Request $request, User $profile)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('edit_profile', ['slug' => $profile->getSlug()]);
        }
        
        // set isDeleted to yes and disconnect the user.
        $profile->setIsDeleted(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('hbt_logout');
    }

    /**
     * Change the image of avatar
     * store the new in $avatarDir
     * Update in database
     * remove the old in $avatarDir (if exist)
     * 
     * @Route("/profile/{id}/avatar/update", name="update_avatar", methods={"PATCH"})
     * 
     * @IsGranted("edit", subject="profile")
     *
     * @param Request $request
     * @param User $profile
     * @return void
     */
    public function updateAvatar(Request $request, User $profile, string $avatarDir)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('update-avatar', $data['token'])) {
            $newAvatar = $data['newAvatar'];
            //$fileImg = md5(uniqid()) . '.' . $newAvatar->guessExtension();
            return new JsonResponse([
                'success' => 1,
                'imgSrc' => $profile->getAvatar(),
                'img' => "oo"
            ], 200);
        }
        else {
            return new JsonResponse([
                'Error' => 'Invalid Token'], 400);
        }
        
    }
}
