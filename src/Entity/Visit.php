<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="visit")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=VisitRepository::class)
 */

class Visit
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="visits")
     * @ORM\JoinColumn(name="visited_id", referencedColumnName="id", nullable=false)
     */
    private $visited;

     /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="visitors")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id", nullable=false)
     */
    private $visitor;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isViewed;

    public function getVisited(): ?user
    {
        return $this->visited;
    }

    public function setVisited(?user $visited): self
    {
        $this->visited = $visited;

        return $this;
    }

    public function getVisitor(): ?user
    {
        return $this->visitor;
    }

    public function setVisitor(?user $visitor): self
    {
        $this->visitor = $visitor;

        return $this;
    }

    public function getIsViewed(): ?bool
    {
        return $this->isViewed;
    }

    public function setIsViewed(bool $isViewed): self
    {
        $this->isViewed = $isViewed;

        return $this;
    }

}