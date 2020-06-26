<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UniqueEntity (
 *  fields = {"pseudo"},
 *  message = "user.pseudo.unique"
 * )
 * 
 * @UniqueEntity (
 *  fields = {"email"},
 *  message = "user.email.unique"
 * )
 * 
 * @UniqueEntity (
 *  fields = {"slug"}
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * 
     * @Assert\NotBlank(
     *  message = "user.pseudo.not_blank"
     * )
     * 
     * @Assert\Length(
     *  min=5,
     *  minMessage="user.pseudo.length"
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank(
     *  message = "user.email.not_blank"
     * )
     * @Assert\Email(
     *  message = "user.email.invalid"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *  message="user.password.not_blank"
     * )
     * @Assert\Length(
     *  min=8,
     *  minMessage="user.password.length"
     * )
     * 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     * 
     * @Assert\NotBlank(
     *  message="user.birthDate.not_null"
     * )
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $situation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $profession;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSubscribed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="smallint")
     */
    private $completed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\ManyToMany(targetEntity=Interest::class, inversedBy="users")
     */
    private $interests;

    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="subscriber")
     */
    private $subscriptions;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $phoneNumber;

     /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @ORM\JoinTable(name="visits",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="visitor_id", referencedColumnName="id")}
     * )
     */
    private $visitors; 

    public function __construct()
    {
        $this->interests = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->visitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation(?string $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsSubscribed(): ?bool
    {
        return $this->isSubscribed;
    }

    public function setIsSubscribed(bool $isSubscribed): self
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCompleted(): ?int
    {
        return $this->completed;
    }

    public function setCompleted(int $completed): self
    {
        $this->completed = $completed;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // for implementation of UserInterface
    public function getUsername()
    {
        return $this->email;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        // $roles = $this->roles;
        // $roles[] = 'ROLE_USER';
        return array('ROLE_USER');
    }

    public function eraseCredentials() {}
    //

    // lifecycleCallbacks functions
    /**
     * Setup default avatar image if not defined in prePersist
     * and creation date
     * 
     * @ORM\PrePersist
     *
     * @return void
     */
    public function setInitialUser() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        
        // set isActive to true
        $this->setIsActive(true);
        // set isSubscribed to false
        $this->setIsSubscribed(false);
        // set isDeleted to false
        $this->setIsDeleted(false);
        // set % completed of profile
        $this->setCompleted(25);
    }

    /**
     * Generate the date of last modified date of profile in preUpdate
     * 
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function setUpdatedAtDate() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Calculate the percentage of completed
     * 
     * @ORM\PreUpdate
     * 
     */
    public function computeCompleted() {
        $TOTAL_USER_OBJECT = 14;
        // by default, 3 objects fullfiled {pseudo/email/Date of Birth}
        // object password not taken into account
        $userObjectCompleted=3;
        $this->getGender() != "" ? $userObjectCompleted++ : "";
        $this->getFirstName() != "" ? $userObjectCompleted++ : "" ;
        $this->getLastName() != "" ? $userObjectCompleted++ : "" ;
        $this->getFirstName() != "" ? $userObjectCompleted++ : "" ;
        $this->getSituation() != "" ? $userObjectCompleted++ : "" ;
        $this->getAvatar() != "" ? $userObjectCompleted++ : "" ;
        $this->getProfession() != "" ? $userObjectCompleted++ : "" ;
        $this->getCompany() != "" ? $userObjectCompleted++ : "" ;
        $this->getDescription() != "" ? $userObjectCompleted++ : "" ;
        $this->getPhoneNumber() != "" ? $userObjectCompleted++ : "" ;
        count($this->getInterests()) != 0 ? $userObjectCompleted++ : "";
        return $this->completed = round(($userObjectCompleted*100)/$TOTAL_USER_OBJECT);
    }

    /**
     * Caclculate age with date of birth
     * Author : Frederic Parmentier
     * Created at : 2019/08/07
     *
     * @return void
     */
    public function getCalculateAge() {
        $today = new \DateTime('now');
        $age = $today->diff($this->getBirthDate());
        return $age->format('%y');
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /* 
    * generation of slug
    * the listener UserEntityListener.php (declare in services.yaml)
    * for slug generation, user package 'string'
    * 
    * Created date : 05/19/2020
    */    
    public function computeSlug(SluggerInterface $slugger) {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = $slugger->slug($this->getPseudo())->lower();
        }
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection|interest[]
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(interest $interest): self
    {
        if (!$this->interests->contains($interest)) {
            $this->interests[] = $interest;
        }

        return $this;
    }

    public function removeInterest(interest $interest): self
    {
        if ($this->interests->contains($interest)) {
            $this->interests->removeElement($interest);
        }

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setSubscriber($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getSubscriber() === $this) {
                $subscription->setSubscriber(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

     /**
     * @return Collection|User[]
     */
    public function getVisitors(): Collection
    {
        return $this->visitors;
    }

    public function addVisitor(User $visitor): self
    {
        if (!$this->visitors->contains($visitor)) {
            $this->visitors[] = $visitor;
            $visitor->addVisitor($this);
        }

        return $this;
    }

    public function removeVisitor(User $visitor): self
    {
        if ($this->visitors->contains($visitor)) {
            $this->visitors->removeElement($visitor);
            $visitor->removeVisitor($this);
        }

        return $this;
    }
}
