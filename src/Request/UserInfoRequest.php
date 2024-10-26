<?php
// src/Request/UserInfoRequest.php
namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UserInfoRequest extends OnboardProcessStepRequest
{
    #[Assert\NotBlank]
    private ?string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[Assert\Regex('/^\d{10,15}$/')]
    private ?string $phone;

    #[Assert\NotBlank]
    private ?string $subscriptionType;

    // Getters and Setters
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getSubscriptionType(): ?string
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(?string $subscriptionType): void
    {
        $this->subscriptionType = $subscriptionType;
    }
}
