<?php

namespace App\Tests\Service\Handlers;

use App\Request\SubmitRequest;
use App\Service\Handlers\HandlerResponse;
use App\Service\Handlers\SubmitHandler;
use App\Service\OnboardingService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class SubmitHandlerTest extends TestCase
{
    private SubmitHandler $submitHandler;
    private $onboardingService;
    private $session;

    protected function setUp(): void
    {
        $this->onboardingService = $this->createMock(OnboardingService::class);

        $this->session = $this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($this->session);

        $this->submitHandler = new SubmitHandler($this->onboardingService, $requestStack);
    }

    public function testHandleSavesDataAndClearsSession()
    {
        $submitRequest = $this->createMock(SubmitRequest::class);
        $submitRequest->method('getUserInfo')->willReturn(['name' => 'John Doe']);
        $submitRequest->method('getAddress')->willReturn(['addressLine1' => '123 Main St']);
        $submitRequest->method('getPayment')->willReturn(['cardNumber' => '4111111111111111']);

        $this->onboardingService->expects($this->once())
            ->method('saveOnboardingData')
            ->with(
                ['name' => 'John Doe'],
                ['addressLine1' => '123 Main St'],
                ['cardNumber' => '4111111111111111']
            );

        $this->session->expects($this->once())
            ->method('clear');

        $result = $this->submitHandler->handle($submitRequest);

        $this->assertInstanceOf(HandlerResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals('onboarding_complete', $result->getRedirectUrl());
    }
}
