<?php

// src/ParamConverter/UserInfoRequestParamConverter.php
namespace App\ParamConverter;

use App\Request\UserInfoRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserInfoRequestParamConverter implements ParamConverterInterface
{
    public function __construct(private ValidatorInterface $validator) {}

    public function apply(Request $request, ParamConverter $configuration)
    {
        // Extract data from the request
        $allReq = $request->request->all(); // or use $request->query for GET parameters

        $data = $allReq['user_info_request'] ?? [];

        // Create an instance of the request class and set properties
        $userInfoRequest = new UserInfoRequest();

        // Only set properties if they exist in the request
        $userInfoRequest->setName($data['name'] ?? null);
        $userInfoRequest->setEmail($data['email'] ?? null);
        $userInfoRequest->setPhone($data['phone'] ?? null);
        $userInfoRequest->setSubscriptionType($data['subscriptionType'] ?? null);

        $errors = $this->validator->validate($userInfoRequest);
        if (count($errors) > 0 && $request->isXmlHttpRequest()) {
            // Collect error messages for JSON response
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            // Return JSON response instead of throwing an exception
            throw new UnprocessableEntityHttpException(json_encode(['success' => false, 'errors' => $errorMessages]));
        }

        // Set the converted request object to the request attributes
        $request->attributes->set($configuration->getName(), $userInfoRequest);
        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return UserInfoRequest::class === $configuration->getClass();
    }
}
