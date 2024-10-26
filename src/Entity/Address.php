<?php

// src/Entity/Address.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $addressLine1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $addressLine2;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private $city;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank]
    private $postalCode;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private $stateProvince;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private $country;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    // Getters and setters for each property...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function setAddressLine2($addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city): void
    {
        $this->city = $city;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getStateProvince()
    {
        return $this->stateProvince;
    }

    public function setStateProvince($stateProvince): void
    {
        $this->stateProvince = $stateProvince;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user->getId();
    }
}
