<?php

namespace Damijanc\SecurityBundle\Tests\Stubs;

class SessionInterfaceStubWithStorage extends SessionInterfaceStub
{
    private $storage = [];

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
        if (array_key_exists($name, $this->storage)) {
            return $this->storage[$name];
        }
    }

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param mixed $value
     *
     * @api
     */
    public function set($name, $value)
    {
        $this->storage[$name] = $value;
    }


    /**
     * Checks if an attribute is defined.
     *
     * @param string $name The attribute name
     *
     * @return bool true if the attribute is defined, false otherwise
     *
     * @api
     */
    public function has($name)
    {
        return array_key_exists($name, $this->storage);
    }


    public function clear()
    {
        $this->storage = [];
    }
}
