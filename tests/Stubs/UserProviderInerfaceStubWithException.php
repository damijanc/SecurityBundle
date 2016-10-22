<?php

namespace Damijanc\SecurityBundle\Tests\Stubs;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProviderInerfaceStubWithException extends UserProviderInterfaceStub
{
    public function loadUserByUsername($username)
    {
        throw new UsernameNotFoundException();
    }
}
