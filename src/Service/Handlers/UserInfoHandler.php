<?php

namespace App\Service\Handlers;

use App\Request\OnboardProcessStepRequest;
use App\Request\UserInfoRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class UserInfoHandler implements HandlerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * @param UserInfoRequest $stepRequest the request object containing payment data
     */
    public function handle(OnboardProcessStepRequest $stepRequest): HandlerResponse
    {
        $this->requestStack->getSession()->set('user_info', [
            'name' => $stepRequest->getName(),
            'email' => $stepRequest->getEmail(),
            'phone' => $stepRequest->getPhone(),
            'subscriptionType' => $stepRequest->getSubscriptionType(),
        ]);

        $redirectUrl = 'premium' === $stepRequest->getSubscriptionType() ? 'onboarding_payment' : 'onboarding_address';

        return new HandlerResponse(true, $redirectUrl);
    }
}
