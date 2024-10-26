<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity('email')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\Regex(pattern: "/^\d{10,15}$/", message: 'Please enter a valid phone number.')]
    private ?string $phone;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\Choice(choices: ['free', 'premium'], message: 'Choose a valid subscription type.')]
    private ?string $subscriptionType;

    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class, cascade: ['persist'])]
    private Collection $addresses;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Payment::class, cascade: ['persist'])]
    private ?Payment $payment = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

    public function addAddress(Address $address): self
    {
        $this->addresses->add($address);
        $address->setUser($this);

        return $this;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;
        $payment->setUser($this);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getSubscriptionType(): ?string
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(string $subscriptionType): void
    {
        $this->subscriptionType = $subscriptionType;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }
}
