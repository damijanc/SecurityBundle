<?php

namespace Damijanc\SecurityBundle\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package Damijanc\SecurityBundle\User
 */
class User implements UserInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $roles = [];


    //************************ GENERATED CODE ******************************88


    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     *
     * @codeCoverageIgnore
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @codeCoverageIgnore
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @param string $password
     * @return string The password
     *
     * @codeCoverageIgnore
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getRoles()
    {
        return $this->roles;
    }


    /**
     * @param string $name
     * @return bool
     */
    public function hasRole($name)
    {
        return in_array($name, $this->roles, true);
    }

    /**
     * @param array $roles
     *
     * @codeCoverageIgnore
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     *
     * @codeCoverageIgnore
     */
    public function getSalt()
    {
        return null;
    }


    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
