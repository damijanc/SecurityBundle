<?php

namespace Damijanc\SecurityBundle\Tests\Stubs;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Class TokenInterfaceStub
 * @package Damijanc\SecurityBundle\tests\Stubs
 * @codeCoverageIgnore
 */
class TokenInterfaceStub implements TokenInterface
{


    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }

    /**
     * Returns a string representation of the Token.
     *
     * This is only to be used for debugging purposes.
     *
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
    }

    /**
     * Returns the user roles.
     *
     * @return RoleInterface[] An array of RoleInterface instances.
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return true;
    }

    /**
     * Returns a user representation.
     *
     * @return mixed Can be a UserInterface instance, an object implementing a __toString method,
     *               or the username as a regular string
     *
     * @see AbstractToken::setUser()
     */
    public function getUser()
    {
        // TODO: Implement getUser() method.
    }

    /**
     * Sets a user.
     *
     * @param mixed $user
     */
    public function setUser($user)
    {
        // TODO: Implement setUser() method.
    }

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return 'admin';
    }

    /**
     * Returns whether the user is authenticated or not.
     *
     * @return bool true if the token has been authenticated, false otherwise
     */
    public function isAuthenticated()
    {
        // TODO: Implement isAuthenticated() method.
    }

    /**
     * Sets the authenticated flag.
     *
     * @param bool $isAuthenticated The authenticated flag
     */
    public function setAuthenticated($isAuthenticated)
    {
        // TODO: Implement setAuthenticated() method.
    }

    /**
     * Removes sensitive information from the token.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Returns the token attributes.
     *
     * @return array The token attributes
     */
    public function getAttributes()
    {
        // TODO: Implement getAttributes() method.
    }

    /**
     * Sets the token attributes.
     *
     * @param array $attributes The token attributes
     */
    public function setAttributes(array $attributes)
    {
        // TODO: Implement setAttributes() method.
    }

    /**
     * Returns true if the attribute exists.
     *
     * @param string $name The attribute name
     *
     * @return bool true if the attribute exists, false otherwise
     */
    public function hasAttribute($name)
    {
        // TODO: Implement hasAttribute() method.
    }

    /**
     * Returns an attribute value.
     *
     * @param string $name The attribute name
     *
     * @return mixed The attribute value
     *
     * @throws \InvalidArgumentException When attribute doesn't exist for this token
     */
    public function getAttribute($name)
    {
        // TODO: Implement getAttribute() method.
    }

    /**
     * Sets an attribute.
     *
     * @param string $name The attribute name
     * @param mixed $value The attribute value
     */
    public function setAttribute($name, $value)
    {
        // TODO: Implement setAttribute() method.
    }
}
