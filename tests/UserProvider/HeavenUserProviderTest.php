<?php

namespace Damijanc\SecurityBundle\Tests\UserProvider;

use Damijanc\SecurityBundle\Tests\Stubs\EventDispatcherInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceStub;
use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceStubWithStorage;
use Damijanc\SecurityBundle\Tests\Stubs\SessionInterfaceWithNoUserDataStub;
use Damijanc\SecurityBundle\Tests\Stubs\SomeUserStub;
use Damijanc\SecurityBundle\User\User;
use Damijanc\SecurityBundle\UserProvider\LoginUserProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * Class LoginUserProviderTest
 * @package Damijanc\SecurityBundle\tests\UserProvider
 */
class LoginUserProviderTest extends KernelTestCase
{
    public function testLoadUserByUsername()
    {
        $session         = new SessionInterfaceStub();
        $eventDispatcher = new EventDispatcherInterfaceStub();
        $provider        = new LoginUserProvider($session, $eventDispatcher);

        $result = $provider->loadUserByUsername('admin');

        $this->assertInstanceOf(User::class, $result);
    }


    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testUserLoadedByUsernameIsNotFound()
    {
        $session         = new SessionInterfaceWithNoUserDataStub();
        $eventDispatcher = new EventDispatcherInterfaceStub();

        $provider = new LoginUserProvider($session, $eventDispatcher);
        $provider->loadUserByUsername('admin');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testUserLoadedByUsernameThrowsAuthenticationError()
    {
        $eventDispatcher = new EventDispatcherInterfaceStub();

        $session = new SessionInterfaceStubWithStorage();
        $session->set(Security::AUTHENTICATION_ERROR, new AuthenticationException('test message'));

        $provider = new LoginUserProvider($session, $eventDispatcher);
        $provider->loadUserByUsername('admin');
    }

    public function testRefreshUser()
    {
        $session         = new SessionInterfaceStub();
        $eventDispatcher = new EventDispatcherInterfaceStub();
        $provider        = new LoginUserProvider($session, $eventDispatcher);

        $result = $provider->refreshUser(new User());

        self::assertInstanceOf(User::class, $result);
    }

    /**
     * @group userprovider
     */
    public function testRefreshUserWithReauthentication()
    {
        $session         = new SessionInterfaceStub();
        $eventDispatcher = new EventDispatcherInterfaceStub();
        $provider        = new LoginUserProvider($session, $eventDispatcher);

        $result = $provider->refreshUser(new User());

        self::assertInstanceOf(User::class, $result);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserException()
    {
        $session         = new SessionInterfaceStub();
        $eventDispatcher = new EventDispatcherInterfaceStub();
        $provider        = new LoginUserProvider($session, $eventDispatcher);

        $provider->refreshUser(new SomeUserStub());
    }

    public function testSupportsClass()
    {
        $session         = new SessionInterfaceStub();
        $eventDispatcher = new EventDispatcherInterfaceStub();
        $provider        = new LoginUserProvider($session, $eventDispatcher);

        $response = $provider->supportsClass(User::class);

        $this->assertEquals($response, true);
    }
}
