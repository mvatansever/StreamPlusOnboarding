<?php

namespace App\Tests\Service\Handlers;

use App\Request\UserInfoRequest;
use App\Service\Handlers\UserInfoHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserInfoHandlerTest extends TestCase
{
    private UserInfoHandler $userInfoHandler;
    private $session;

    protected function setUp(): void
    {
        $this->session = $this->createMock(SessionInterface::class);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getSession')->willReturn($this->session);

        $this->userInfoHandler = new UserInfoHandler($requestStack);
    }

    /**
     * @dataProvider userInfoProvider
     */
    public function testHandleRedirectUrl(array $userInfoData, string $expectedRedirectUrl)
    {
        $userInfoRequest = new UserInfoRequest();
        $userInfoRequest->setName($userInfoData['name']);
        $userInfoRequest->setEmail($userInfoData['email']);
        $userInfoRequest->setPhone($userInfoData['phone']);
        $userInfoRequest->setSubscriptionType($userInfoData['subscriptionType']);

        $result = $this->userInfoHandler->handle($userInfoRequest);

        $this->assertEquals($expectedRedirectUrl, $result->getRedirectUrl());
    }

    public function userInfoProvider()
    {
        return [
            'Valid Free Subscription' => [
                [
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'phone' => '1234567890',
                    'subscriptionType' => 'free',
                ],
                'onboarding_address',
            ],
            'Valid Premium Subscription' => [
                [
                    'name' => 'Premium User',
                    'email' => 'premium@example.com',
                    'phone' => '0987654321',
                    'subscriptionType' => 'premium',
                ],
                'onboarding_payment',
            ],
        ];
    }
    public function testHandleStoresUserInfoInSession()
    {
        $userInfoRequest = new UserInfoRequest();
        $userInfoRequest->setName('Test User');
        $userInfoRequest->setEmail('test@example.com');
        $userInfoRequest->setPhone('1234567890');
        $userInfoRequest->setSubscriptionType('free');

        $this->session->expects($this->once())
            ->method('set')
            ->with('user_info', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '1234567890',
                'subscriptionType' => 'free',
            ]);

        $this->userInfoHandler->handle($userInfoRequest);
    }
}
