<?php

namespace Damijanc\SecurityBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ApiAuthenticateEvent
 * @package Damijanc\SecurityBundle\Event
 *
 * @codeCoverageIgnore
 */
class ApiAuthenticateEvent extends Event
{
    /** @var  string */
    protected $apiKey;

    /** @var  string */
    protected $userName;

    /** @var  string */
    protected $password;


    //*************** GENERATED CODE ***********************


    /**
     * @return string
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
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
