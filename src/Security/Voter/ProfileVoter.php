<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    
    protected function supports($attribute, $subject) :bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $profile, TokenInterface $token) :bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        
        // the logic of this voter is pretty simple: if the logged user is the same
        // who wants to do action, grant permission; otherwise, deny it.
        return $profile->getId() === $user->getId();
    }
}
