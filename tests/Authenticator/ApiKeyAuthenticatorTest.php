<?php
namespace Damijanc\SecurityBundle\Tests\Authenticator;

use Damijanc\SecurityBundle\Authenticator\ApiKeyAuthenticator;
use Damijanc\SecurityBundle\Tests\Stubs\EventDispatcherInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\TokenInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\TokenInterfaceStubWithAttribute;
use Damijanc\SecurityBundle\Tests\Stubs\TokenInterfaceStubWithoutCredentials;
use Damijanc\SecurityBundle\Tests\Stubs\UserProviderInerfaceStubWithException;
use Damijanc\SecurityBundle\Tests\Stubs\UserProviderInterfaceStub;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

/**
 * Class ApiKeyAuthenticatorTest
 * @package Damijanc\SecurityBundle\tests\Authenticator
 * @codeCoverageIgnore
 */
class ApiKeyAuthenticatorTest extends KernelTestCase
{
    /**
     * @group authenticator
     */
    public function testAuthenticateToken()
    {
        $dispatcher = new EventDispatcherInterfaceStub();

        $token = new TokenInterfaceStub();
        $userProvider = new UserProviderInterfaceStub();


        $session = new SessionInterfaceStub();


        $authenticator = new ApiKeyAuthenticator($session, $dispatcher);

        $result = $authenticator->authenticateToken($token, $userProvider, 'default');

        self::assertInstanceOf(PreAuthenticatedToken::class, $result);
    }
    /**
     * @group authenticator
     */
    public function testAuthenticateTokenWithoutCrendentials()
    {
        $dispatcher = new EventDispatcherInterfaceStub();

        $token = new TokenInterfaceStubWithoutCredentials();
        $userProvider = new UserProviderInterfaceStub();


        $session = new SessionInterfaceStub();


        $authenticator = new ApiKeyAuthenticator($session, $dispatcher);

        $result = $authenticator->authenticateToken($token, $userProvider, 'default');

        self::assertInstanceOf(PreAuthenticatedToken::class, $result);
    }

    /**
     * @group authenticator
     *
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testAuthenticateTokenException()
    {
        $dispatcher = new EventDispatcherInterfaceStub();

        $token = new TokenInterfaceStub();
        $userProvider = new UserProviderInerfaceStubWithException();


        $session = new SessionInterfaceStub();


        $authenticator = new ApiKeyAuthenticator($session, $dispatcher);

        $result = $authenticator->authenticateToken($token, $userProvider, 'default');

        $this->assertEquals(null, $result);
    }


    /**
     * @group authenticator
     */
    public function testCreateTokenException()
    {
        /** @var ApiKeyAuthenticator $authenticator */
        list($request, $authenticator) = $this->getApiKeyAuthenticator();

        $token = $authenticator->createToken($request, 'default');

        $this->assertEquals(null, $token);
    }


    /**
     * @group authenticator
     */
    public function testCreateTokenReturnsPreauthToken()
    {
        /** @var Request $request */
        /** @var ApiKeyAuthenticator $authenticator */
        list($request, $authenticator) = $this->getApiKeyAuthenticator();

        $request->query->set('apiKey', '_API_KEY_');

        $this->assertInstanceOf(PreAuthenticatedToken::class, $authenticator->createToken($request, 'default'));

        $request->query->set('oneTimeKey', '_API_KEY_');

        $this->assertInstanceOf(PreAuthenticatedToken::class, $authenticator->createToken($request, 'default'));
    }

    /**
     * @group authenticator
     */
    public function testSupportsToken()
    {
        /** @var ApiKeyAuthenticator $authenticator */
        $authenticator = $this->getApiKeyAuthenticator()[1];

        $tokenStubPass = $this->getMockBuilder(PreAuthenticatedToken::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var TokenInterfaceStub $tokenStubPass */
        $tokenStubPass->method('getProviderKey')
            ->willReturn('default');

        $this->assertTrue($authenticator->supportsToken($tokenStubPass, 'default'));

        /** @var TokenInterfaceStub $tokenStubFail */
        $tokenStubFail = $this->getMockBuilder(PreAuthenticatedToken::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tokenStubFail->method('getProviderKey')
            ->willReturn('special');

        $this->assertFalse($authenticator->supportsToken($tokenStubFail, 'default'));
    }

    /**
     * @param array $query
     * @return array
     */
    protected function getApiKeyAuthenticator($query = [])
    {
        $dispatcher = new EventDispatcherInterfaceStub();
        $session = new SessionInterfaceStub();


        $request = new Request($query);


        $authenticator = new ApiKeyAuthenticator($session, $dispatcher);

        return [$request, $authenticator];
    }
}
