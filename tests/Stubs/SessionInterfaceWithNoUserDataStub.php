<?php

namespace Damijanc\SecurityBundle\Tests\Stubs;

/**
 * Class SessionInterfaceWithNoUserDataStub
 * @package Damijanc\SecurityBundle\tests\Stubs
 */
class SessionInterfaceWithNoUserDataStub extends SessionInterfaceStub
{

    /**
     * Returns an attribute.
     *
     * @param string $name The attribute name
     * @param mixed $default The default value if not found.
     *
     * @return mixed
     *
     * @api
     */
    public function get($name, $default = null)
    {
        return;
    }
}
