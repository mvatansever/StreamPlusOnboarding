<?php

namespace App\Tests\Service\Handlers;

use App\Request\AddressRequest;
use App\Service\Handlers\AddressHandler;
use App\Service\Handlers\HandlerResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class AddressHandlerTest extends TestCase
{
    private AddressHandler $addressHandler;
    private $session;

    protected function setUp(): void
    {
        $this->session = $this->createMock(\Symfony\Component\HttpFoundation\Session\SessionInterface::class);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($this->session);

        $this->addressHandler = new AddressHandler($requestStack);
    }

    public function testHandleStoresAddressInSession()
    {
        $addressRequest = $this->createMock(AddressRequest::class);
        $addressRequest->method('getAddressLine1')->willReturn('123 Main St');
        $addressRequest->method('getAddressLine2')->willReturn('Apt 4B');
        $addressRequest->method('getCity')->willReturn('Sample City');
        $addressRequest->method('getPostalCode')->willReturn('12345');
        $addressRequest->method('getStateProvince')->willReturn('CA');
        $addressRequest->method('getCountry')->willReturn('USA');

        $this->session->expects($this->once())
            ->method('set')
            ->with('address', [
                'addressLine1' => '123 Main St',
                'addressLine2' => 'Apt 4B',
                'city' => 'Sample City',
                'postalCode' => '12345',
                'stateProvince' => 'CA',
                'country' => 'USA',
            ]);

        $result = $this->addressHandler->handle($addressRequest);

        $this->assertInstanceOf(HandlerResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals('onboarding_confirmation', $result->getRedirectUrl());
    }
}
