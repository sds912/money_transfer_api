<?php

namespace App\Entity;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DepositRepository")
 */
class Deposit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"deposit.read"})
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="creator_deposits")
     * @Groups({"account.write","deposit.write","deposit.read","partner.write"})
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PartnerAccount", inversedBy="deposits", cascade={"persist"})
     * @Groups({"deposit.write","deposit.read","account.write"})
     */
    private $account;

    /**
     * @ORM\Column(type="bigint")
     * @Groups({"deposit.write","deposit.read","account.write","partner.write"})
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"deposit.write","deposit.read","account.write","partner.write"})
     */
    private $createdAt;

    

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAccount(): ?PartnerAccount
    {
        return $this->account;
    }

    public function setAccount(?PartnerAccount $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function __toString()
    {
        return 'depot '.$this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

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

    
}
