<?php

namespace Damijanc\SecurityBundle\Tests\Authenticator;

use Damijanc\SecurityBundle\Authenticator\ApiKeyAuthenticator;
use Damijanc\SecurityBundle\Authenticator\LoginAuthenticator;
use Damijanc\SecurityBundle\Event\ApiAuthenticateEvent;
use Damijanc\SecurityBundle\Event\ApiAuthenticationEvents;
use Damijanc\SecurityBundle\Tests\Stubs\EventDispatcherInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\TokenInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\TokenInterfaceStubWithoutCredentials;
use Damijanc\SecurityBundle\Tests\Stubs\UserProviderInerfaceStubWithException;
use Damijanc\SecurityBundle\Tests\Stubs\UserProviderInterfaceStub;
use Damijanc\SecurityBundle\User\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class LoginAuthenticatorTest
 * @package Damijanc\SecurityBundle\tests\Authenticator
 * @codeCoverageIgnore
 */
class LoginAuthenticatorTest extends KernelTestCase
{


    /**
     * @group authenticator
     */
    public function testAuthenticateTokenWithNoCredentials()
    {
        $dispatcher = new EventDispatcherInterfaceStub();
        $token = new TokenInterfaceStubWithoutCredentials();
        $userProvider = new UserProviderInterfaceStub();

        $authenticator = new LoginAuthenticator($dispatcher);

        $result = $authenticator->authenticateToken($token, $userProvider, 'default');

        self::assertInstanceOf(UsernamePasswordToken::class, $result);
    }

    /**
     * @group authenticator
     */
    public function testAuthenticateTokenWithCredentials()
    {
        $dispatcher = new EventDispatcherInterfaceStub();
        $token = new TokenInterfaceStub();
        $userProvider = new UserProviderInterfaceStub();

        $authenticator = new LoginAuthenticator($dispatcher);

        $result = $authenticator->authenticateToken($token, $userProvider, 'default');

        self::assertInstanceOf(UsernamePasswordToken::class, $result);
    }

    /**
     * * @group authenticator
     */
    public function testAuthenticateToken()
    {
        $dispatcher = new EventDispatcherInterfaceStub();

        $token = new TokenInterfaceStub();
        $userProvider = new UserProviderInterfaceStub();

        $authenticator = new LoginAuthenticator($dispatcher);

        $result = $authenticator->authenticateToken($token, $userProvider, 'default');

        self::assertInstanceOf(UsernamePasswordToken::class, $result);
    }

    public function testAuthenticatePasswordToken()
    {
        $userProvider = new UserProviderInterfaceStub();

        $user = new User();
        $token = new UsernamePasswordToken($user, 'password', 'default');

        $dispatcher = $this->getMockBuilder(EventDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(ApiAuthenticationEvents::AUTHENTICATE));

        $authenticator = new LoginAuthenticator($dispatcher);

        $authenticator->authenticateToken($token, $userProvider, 'default');
    }

    public function testPreauthenticatedToken()
    {
        $userProvider = new UserProviderInterfaceStub();

        $user = new User();
        $user->setApiKey('apiKey');
        $token = new PreAuthenticatedToken($user, null, 'default');

        $expectedEvent = new ApiAuthenticateEvent();
        $expectedEvent->setApiKey('apiKey');

        $dispatcher = $this->getMockBuilder(EventDispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(ApiAuthenticationEvents::API_KEY_AUTHENTICATE),
                $this->equalTo($expectedEvent)
            );

        $session = new SessionInterfaceStub();

        $authenticator = new ApiKeyAuthenticator($session, $dispatcher);

        $authenticator->authenticateToken($token, $userProvider, 'default');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     * @group authenticator
     */
    public function testAuthenticateTokenException()
    {
        $dispatcher = new EventDispatcherInterfaceStub();

        $token = new TokenInterfaceStub();
        $userProvider = new UserProviderInerfaceStubWithException();

        $authenticator = new LoginAuthenticator($dispatcher);

        $authenticator->authenticateToken($token, $userProvider, 'default');
    }

    /**
     * @group authenticator
     */
    public function testCreateToken()
    {
        list($authenticator, $request) = $this->getLoginAuthenticator();

        /** @var LoginAuthenticator $authenticator */
        $result = $authenticator->createToken($request, 'username', 'password', 'default');

        $this->assertInstanceOf(UsernamePasswordToken::class, $result);
    }

    /**
     * @group authenticator
     */
    public function testSupportsToken()
    {
        list($authenticator, $request) = $this->getLoginAuthenticator();
        /** @var LoginAuthenticator $authenticator */
        $tokenStubPass = $this->getMockBuilder(UsernamePasswordToken::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStubPass->method('getProviderKey')
            ->willReturn('default');
        /** @var TokenInterfaceStub $tokenStubPass */
        $this->assertTrue($authenticator->supportsToken($tokenStubPass, 'default'));


        $tokenStubFail = $this->getMockBuilder(PreAuthenticatedToken::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var TokenInterfaceStub $tokenStubFail */
        $tokenStubFail->method('getProviderKey')
            ->willReturn('sepcial');
        $this->assertFalse($authenticator->supportsToken($tokenStubFail, 'default'));
    }

    /**
     * @return array
     */
    protected function getLoginAuthenticator()
    {
        $dispatcher = new EventDispatcherInterfaceStub();

        $authenticator = new LoginAuthenticator($dispatcher);

        $request = new Request();
        return array($authenticator, $request);
    }
}
