<?php

namespace App\Service\Handlers;

use App\Request\OnboardProcessStepRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class UserInfoHandler implements HandlerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ){}

    public function handle(OnboardProcessStepRequest $stepRequest): HandlerResponse
    {
        // Store the validated user info in the session
        $this->requestStack->getSession()->set('user_info', [
            'name' => $stepRequest->getName(),
            'email' => $stepRequest->getEmail(),
            'phone' => $stepRequest->getPhone(),
            'subscriptionType' => $stepRequest->getSubscriptionType(),
        ]);

        $redirectUrl = $stepRequest->getSubscriptionType() === 'premium' ? 'onboarding_payment' : 'onboarding_address';

        return new HandlerResponse(true, $redirectUrl);
    }
}
