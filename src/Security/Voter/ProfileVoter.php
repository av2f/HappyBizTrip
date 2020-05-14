<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['PROFILE_EDIT', 'PROFILE_VIEW'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $profile, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'PROFILE_EDIT':
                return $profile->getId() === $user->getId();
                break;
            case 'PROFILE_VIEW':
                return $profile->getId() === $user->getId();
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
