<?php

namespace App\Entity;

use App\Repository\SubcripTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubcripTypeRepository::class)
 */
class SubcripType
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
    private $type;

    /**
     * @ORM\Column(type="smallint")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $durationType;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDurationType(): ?string
    {
        return $this->durationType;
    }

    public function setDurationType(string $durationType): self
    {
        $this->durationType = $durationType;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
