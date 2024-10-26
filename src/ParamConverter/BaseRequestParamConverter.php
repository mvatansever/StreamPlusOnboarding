<?php

namespace App\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequestParamConverter implements ParamConverterInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    protected function populateAndValidate(Request $request, object $requestObject, string $requestKey): void
    {
        $allReq = $request->request->all();
        $data = $allReq[$requestKey] ?? [];

        foreach ($data as $key => $value) {
            $setterMethod = 'set'.ucfirst($key);
            if (method_exists($requestObject, $setterMethod)) {
                $requestObject->$setterMethod($value);
            }
        }

        $this->validateRequest($request, $requestObject);
    }

    protected function validateRequest(Request $request, object $requestObject): void
    {
        $errors = $this->validator->validate($requestObject);
        if (count($errors) > 0 && $request->isXmlHttpRequest()) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new UnprocessableEntityHttpException(json_encode(['success' => false, 'errors' => $errorMessages]));
        }
    }
}
