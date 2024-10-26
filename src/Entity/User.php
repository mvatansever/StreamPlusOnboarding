<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity("email")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private $name;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\Regex(pattern: "/^\d{10,15}$/", message: "Please enter a valid phone number.")]
    private $phone;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\Choice(choices: ["free", "premium"], message: "Choose a valid subscription type.")]
    private $subscriptionType;

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

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function getSubscriptionType()
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType($subscriptionType): void
    {
        $this->subscriptionType = $subscriptionType;
    }
}
