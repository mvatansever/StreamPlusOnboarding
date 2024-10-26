<?php

namespace App\ParamConverter;

use App\Request\SubmitRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class SubmitRequestParamConverter extends BaseRequestParamConverter
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $submitRequest = new SubmitRequest();

        $submitRequest->setUserInfo($request->getSession()->get('user_info'));
        $submitRequest->setAddress($request->getSession()->get('address'));
        $submitRequest->setPayment($request->getSession()->get('payment'));

        $this->validateRequest($request, $submitRequest);

        $request->attributes->set($configuration->getName(), $submitRequest);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return SubmitRequest::class === $configuration->getClass();
    }
}
