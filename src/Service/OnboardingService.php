<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Payment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OnboardingService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Save onboarding data.
     *
     * @param array<string, mixed> $addressData data related to the address
     * @param array<string, mixed> $paymentData data related to the payment
     * @param array<string, mixed> $userInfo    user information
     */
    public function saveOnboardingData(array $userInfo, array $addressData, ?array $paymentData): void
    {
        $user = new User();
        $user->setName($userInfo['name']);
        $user->setEmail($userInfo['email']);
        $user->setPhone($userInfo['phone']);
        $user->setSubscriptionType($userInfo['subscriptionType']);

        $this->entityManager->persist($user);

        $address = new Address();
        $address->setAddressLine1($addressData['addressLine1']);
        $address->setAddressLine2($addressData['addressLine2'] ?? null);
        $address->setCity($addressData['city']);
        $address->setPostalCode($addressData['postalCode']);
        $address->setStateProvince($addressData['stateProvince']);
        $address->setCountry($addressData['country']);

        $user->addAddress($address);

        $this->entityManager->persist($address);

        if ('premium' === $userInfo['subscriptionType'] && $paymentData) {
            $payment = new Payment();
            $payment->setCardNumber($paymentData['cardNumber']);
            $payment->setExpirationDate($paymentData['expirationDate']);

            $user->setPayment($payment);

            $this->entityManager->persist($payment);
        }

        $this->entityManager->flush();
    }
}
