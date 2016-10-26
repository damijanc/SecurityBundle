##Listeners

Bundle raises 2 events AUTHENTICATE and API_KEY_AUTHENTICATE.
In order for bundle to work you need to implement event listeners in your code that will handle your api call and responses.

When you retrieve your user set it to ```AuthenticationConstants::SECURITY_TOKEN_NAME``` session variable.

Bellow is an empty shell you can use as an entry point.

```php
<?php

namespace Damijanc\SecurityBundle\Event;

use JMS\DiExtraBundle\Annotation as JMS;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ApiAuthenticationEventListener
 * @package Damijanc\SecurityBundle\Event
 * @JMS\Service("api_authentication_event_listener")
 * @JMS\Tag("kernel.event_subscriber")
 */
class ApiAuthenticationEventListener implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     * The array keys are event names and the value can be:
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     * For instance:
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     * @return array The event names to listen to
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [
            ApiAuthenticationEvents::AUTHENTICATE              => 'onAuthenticate',
            ApiAuthenticationEvents::API_KEY_AUTHENTICATE      => 'onApiKeyAuthenticate'
        ];
    }

    /**
     * @param ApiAuthenticateEvent $event User instance
     *
     * @return \Damijanc\SecurityBundle\Event\ApiAuthenticateEvent
     */
    public function onAuthenticate(ApiAuthenticateEvent $event)
    {
       //your custom logic here

        return $event;
    }


    /**
     * @param ApiAuthenticateEvent $event
     *
     * @return \Damijanc\SecurityBundle\Event\ApiAuthenticateEvent
     */
    public function onApiKeyAuthenticate(ApiAuthenticateEvent $event)
    {
        //your custom logic here

        return $event;
    }
}

```