<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AffectationRepository")
 */
class Affectation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"affect.read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({
     * "affect.read",
     * "affect.write",
     * "partner.read"})
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({
     * "affect.read",
     * "affect.write",
     * "partner.read"})
     */
    private $endAt;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PartnerAccount", inversedBy="affectations")
     * @Groups({"affect.read", "affect.write"})
     */
    private $account;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"partner.read", "affect.read", "affect.write"})
     */
    private $addedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="affectation", cascade={"persist", "remove"})
     * @Groups({"affect.read", "affect.write"})
     */
    private $employee;


    public function __construct()
    {
        $this->addedAt = new DateTime('now');
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

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
        return "affectation ".$this->id;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(?\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
