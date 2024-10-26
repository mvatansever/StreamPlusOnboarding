<?php

namespace App\ParamConverter;

use App\Request\PaymentRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class PaymentRequestParamConverter extends BaseRequestParamConverter
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $paymentRequest = new PaymentRequest();
        $this->populateAndValidate($request, $paymentRequest, 'payment_request');

        $request->attributes->set($configuration->getName(), $paymentRequest);
        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return PaymentRequest::class === $configuration->getClass();
    }
}
