<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VisitRepository::class)
 */
class Visit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="visits")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $visitor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVisitor(): ?User
    {
        return $this->visitor;
    }

    public function setVisitor(?User $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
    }
}
