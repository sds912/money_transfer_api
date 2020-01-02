<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"phone"})
 * @ApiFilter(BooleanFilter::class, properties={"isActive"})
 * 
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({
     *  "write"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string The hashed password
     * @Groups({
     *  "write"
     * }) 
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "write"
     * })
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "write"
     * })
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "write"
     * })
     * @Assert\NotBlank()
     */
    private $fname;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "write"
     * })
     * @Assert\NotBlank()
     */
    private $lname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *  "write"
     * })
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(type="boolean", options={"default" : true})
     * @Groups({
     *  "write",
     *   "read"
     * })
     * @Assert\Type("bool")
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "write"
     * })
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({
     *  "write"
     * })
     * @Assert\NotBlank()
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="supervisorUsers", cascade={"persist"})
     * @Groups({"read"})
     * @ApiSubresource(maxDepth=1)
     */
    private $supervisor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="supervisor", cascade={"persist"})
     * @Groups({"read"})
     * @ApiSubresource(maxDepth=1)
     */
    private $supervisorUsers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles", inversedBy="users", cascade={"persist"})
     * @Groups({"read"})
     */
    private $userRoles;

   
    
   
    public function __construct()
    {
        $this->supervisor = new ArrayCollection();
        $this->supervisorUsers = new ArrayCollection();
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
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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
}
