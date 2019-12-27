<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 *  attributes= {"security" = "is_granted('ROLE_ADMIN')"},
 *  collectionOperations={
 *    "get",
 *    "post"={
 *     "security_post_denormalize"="is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')"
 *      }
 * },
 *  itemOperations = {
 *   "get",
 *   "put" = {
 *    "access_control"="is_granted('ROLE_SUPER_ADMIN')"
 *  }
 * },
 *   normalizationContext={"groups"={"read"}},
 *   denormalizationContext={"groups"={"write"}}
 * )
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"phone"})
 * 
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
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"write"})
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/+[0-9]*$")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $fname;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $lname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read", "write"})
     * @Assert\Type("bool")
     */
    private $active = true;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $city;

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
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
}
