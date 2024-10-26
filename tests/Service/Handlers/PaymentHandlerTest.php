<?php

namespace App\Tests\Service\Handlers;

use App\Request\PaymentRequest;
use App\Service\Handlers\HandlerResponse;
use App\Service\Handlers\PaymentHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentHandlerTest extends TestCase
{
    private PaymentHandler $paymentHandler;
    private $session;

    protected function setUp(): void
    {
        $this->session = $this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($this->session);

        $this->paymentHandler = new PaymentHandler($requestStack);
    }

    public function testHandleStoresPaymentInSession()
    {
        $paymentRequest = $this->createMock(PaymentRequest::class);
        $paymentRequest->method('getCardNumber')->willReturn('4111111111111111');
        $paymentRequest->method('getExpirationDate')->willReturn('12/25');
        $paymentRequest->method('getCvv')->willReturn('123');

        $this->session->expects($this->once())
            ->method('set')
            ->with('payment', [
                'cardNumber' => '4111111111111111',
                'expirationDate' => '12/25',
                'cvv' => '123',
            ]);

        $result = $this->paymentHandler->handle($paymentRequest);

        $this->assertInstanceOf(HandlerResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals('onboarding_address', $result->getRedirectUrl());
    }
}
