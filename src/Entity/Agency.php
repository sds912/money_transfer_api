<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AgencyRepository")
 * @UniqueEntity(fields={"code"})
 */
class Agency
{
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *  @Groups({"agency.write", "agency.read"})
     */
    private $id;
    

    /**
     * @ORM\Column(type="integer")
     * @Groups({"agency.write"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *   min = 5,
     *   max = 5
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"agency.write"})
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"agency.write"})
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agency.write"})
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"agency.write"})
     * @Assert\NotBlank()
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"agency.write"})
     * @Assert\NotBlank()
     */
    private $isActive = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="admin_agencies")
     * @Groups({"agency.write","agency.read"})
     */
    private $agency_admin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="owner_agencies")
     * @Groups({"agency.write","agency.read"})
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="cashier_agency")
     * @Groups({"agency.write","agency.read"})
     */
    private $cashiers;

    

   



    public function __construct()
    {
        $this->cashiers = new ArrayCollection();
    }

    
  
    

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

   


    public function __toString(){
       return $this->address;
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
     * @return Collection|User[]
     */
    public function getCashiers(): Collection
    {
        return $this->cashiers;
    }

    public function addCashier(User $cashier): self
    {
        if (!$this->cashiers->contains($cashier)) {
            $this->cashiers[] = $cashier;
            $cashier->setCashierAgency($this);
        }

        return $this;
    }

    public function removeCashier(User $cashier): self
    {
        if ($this->cashiers->contains($cashier)) {
            $this->cashiers->removeElement($cashier);
            // set the owning side to null (unless already changed)
            if ($cashier->getCashierAgency() === $this) {
                $cashier->setCashierAgency(null);
            }
        }

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

   

    

  
}
