<?php

namespace App\Entity;

use App\Repository\InterestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterestRepository::class)
 */
class Interest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $raw;

    /**
     * @ORM\ManyToOne(targetEntity=interestType::class, inversedBy="interests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $interestType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRaw(): ?int
    {
        return $this->raw;
    }

    public function setRaw(int $raw): self
    {
        $this->raw = $raw;

        return $this;
    }

    public function getInterestType(): ?interestType
    {
        return $this->interestType;
    }

    public function setInterestType(?interestType $interestType): self
    {
        $this->interestType = $interestType;

        return $this;
    }
}
