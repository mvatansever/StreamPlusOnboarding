<?php

namespace App\Tests\ParamConverter;

use App\ParamConverter\UserInfoRequestParamConverter;
use App\Request\UserInfoRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserInfoRequestParamConverterTest extends TestCase
{
    private UserInfoRequestParamConverter $converter;
    private $validatorMock;

    protected function setUp(): void
    {
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->converter = new UserInfoRequestParamConverter($this->validatorMock);
    }

    public function testApplyValidRequest()
    {
        $request = new Request();
        $request->request->set('user_info_request', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'subscriptionType' => 'basic',
        ]);

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList());

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('userInfoRequest');

        $this->converter->apply($request, $configuration);

        $this->assertInstanceOf(UserInfoRequest::class, $request->attributes->get('userInfoRequest'));
        $userInfoRequest = $request->attributes->get('userInfoRequest');

        $this->assertEquals('John Doe', $userInfoRequest->getName());
        $this->assertEquals('john.doe@example.com', $userInfoRequest->getEmail());
        $this->assertEquals('1234567890', $userInfoRequest->getPhone());
        $this->assertEquals('basic', $userInfoRequest->getSubscriptionType());
    }

    public function testApplyInvalidRequest()
    {
        $request = new Request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->request->set('user_info_request', [
            'name' => '', // Invalid name
            'email' => 'invalid-email',
            'phone' => '1234567890',
            'subscriptionType' => 'basic',
        ]);

        $violation = new ConstraintViolation(
            'Invalid email format.',
            null,
            [],
            null,
            'email',
            'invalid-email'
        );

        $this->validatorMock->method('validate')->willReturn(new ConstraintViolationList([$violation]));

        $configuration = $this->createMock(ParamConverter::class);
        $configuration->method('getName')->willReturn('userInfoRequest');

        $this->expectException(UnprocessableEntityHttpException::class);

        $this->converter->apply($request, $configuration);
    }
}
