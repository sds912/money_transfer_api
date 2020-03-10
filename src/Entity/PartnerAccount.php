<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PartnerAccountRepository")
 */
class PartnerAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"account.read","partner.read"})
     */
    private $id;
    /**
     * @ORM\Column(type="bigint")
     * @Groups({"deposit.write","account.read","partner.read","partner.write"})
     */
    private $balance = 0;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"account.write", "account.read","partner.read","partner.write","user.read"})
     */
    private $createdAt;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deposit", mappedBy="account", cascade={"persist","remove"})
     * @Groups({"account.write", "account.read","partner.write"})
     */
    private $deposits;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="creatorAccounts")
     * @Groups({"account.write", "account.read","partner.write"})
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partner", inversedBy="accounts")
     * @Groups({"account.write", "account.read","user.read"})
     */
    private $owner;

    /**
     * @ORM\Column(type="bigint")
     * @Groups({"transact.read","account.write", "deposit.write", "deposit.read","account.read", "account.write","partner.read","partner.write","user.read"})
     */
    private $accountNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="account")
     * @Groups({"partner.read"})
     */
    private $affectations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partnerAccount")
     * @Groups({"account.read"})
     */
    private $employees;

   
  

    public function __construct()
    {
        
        $this->deposits = new ArrayCollection();
        $this->setCreatedAt(new \DateTime('now'));
        $this->affectations = new ArrayCollection();
        $this->employees = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Deposit[]
     */
    public function getDeposits(): Collection
    {
        return $this->deposits;
    }

    public function addDeposit(Deposit $deposit): self
    {
        if (!$this->deposits->contains($deposit)) {
            $this->deposits[] = $deposit;
            $deposit->setAccount($this);
        }

        return $this;
    }

    public function removeDeposit(Deposit $deposit): self
    {
        if ($this->deposits->contains($deposit)) {
            $this->deposits->removeElement($deposit);
            // set the owning side to null (unless already changed)
            if ($deposit->getAccount() === $this) {
                $deposit->setAccount(null);
            }
        }

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function __toString()
    {
        return 'compte '.$this->accountNumber;
    }

    public function getOwner(): ?Partner
    {
        return $this->owner;
    }

    public function setOwner(?Partner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setAccount($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getAccount() === $this) {
                $affectation->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(User $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setPartnerAccount($this);
        }

        return $this;
    }

    public function removeEmployee(User $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getPartnerAccount() === $this) {
                $employee->setPartnerAccount(null);
            }
        }

        return $this;
    }



    
}
