<?php

namespace App\Tests\ParamConverter;

use App\ParamConverter\AddressRequestParamConverter;
use App\Request\AddressRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AddressRequestParamConverterTest extends TestCase
{
    private AddressRequestParamConverter $converter;
    private $validatorMock;

    protected function setUp(): void
    {
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->converter = new AddressRequestParamConverter($this->validatorMock);
    }

    public function testApplyValidRequest()
    {
        $request = new Request();
        $request->request->set('address_request', [
            'addressLine1' => '123 Main St',
            'addressLine2' => 'Apt 4B',
            'city' => 'Springfield',
            'postalCode' => '12345',
            'stateProvince' => 'IL',
            'country' => 'USA',
        ]);

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList());

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('addressRequest');

        $this->converter->apply($request, $configuration);

        $this->assertInstanceOf(AddressRequest::class, $request->attributes->get('addressRequest'));
        $addressRequest = $request->attributes->get('addressRequest');

        $this->assertEquals('123 Main St', $addressRequest->getAddressLine1());
        $this->assertEquals('Apt 4B', $addressRequest->getAddressLine2());
        $this->assertEquals('Springfield', $addressRequest->getCity());
        $this->assertEquals('12345', $addressRequest->getPostalCode());
        $this->assertEquals('IL', $addressRequest->getStateProvince());
        $this->assertEquals('USA', $addressRequest->getCountry());
    }

    public function testApplyInvalidRequest()
    {
        $request = new Request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->request->set('address_request', [
            'addressLine1' => '', // Invalid addressLine1
            'city' => 'Springfield',
            'postalCode' => '12345',
            'stateProvince' => 'IL',
            'country' => 'USA',
        ]);

        $violation = new ConstraintViolation(
            'Address line cannot be blank.',
            null,
            [],
            null,
            'addressLine1',
            ''
        );

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList([$violation]));

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('addressRequest');

        $this->expectException(UnprocessableEntityHttpException::class);

        $this->converter->apply($request, $configuration);
    }
}
