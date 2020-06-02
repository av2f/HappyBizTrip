<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Service\ImageOptimizer;
use App\Repository\InterestRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterestTypeRepository;
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
    public function editProfile(Request $request, User $profile, EntityManagerInterface $em, InterestRepository $qInterests, InterestTypeRepository $qInterestsType)
    {
        // Authorization managed by voter
        $this->denyAccessUnlessGranted('edit', $profile);
        
        // for interest part
        $interestsType = $qInterestsType->findInterestTypeOrder();
        $interestsName = $qInterests->findInterestOrder();
        
        // create form
        $form = $this->createForm(ProfileType::class, $profile);
        
        // handle the submit
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash(
                'success',
                'update.profile.successfull'
            );

            return $this->redirectToRoute('edit_profile', ['slug' => $profile->getSlug()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $profile,
            'interestsType' => $interestsType,
            'interestsName' => $interestsName
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
    
    public function deleteProfile(Request $request, User $profile, EntityManagerInterface $em)
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('edit_profile', ['slug' => $profile->getSlug()]);
        }
        
        // set isDeleted to yes and disconnect the user.
        $profile->setIsDeleted(true);
        $em->flush();

        return $this->redirectToRoute('hbt_logout');
    }

    /**
     * Change the image of avatar
     * store the new in $avatarDir
     * Update in database
     * remove the old in $avatarDir (if exist)
     * 
     * @Route("/profile/{id}/avatar/update", name="update_avatar", methods={"POST"})
     * 
     * @IsGranted("edit", subject="profile")
     *
     * @param Request $request
     * @param User $profile
     * @return void
     */
    public function updateAvatar(User $profile, string $avatarDir, EntityManagerInterface $em,  ImageOptimizer $imageOptimizer)
    {
        $MAX_FILE_SIZE = 3145728; // 3Mo Octets max
        
        if (isset($_POST['token'])) {
            if ($this->isCsrfTokenValid('update-avatar', $_POST['token'])) {
                $oldAvatarFile = $profile->getAvatar();
                if ($_POST['action'] === 'update') {
                    // update avatar image
                    if (!empty($_FILES['image'])) {
                        if ($_FILES['image']['size'] > $MAX_FILE_SIZE) { // image exceeds size
                            return new JsonResponse([
                                'error' => '4'], 400);
                        }
                        if ($_FILES['image']['error'] === 0) {  // UPLOAD_ERR_OK
                            $fileDest = md5(uniqid('')) . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                            // verifify if files already exists. If yes, rename it
                            if (file_exists($avatarDir.$fileDest)) { $fileDest = md5(uniqid('', true)) . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);}
                            // Move file
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $avatarDir.$fileDest)) {
                                // Optimize image
                                $imageOptimizer->resize($avatarDir.$fileDest, 600, 600);
                                // store new avatar in database
                                $profile->setAvatar($fileDest);
                                $em->flush();
                                 // delete old avatar
                                if (!empty($oldAvatarFile) && file_exists($avatarDir.$oldAvatarFile)) unlink($avatarDir.$oldAvatarFile);
                                // return success response
                                return new JsonResponse([
                                    'success' => '1'], 200);
                            }
                        }
                        elseif ($_FILES['image']['error'] === 1) {  // UPLOAD_ERR_INI_SIZE : Size of file exceeds upload_max_filesize
                            return new JsonResponse([
                                'error' => '4'], 400);
                        }
                        else {
                            // return error
                            return new JsonResponse([
                                'error' => '2'], 400);
                        }
                    }
                    else {
                        // return error
                        return new JsonResponse([
                            'error' => '2'], 400);
                    }
                }
                elseif ($_POST['action'] === 'delete') {
                    // delete avatar from database
                    $profile->setAvatar('');
                    $em->flush();
                    // delete old avatar
                    if (!empty($oldAvatarFile) && file_exists($avatarDir.$oldAvatarFile)) unlink($avatarDir.$oldAvatarFile);
                    // return success response
                    return new JsonResponse([
                        'success' => '1'], 200);
                }
                else {
                     // return error
                     return new JsonResponse([
                        'error' => '2'], 400);
                }
            }
            else {
                return new JsonResponse([
                    'error' => '3'], 400);
            }
        }
        else {
            return new JsonResponse([
                'Error' => '3'], 400);
        }
        
    }
}
