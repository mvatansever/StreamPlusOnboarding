<?php

namespace App\Service\Handlers;

use App\Request\AddressRequest;
use App\Request\OnboardProcessStepRequest;
use Symfony\Component\HttpFoundation\RequestStack;

class AddressHandler implements HandlerInterface
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * @param AddressRequest $stepRequest the request object containing payment data
     */
    public function handle(OnboardProcessStepRequest $stepRequest): HandlerResponse
    {
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
