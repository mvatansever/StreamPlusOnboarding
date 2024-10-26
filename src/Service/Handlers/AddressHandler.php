<?php

namespace App\Service\Handlers;

use App\Request\OnboardProcessStepRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class AddressHandler implements HandlerInterface
{
    public function __construct(private readonly RequestStack $requestStack) {}

    public function handle(OnboardProcessStepRequest $stepRequest): HandlerResponse
    {
        // Store the validated address in the session
        $this->requestStack->getSession()->set('address', [
            'addressLine1' => $stepRequest->getAddressLine1(),
            'addressLine2' => $stepRequest->getAddressLine2(),
            'city' => $stepRequest->getCity(),
            'postalCode' => $stepRequest->getPostalCode(),
            'stateProvince' => $stepRequest->getStateProvince(),
            'country' => $stepRequest->getCountry(),
        ]);

        return new HandlerResponse(true, 'onboarding_confirmation');
    }
}
