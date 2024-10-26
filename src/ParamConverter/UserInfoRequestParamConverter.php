<?php

namespace App\ParamConverter;

use App\Request\UserInfoRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class UserInfoRequestParamConverter extends BaseRequestParamConverter
{
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $userInfoRequest = new UserInfoRequest();
        $this->populateAndValidate($request, $userInfoRequest, 'user_info_request');

        $request->attributes->set($configuration->getName(), $userInfoRequest);
        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return UserInfoRequest::class === $configuration->getClass();
    }
}
