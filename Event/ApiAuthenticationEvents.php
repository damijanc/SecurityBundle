<?php

namespace Damijanc\SecurityBundle\Event;

/**
 * Class ApiAuthenticationEvents
 * @package Damijanc\SecurityBundle\Event
 *
 * @codeCoverageIgnore
 */
final class ApiAuthenticationEvents
{
    /**
     * The authenticate event is thrown each time username authentication is triggered
     *
     * The event listener receives an
     * Damijanc\SecurityBundle\Event\ApiAuthenticateEvent instance.
     *
     * @var string
     */
    const AUTHENTICATE = 'damijanc.authenticate';

    /**
     * The authenticate event is thrown each time api key authentication is triggered
     *
     * The event listener receives an
     * Damijanc\SecurityBundle\Event\ApiAuthenticateEvent instance.
     *
     * @var string
     */
    const API_KEY_AUTHENTICATE = 'damijanc.api.key.authenticate';
}
