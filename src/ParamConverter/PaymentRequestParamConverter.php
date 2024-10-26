<?php

namespace App\ParamConverter;

use App\Request\PaymentRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentRequestParamConverter implements ParamConverterInterface
{
    public function __construct(private ValidatorInterface $validator) {}

    public function apply(Request $request, ParamConverter $configuration)
    {
        $allReq = $request->request->all(); // or use $request->query for GET parameters

        $data = $allReq['payment_request'] ?? [];

        // Create an instance of the request class and set properties
        $paymentRequest = new PaymentRequest();
        $paymentRequest->setCardNumber($data['cardNumber'] ?? null);
        $paymentRequest->setExpirationDate($data['expirationDate'] ?? null);
        $paymentRequest->setCvv($data['cvv'] ?? null);

        $errors = $this->validator->validate($paymentRequest);
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
        $request->attributes->set($configuration->getName(), $paymentRequest);
        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return PaymentRequest::class === $configuration->getClass();
    }
}
