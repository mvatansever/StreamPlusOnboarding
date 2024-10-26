<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity] // Corrected to use class reference
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null; // Initialize to avoid PHPStan warning

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^\d{12,19}$/", message: 'Please enter a valid card number.')]
    private ?string $cardNumber = null; // Initialize to avoid PHPStan warning

    #[ORM\Column(type: 'string', length: 5)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: "/^(0[1-9]|1[0-2])\/\d{2}$/", message: 'Expiration date must be in MM/YY format.')]
    private ?string $expirationDate = null; // Initialize to avoid PHPStan warning

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'payment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null; // Initialize to avoid PHPStan warning

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?string $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    public function getUser(): ?User // Added return type hint
    {
        return $this->user;
    }

    public function setUser(User $user): void // Added parameter type hint
    {
        $this->user = $user;
    }
}
