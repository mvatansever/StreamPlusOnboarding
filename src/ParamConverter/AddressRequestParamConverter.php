<?php

namespace App\ParamConverter;

use App\Request\AddressRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class AddressRequestParamConverter extends BaseRequestParamConverter
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $addressRequest = new AddressRequest();
        $this->populateAndValidate($request, $addressRequest, 'address_request');

        $request->attributes->set($configuration->getName(), $addressRequest);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return AddressRequest::class === $configuration->getClass();
    }
}
