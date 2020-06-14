<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "user.change.password_wrong_value"
     * )
     */
    protected $oldPassword;

    /**
     * @Assert\NotBlank(
     *  message = "user.password.not_blank"
     * )
     * 
     * @Assert\Length(
     *  min=8,
     *  minMessage = "user.password.length"
     * )
     * 
     */
    protected $newPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
