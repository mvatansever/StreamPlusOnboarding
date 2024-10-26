<?php

namespace App\Service\Handlers;

use App\Request\OnboardProcessStepRequest;
use App\Request\PaymentRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class PaymentHandler implements HandlerInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * @param PaymentRequest $stepRequest the request object containing payment data
     */
    public function handle(OnboardProcessStepRequest $stepRequest): HandlerResponse
    {
        $this->requestStack->getSession()->set('payment', [
            'cardNumber' => $stepRequest->getCardNumber(),
            'expirationDate' => $stepRequest->getExpirationDate(),
            'cvv' => $stepRequest->getCvv(),
        ]);

        return new HandlerResponse(true, 'onboarding_address');
    }
}
