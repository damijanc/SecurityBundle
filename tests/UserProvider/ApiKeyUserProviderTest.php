<?php
namespace Damijanc\SecurityBundle\Tests\UserProvider;

use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceStubWithStorage;
use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceWithNoUserDataStub;
use Damijanc\SecurityBundle\Tests\Stubs\SomeUserStub;
use Damijanc\SecurityBundle\User\User;
use Damijanc\SecurityBundle\UserProvider\ApiKeyUserProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class ApiKeyUserProviderTest extends KernelTestCase
{
    public function testLoadUserByUsername()
    {
        $session = new SessionInterfaceStub();
        $provider = new ApiKeyUserProvider($session);
        $result = $provider->loadUserByUsername('admin');

        $this->assertInstanceOf(User::class, $result);
    }


    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameException()
    {
        $session = new SessionInterfaceWithNoUserDataStub();
        $provider = new ApiKeyUserProvider($session);
        $result = $provider->loadUserByUsername('admin');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameAuthenticationException()
    {
        $session = new SessionInterfaceStubWithStorage();
        $session->set(Security::AUTHENTICATION_ERROR, new AuthenticationException('test message'));
        $provider = new ApiKeyUserProvider($session);
        $result = $provider->loadUserByUsername('admin');
    }


    public function testRefreshUser()
    {
        $session = new SessionInterfaceStub();
        $provider = new ApiKeyUserProvider($session);
        $result = $provider->refreshUser(new User());
        $this->assertInstanceOf(User::class, $result);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserException()
    {
        $session = new SessionInterfaceStub();
        $provider = new ApiKeyUserProvider($session);
        $result = $provider->refreshUser(new SomeUserStub());
    }

    public function testSupportsClass()
    {
        $session = new SessionInterfaceStub();
        $provider = new ApiKeyUserProvider($session);
        $response = $provider->supportsClass(User::class);
        $this->assertEquals($response, true);
    }
}
