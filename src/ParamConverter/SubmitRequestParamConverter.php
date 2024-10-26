<?php

namespace App\ParamConverter;

use App\Request\SubmitRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubmitRequestParamConverter implements ParamConverterInterface
{
    public function __construct(private ValidatorInterface $validator) {}

    public function apply(Request $request, ParamConverter $configuration)
    {
        // Create an instance of the request class
        $submitRequest = new SubmitRequest();

        // Extract data from the session instead of the request
        $submitRequest->setUserInfo($request->getSession()->get('user_info'));
        $submitRequest->setAddress($request->getSession()->get('address'));
        $submitRequest->setPayment($request->getSession()->get('payment'));

        $errors = $this->validator->validate($submitRequest);
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
        $request->attributes->set($configuration->getName(), $submitRequest);
        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return SubmitRequest::class === $configuration->getClass();
    }
}
