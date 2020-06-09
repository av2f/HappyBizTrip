<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscriptions")
     */
    private $subscriber;

    /**
     * @ORM\ManyToOne(targetEntity=SubscripType::class, inversedBy="subscriptions")
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

    public function setSubscribPayAt(?\DateTimeInterface $subscribPayAt): self
    {
        $this->subscribPayAt = $subscribPayAt;

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

    /**
     * Calculate number of days remaining before the end of subscription
     * Author : F. Parmentier
     * Created At : 2019/08/24
     *
     * @return integer|null
     */
    public function getDaysEndOfSubscription(): ?int
    {
        $dateNow = new \Datetime();
        return intval($dateNow->diff($this->subscribEndAt)->format("%a"));
    }
}
