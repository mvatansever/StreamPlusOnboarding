<?php
// src/Request/AddressRequest.php
namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class AddressRequest extends OnboardProcessStepRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    private ?string $addressLine1;

    #[Assert\Length(max: 255)]
    private ?string $addressLine2;

    #[Assert\NotBlank]
    private ?string $city;

    #[Assert\NotBlank]
    private ?string $postalCode;

    #[Assert\NotBlank]
    private ?string $stateProvince;

    #[Assert\NotBlank]
    private ?string $country;

    // Getters and Setters
    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(?string $addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(?string $addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getStateProvince(): ?string
    {
        return $this->stateProvince;
    }

    public function setStateProvince(?string $stateProvince): void
    {
        $this->stateProvince = $stateProvince;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }
}
