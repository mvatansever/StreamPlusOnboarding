<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\OnboardingService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class OnboardingServiceTest extends TestCase
{
    private OnboardingService $onboardingService;
    private $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->userRepository = $this->createMock(EntityRepository::class);
        $this->entityManager
            ->method('getRepository')
            ->willReturn($this->userRepository);

        $this->onboardingService = new OnboardingService($this->entityManager);
    }

    public function testSaveNewUser()
    {
        $userInfo = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'subscriptionType' => User::FREE_USER,
        ];

        $addressData = [
            'addressLine1' => '123 Main St',
            'addressLine2' => null,
            'city' => 'Anytown',
            'postalCode' => '12345',
            'stateProvince' => 'State',
            'country' => 'Country',
        ];

        $paymentData = [];

        $this->entityManager->expects($this->exactly(2))
            ->method('persist');

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->onboardingService->saveOnboardingData($userInfo, $addressData, $paymentData);
    }
}
