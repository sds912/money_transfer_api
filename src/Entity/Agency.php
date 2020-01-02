<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgencyRepository")
 */
class Agency
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
    private $code;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

  
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $agency_admin;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $agency_cashier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="owner_agencies")
     */
    private $agency_owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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

  
    public function getAgencyAdmin(): ?User
    {
        return $this->agency_admin;
    }

    public function setAgencyAdmin(?User $agency_admin): self
    {
        $this->agency_admin = $agency_admin;

        return $this;
    }

    public function getAgencyCashier(): ?User
    {
        return $this->agency_cashier;
    }

    public function setAgencyCashier(?User $agency_cashier): self
    {
        $this->agency_cashier = $agency_cashier;

        return $this;
    }

    public function getAgencyOwner(): ?User
    {
        return $this->agency_owner;
    }

    public function setAgencyOwner(?User $agency_owner): self
    {
        $this->agency_owner = $agency_owner;

        return $this;
    }

    public function __toString()
    {
        return $this->city;
    }
}
