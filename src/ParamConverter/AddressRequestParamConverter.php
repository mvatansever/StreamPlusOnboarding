<?php

namespace App\ParamConverter;

use App\Request\AddressRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressRequestParamConverter implements ParamConverterInterface
{
    public function __construct(private ValidatorInterface $validator) {}

    public function apply(Request $request, ParamConverter $configuration)
    {
        $allReq = $request->request->all(); // or use $request->query for GET parameters

        $data = $allReq['address_request'] ?? [];

        // Create an instance of the request class and set properties
        $addressRequest = new AddressRequest();
        $addressRequest->setAddressLine1($data['addressLine1'] ?? null);
        $addressRequest->setAddressLine2($data['addressLine2'] ?? null);
        $addressRequest->setCity($data['city'] ?? null);
        $addressRequest->setPostalCode($data['postalCode'] ?? null);
        $addressRequest->setStateProvince($data['stateProvince'] ?? null);
        $addressRequest->setCountry($data['country'] ?? null);

        $errors = $this->validator->validate($addressRequest);
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
        $request->attributes->set($configuration->getName(), $addressRequest);
        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return AddressRequest::class === $configuration->getClass();
    }
}
