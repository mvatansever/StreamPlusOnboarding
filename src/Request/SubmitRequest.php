<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class SubmitRequest extends OnboardProcessStepRequest
{
    #[Assert\NotBlank]
    private ?array $userInfo;

    #[Assert\NotBlank]
    private ?array $address;

    #[Assert\NotBlank]
    private ?array $payment;

    // Getters and Setters
    public function getUserInfo(): ?array
    {
        return $this->userInfo;
    }

    public function setUserInfo(?array $userInfo): void
    {
        $this->userInfo = $userInfo;
    }

    public function getAddress(): ?array
    {
        return $this->address;
    }

    public function setAddress(?array $address): void
    {
        $this->address = $address;
    }

    public function getPayment(): ?array
    {
        return $this->payment;
    }

    public function setPayment(?array $payment): void
    {
        $this->payment = $payment;
    }
}
