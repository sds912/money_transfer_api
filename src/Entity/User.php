<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"phone"})
 */
class User implements UserInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({
     *  "user.read",
     *  "user.write",
     * "partner.read",
     * "affect.read"
     * 
     * })
     */
    private $id;


    /**
     * @var string The hashed password
     * @Groups({
     *  "user.write",
     * "partner.write"
     * 
     * }) 
     * @ORM\Column(type="string", nullable=true)
     * 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "user.write",
     *  "user.read",
     *   "partner.read",
     *   "partner.write",
     *   "account.read",
     *    "affect.write",
     *    "affect.read",
     *    "transact.read"
     * })
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "user.write",
     *  "user.read",
     *   "partner.read",
     * "partner.write",
     * "account.read",
     * "affect.write",
     * "affect.read",
     * "transact.read"
     * })
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "user.read",
     *  "user.write",
     *   "partner.read",
     *   "partner.write",
     *   "account.read",
     *   "affect.write",
     * "affect.read",
     * "transact.read"
     * 
     * })
     * @Assert\NotBlank()
     */
    private $fname;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "user.write",
     *  "user.read",
     *  "partner.read",
     *  "partner.write",
     * "account.read",
     *  "affect.write",
     * "transact.read"
     * })
     * @Assert\NotBlank()
     */
    private $lname;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     * @Groups({
     *  "user.write",
     *  "user.read",
     *   "block.update",
     *   "block.read",
     *   "partner.write",
     *   "partner.read",
     *   "account.read",
     *    "affect.write",
     *    "affect.read",
     *    "transact.read"
     * })
     * @Assert\Type("bool")
     */
    private $isActive = true;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="supervisorUsers")
     * @Groups({
     *  "user.write",
     *  "user.read",
     *  "affect.write"
     * })
     */
    private $supervisor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="supervisor")
     * @Groups({
     *  "user.write",
     * "partner.read"
     * 
     * })
     */
    private $supervisorUsers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles", inversedBy="users")
     * @Groups({
     *  "user.read",
     *  "user.write",
     * "affect.write",
     * "account.read"
     * })
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartnerAccount", mappedBy="creator", cascade={"persist"})
     * @Groups({
     *  "user.write",
     *   "partner.read"
     * })
     */
    private $creatorAccounts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Partner", mappedBy="partnerCreator")
     * @Groups({
     *  "user.write",
     *  "partner.write"
     * })
     */
    private $partners;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"affect.write"})
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PartnerAccount", inversedBy="employees", cascade={"persist"})
     * @Groups({"user.write","partner.read"})
     */
    private $partnerAccount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Affectation", mappedBy="employee", cascade={"persist", "remove"})
     * @Groups({"partner.read"})
     */
    private $affectation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="author")
     */
    private $transactions;

    
    public function __construct()
    {
        $this->supervisor = new ArrayCollection();
        $this->supervisorUsers = new ArrayCollection();
        $this->creatorAccounts = new ArrayCollection();
        $this->partners = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

     /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): Array
    {
       return [$this->userRoles->getRoleName()];

    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

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

    /**
     * @return Collection|self[]
     */
    public function getSupervisor(): Collection
    {
        return $this->supervisor;
    }

    public function addSupervisor(self $supervisor): self
    {
        if (!$this->supervisor->contains($supervisor)) {
            $this->supervisor[] = $supervisor;
        }

        return $this;
    }

    public function removeSupervisor(self $supervisor): self
    {
        if ($this->supervisor->contains($supervisor)) {
            $this->supervisor->removeElement($supervisor);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSupervisorUsers(): Collection
    {
        return $this->supervisorUsers;
    }

    public function addSupervisorUser(self $supervisorUser): self
    {
        if (!$this->supervisorUsers->contains($supervisorUser)) {
            $this->supervisorUsers[] = $supervisorUser;
            $supervisorUser->addSupervisor($this);
        }

        return $this;
    }

    public function removeSupervisorUser(self $supervisorUser): self
    {
        if ($this->supervisorUsers->contains($supervisorUser)) {
            $this->supervisorUsers->removeElement($supervisorUser);
            $supervisorUser->removeSupervisor($this);
        }

        return $this;
    }

    public function getUserRoles(): ?Roles
    {
        return $this->userRoles;
    }

    public function setUserRoles(?Roles $userRoles): self
    {
        $this->userRoles = $userRoles;

        return $this;
    }

   
    public function __toString()
    {
        return $this->fname;
    }

    /**
     * @return Collection|PartnerAccount[]
     */
    public function getCreatorAccounts(): Collection
    {
        return $this->creatorAccounts;
    }

    public function addCreatorAccount(PartnerAccount $creatorAccount): self
    {
        if (!$this->creatorAccounts->contains($creatorAccount)) {
            $this->creatorAccounts[] = $creatorAccount;
            $creatorAccount->setCreator($this);
        }

        return $this;
    }

    public function removeCreatorAccount(PartnerAccount $creatorAccount): self
    {
        if ($this->creatorAccounts->contains($creatorAccount)) {
            $this->creatorAccounts->removeElement($creatorAccount);
            // set the owning side to null (unless already changed)
            if ($creatorAccount->getCreator() === $this) {
                $creatorAccount->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Partner[]
     */
    public function getPartners(): Collection
    {
        return $this->partners;
    }

    public function addPartner(Partner $partner): self
    {
        if (!$this->partners->contains($partner)) {
            $this->partners[] = $partner;
            $partner->setPartnerCreator($this);
        }

        return $this;
    }

    public function removePartner(Partner $partner): self
    {
        if ($this->partners->contains($partner)) {
            $this->partners->removeElement($partner);
            // set the owning side to null (unless already changed)
            if ($partner->getPartnerCreator() === $this) {
                $partner->setPartnerCreator(null);
            }
        }

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPartnerAccount(): ?PartnerAccount
    {
        return $this->partnerAccount;
    }

    public function setPartnerAccount(?PartnerAccount $partnerAccount): self
    {
        $this->partnerAccount = $partnerAccount;

        return $this;
    }

    public function getAffectation(): ?Affectation
    {
        return $this->affectation;
    }

    public function setAffectation(?Affectation $affectation): self
    {
        $this->affectation = $affectation;

        // set (or unset) the owning side of the relation if necessary
        $newEmployee = null === $affectation ? null : $this;
        if ($affectation->getEmployee() !== $newEmployee) {
            $affectation->setEmployee($newEmployee);
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setAuthor($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getAuthor() === $this) {
                $transaction->setAuthor(null);
            }
        }

        return $this;
    }

    
   
 
}
