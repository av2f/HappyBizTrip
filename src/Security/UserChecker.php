<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
        if ($user->getIsDeleted()) {
            throw new CustomUserMessageAuthenticationException("Your account is being deleted and is no longer accessible");
        }

        // user is inactive
        if (!$user->getIsActive()) {
            throw new CustomUserMessageAuthenticationException('Your account has been deactivated and is no longer accessible');
        }

    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }
}