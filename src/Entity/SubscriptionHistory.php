<?php

namespace App\Entity;

use App\Repository\SubscriptionHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionHistoryRepository::class)
 */
class SubscriptionHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     */
    private $subscriber;

    /**
     * @ORM\ManyToOne(targetEntity=SubscripType::class, inversedBy="subscriptionHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subscriberType;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $subscribPayAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $subscribBeginAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $subscribEndAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriber(): ?user
    {
        return $this->subscriber;
    }

    public function setSubscriber(?user $subscriber): self
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    public function getSubscriberType(): ?subscripType
    {
        return $this->subscriberType;
    }

    public function setSubscriberType(?subscripType $subscriberType): self
    {
        $this->subscriberType = $subscriberType;

        return $this;
    }

    public function getSubscribPayAt(): ?\DateTimeInterface
    {
        return $this->subscribPayAt;
    }

    public function setSubscribPayAt(?\DateTimeInterface $subscibPayAt): self
    {
        $this->subscribPayAt = $subscibPayAt;

        return $this;
    }

    public function getSubscribBeginAt(): ?\DateTimeInterface
    {
        return $this->subscribBeginAt;
    }

    public function setSubscribBeginAt(\DateTimeInterface $subscribBeginAt): self
    {
        $this->subscribBeginAt = $subscribBeginAt;

        return $this;
    }

    public function getSubscribEndAt(): ?\DateTimeInterface
    {
        return $this->subscribEndAt;
    }

    public function setSubscribEndAt(\DateTimeInterface $subscribEndAt): self
    {
        $this->subscribEndAt = $subscribEndAt;

        return $this;
    }
}
