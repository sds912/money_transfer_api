<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PartnerRepository")
 */
class Partner
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $rc;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="partners")
     * @Groups({"partner.read","partner.write"})
     */
    private $partnerCreator;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @Groups({"partner.read","partner.write","account.read"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PartnerAccount", mappedBy="owner",cascade={"remove","persist"})
     * @Groups({"partner.read", "partner.write"})
     */
    private $accounts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agencyName;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->setCreatedAt( new DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRc(): ?string
    {
        return $this->rc;
    }

    public function setRc(string $rc): self
    {
        $this->rc = $rc;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getCreatedAt(): ? DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPartnerCreator(): ?User
    {
        return $this->partnerCreator;
    }

    public function setPartnerCreator(?User $partnerCreator): self
    {
        $this->partnerCreator = $partnerCreator;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|PartnerAccount[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(PartnerAccount $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setOwner($this);
        }

        return $this;
    }

    public function removeAccount(PartnerAccount $account): self
    {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
            // set the owning side to null (unless already changed)
            if ($account->getOwner() === $this) {
                $account->setOwner(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->ninea;
    }

    public function getAgencyName(): ?string
    {
        return $this->agencyName;
    }

    public function setAgencyName(string $agencyName): self
    {
        $this->agencyName = $agencyName;

        return $this;
    }
}
