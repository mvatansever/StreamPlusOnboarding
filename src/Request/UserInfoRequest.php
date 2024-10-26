<?php

namespace App\Request;

use App\Validator\Constraints\EmailExists;
use Symfony\Component\Validator\Constraints as Assert;

class UserInfoRequest extends OnboardProcessStepRequest
{
    #[Assert\NotBlank]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[EmailExists]
    private ?string $email = null;

    #[Assert\Regex('/^\d{10,15}$/')]
    private ?string $phone = null;

    #[Assert\NotBlank]
    private ?string $subscriptionType = null;

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
