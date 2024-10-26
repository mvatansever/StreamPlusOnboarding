<?php

namespace App\Request;

use App\Validator\Constraints\FutureExpirationDate;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentRequest extends OnboardProcessStepRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 12, max: 19)]
    #[Assert\Luhn]
    #[Assert\Regex('/^\d+$/', message: 'Card number must be numeric.')]
    private ?string $cardNumber;

    #[Assert\NotBlank]
    #[Assert\Regex('/^(0[1-9]|1[0-2])\/\d{2}$/', message: 'Expiration date must be in MM/YY format.')]
    #[FutureExpirationDate]
    private ?string $expirationDate;

    #[Assert\NotBlank]
    #[Assert\Regex('/^\d{3}$/', message: 'CVV must be 3 digits.')]
    private ?string $cvv;

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(?string $cvv): void
    {
        $this->cvv = $cvv;
    }
}
