<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AgencyRepository")
 * 
 * @UniqueEntity(fields={"code"})
 * @UniqueEntity(fields={"owner"})
 */
class Agency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *  @Groups({"admin.write","admin.read"})
     */
    private $id;
    

    /**
     * @ORM\Column(type="integer")
     * @Groups({"admin.write", "admin.read"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *   min = 5,
     *   max = 5
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"admin.write","admin.read"})
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"admin.write","admin.read"})
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin.write","admin.read"})
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"admin.write","admin.read"})
     * @Assert\NotBlank()
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"admin.write","admin.read"})
     * @Assert\NotBlank()
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="owner_agencies")
     * @Groups({"admin.write","admin.read"})
     * @Assert\Valid()
     * @ApiSubresource(maxDepth=1)
     * @Assert\NotBlank()
     */
    private $owner;

    
  
    

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

  
}
