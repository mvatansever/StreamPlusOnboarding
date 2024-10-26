<?php

namespace App\Service\Handlers;

use App\Request\OnboardProcessStepRequest;
use App\Request\SubmitRequest;
use App\Service\OnboardingService;
use Symfony\Component\HttpFoundation\RequestStack;

class SubmitHandler implements HandlerInterface
{
    public function __construct(
        private readonly OnboardingService $onboardingService,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * @param SubmitRequest $stepRequest the request object containing payment data
     */
    public function handle(OnboardProcessStepRequest $stepRequest): HandlerResponse
    {
        $this->onboardingService->saveOnboardingData(
            $stepRequest->getUserInfo(),
            $stepRequest->getAddress(),
            $stepRequest->getPayment()
        );

        $this->requestStack->getSession()->clear();

        return new HandlerResponse(true, 'onboarding_complete');
    }
}
