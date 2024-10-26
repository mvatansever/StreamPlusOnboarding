<?php

namespace App\Tests\ParamConverter;

use App\ParamConverter\SubmitRequestParamConverter;
use App\Request\SubmitRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SubmitRequestParamConverterTest extends TestCase
{
    private SubmitRequestParamConverter $converter;
    private $validatorMock;

    protected function setUp(): void
    {
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->converter = new SubmitRequestParamConverter($this->validatorMock);
    }

    public function testApplyValidRequest()
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $request->getSession()->set('user_info', ['name' => 'John Doe', 'email' => 'john.doe@example.com']);
        $request->getSession()->set('address', ['addressLine1' => '123 Main St', 'city' => 'Anytown']);
        $request->getSession()->set('payment', ['cardNumber' => '4111111111111111', 'expirationDate' => '12/25', 'cvv' => '123']);

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList());

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('submitRequest');

        $this->converter->apply($request, $configuration);

        $this->assertInstanceOf(SubmitRequest::class, $request->attributes->get('submitRequest'));
        $submitRequest = $request->attributes->get('submitRequest');

        $this->assertEquals('John Doe', $submitRequest->getUserInfo()['name']);
        $this->assertEquals('john.doe@example.com', $submitRequest->getUserInfo()['email']);
        $this->assertEquals('123 Main St', $submitRequest->getAddress()['addressLine1']);
        $this->assertEquals('Anytown', $submitRequest->getAddress()['city']);
        $this->assertEquals('4111111111111111', $submitRequest->getPayment()['cardNumber']);
    }

    public function testApplyInvalidRequest()
    {
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->getSession()->set('user_info', null);
        $request->getSession()->set('address', null);
        $request->getSession()->set('payment', null);

        $violation = new ConstraintViolation(
            'User information is required.',
            null,
            [],
            null,
            'user_info',
            null
        );

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList([$violation]));

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('submitRequest');

        $this->expectException(UnprocessableEntityHttpException::class);

        $this->converter->apply($request, $configuration);
    }
}
