<?php

namespace App\Entity;

use App\Repository\ConnnectRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_connect")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=ConnectRepository::class)
 */

class Connect
{
     /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="requesters")
     * @ORM\JoinColumn(name="requester_id", referencedColumnName="id", nullable=false)
     */
    private $requester;
    
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="requests")
     * @ORM\JoinColumn(name="requested_id", referencedColumnName="id", nullable=false)
     */
    private $requested;

    /**
     * @ORM\Column(type="datetime")
     */
    private $actionAt;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $state;

    public function getRequester(): ?user
    {
        return $this->requester;
    }

    public function setRequester(?user $requester): self
    {
        $this->requester = $requester;

        return $this;
    }

    public function getRequested(): ?user
    {
        return $this->requested;
    }

    public function setRequested(?user $requested): self
    {
        $this->requested = $requested;

        return $this;
    }

    public function getActionAt(): ?\DateTimeInterface
    {
        return $this->actionAt;
    }

    public function setActionAt(\DateTimeInterface $actionAt): self
    {
        $this->actionAt = $actionAt;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

}