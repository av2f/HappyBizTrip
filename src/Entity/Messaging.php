<?php

namespace App\Entity;

use App\Repository\MessagingRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="messaging")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass=MessagingRepository::class)
 */

class Messaging
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="senders")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id", nullable=false)
     */
    private $sender;
    
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="receivers")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id", nullable=false)
     */
    private $receiver;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $message;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $readedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isReaded;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deletedBySender;

     /**
     * @ORM\Column(type="boolean")
     */
    private $deletedByReceiver;
    

    public function getSender(): ?user
    {
        return $this->sender;
    }

    public function setSender(?user $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?user
    {
        return $this->receiver;
    }

    public function setReceiver(?user $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReadedAt(): ?\DateTimeInterface
    {
        return $this->readedAt;
    }

    public function setReadedAt(\DateTimeInterface $readedAt): self
    {
        $this->readedAt = $readedAt;

        return $this;
    }

    public function getIsReaded(): ?bool
    {
        return $this->isReaded;
    }

    public function setIsReaded(bool $isReaded): self
    {
        $this->isReaded = $isReaded;

        return $this;
    }

    public function getDeletedBySender(): ?bool
    {
        return $this->deletedBySender;
    }

    public function setDeletedBySender(bool $deletedBySender): self
    {
        $this->deletedBySender = $deletedBySender;

        return $this;
    }

    public function getDeletedByReceiver(): ?bool
    {
        return $this->deletedByReceiver;
    }

    public function setDeletedByReceiver(bool $deletedByReceiver): self
    {
        $this->deletedByReceiver = $deletedByReceiver;

        return $this;
    }

    // lifecycleCallbacks functions
    /**
     * Setup boolean values (isReaded, deletedBySender, deletedByReceiver)
     * and date of CreatedAt
     * 
     * @ORM\PrePersist
     *
     * @return void
     */
    public function setInitialMessaging() {
        $this->createdAt = new \DateTime();
        
        // set isReaded to false
        $this->setIsReaded(false);
        // set deletedBySender to false
        $this->setDeletedBySender(false);
        // set deletedByReceiver to false
        $this->setDeletedByReceiver(false);
    }

    /**
     * Update the date of ReadedAt and set isReaded to true in preUpdate
     * 
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function setUpdatedMessaging() {
        $this->readedAt = new \DateTime();
        $this->setIsReaded(true);
    }
}