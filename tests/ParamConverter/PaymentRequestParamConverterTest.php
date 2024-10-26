<?php

namespace App\Tests\ParamConverter;

use App\ParamConverter\PaymentRequestParamConverter;
use App\Request\PaymentRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PaymentRequestParamConverterTest extends TestCase
{
    private PaymentRequestParamConverter $converter;
    private $validatorMock;

    protected function setUp(): void
    {
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->converter = new PaymentRequestParamConverter($this->validatorMock);
    }

    public function testApplyValidRequest()
    {
        $request = new Request();
        $request->request->set('payment_request', [
            'cardNumber' => '4111111111111111',
            'expirationDate' => '12/25',
            'cvv' => '123',
        ]);

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList());

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('paymentRequest');

        $this->converter->apply($request, $configuration);

        $this->assertInstanceOf(PaymentRequest::class, $request->attributes->get('paymentRequest'));
        $paymentRequest = $request->attributes->get('paymentRequest');

        $this->assertEquals('4111111111111111', $paymentRequest->getCardNumber());
        $this->assertEquals('12/25', $paymentRequest->getExpirationDate());
        $this->assertEquals('123', $paymentRequest->getCvv());
    }

    public function testApplyInvalidRequest()
    {
        $request = new Request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->request->set('payment_request', [
            'cardNumber' => '', // Invalid cardNumber
            'expirationDate' => 'invalid-date',
            'cvv' => '123',
        ]);

        $violation = new ConstraintViolation(
            'Card number cannot be blank.',
            null,
            [],
            null,
            'cardNumber',
            ''
        );

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList([$violation]));

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('paymentRequest');

        $this->expectException(UnprocessableEntityHttpException::class);

        $this->converter->apply($request, $configuration);
    }
}
