<?php

namespace App\Entity;

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
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $account_number;

    /**
     * @ORM\Column(type="integer")
     */
    private $balance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="partnerAccounts")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Deposit", mappedBy="account")
     */
    private $deposits;

    public function __construct()
    {
        $this->deposits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountNumber(): ?int
    {
        return $this->account_number;
    }

    public function setAccountNumber(int $account_number): self
    {
        $this->account_number = $account_number;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
}
