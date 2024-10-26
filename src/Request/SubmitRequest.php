<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class SubmitRequest extends OnboardProcessStepRequest
{
    /**
     * @var array<string, mixed>|null
     */
    #[Assert\NotBlank]
    private ?array $userInfo;

    /**
     * @var array{addressLine1: string, addressLine2?: string, city: string, postalCode: string, stateProvince: string, country: string}|null
     */
    #[Assert\NotBlank]
    private ?array $address;

    /**
     * @var array{cardNumber: string, expirationDate: string}|null
     */
    #[Assert\NotBlank]
    private ?array $payment;

    /**
     * @return array<string, mixed>|null
     */
    public function getUserInfo(): ?array
    {
        return $this->userInfo;
    }

    /**
     * @param array<string, mixed>|null $userInfo
     */
    public function setUserInfo(?array $userInfo): void
    {
        $this->userInfo = $userInfo;
    }

    /**
     * @return array{addressLine1: string, addressLine2?: string, city: string, postalCode: string, stateProvince: string, country: string}|null
     */
    public function getAddress(): ?array
    {
        return $this->address;
    }

    /**
     * @param array{addressLine1: string, addressLine2?: string, city: string, postalCode: string, stateProvince: string, country: string}|null $address
     */
    public function setAddress(?array $address): void
    {
        $this->address = $address;
    }

    /**
     * @return array{cardNumber: string, expirationDate: string}|null
     */
    public function getPayment(): ?array
    {
        return $this->payment;
    }

    /**
     * @param array{cardNumber: string, expirationDate: string}|null $payment
     */
    public function setPayment(?array $payment): void
    {
        $this->payment = $payment;
    }
}
