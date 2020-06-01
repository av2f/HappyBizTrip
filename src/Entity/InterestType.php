<?php

namespace App\Entity;

use App\Repository\InterestTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterestTypeRepository::class)
 */
class InterestType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nameType;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $iconType;

    /**
     * @ORM\OneToMany(targetEntity=Interest::class, mappedBy="interestType")
     */
    private $interests;

    public function __construct()
    {
        $this->interests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameType(): ?string
    {
        return $this->nameType;
    }

    public function setNameType(string $nameType): self
    {
        $this->nameType = $nameType;

        return $this;
    }

    public function getIconType(): ?string
    {
        return $this->iconType;
    }

    public function setIconType(string $iconType): self
    {
        $this->iconType = $iconType;

        return $this;
    }

    /**
     * @return Collection|Interest[]
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(Interest $interest): self
    {
        if (!$this->interests->contains($interest)) {
            $this->interests[] = $interest;
            $interest->setInterestType($this);
        }

        return $this;
    }

    public function removeInterest(Interest $interest): self
    {
        if ($this->interests->contains($interest)) {
            $this->interests->removeElement($interest);
            // set the owning side to null (unless already changed)
            if ($interest->getInterestType() === $this) {
                $interest->setInterestType(null);
            }
        }

        return $this;
    }
}
