<?php

namespace Damijanc\SecurityBundle\Authenticator;

use Damijanc\SecurityBundle\Event\ApiAuthenticateEvent;
use Damijanc\SecurityBundle\Event\ApiAuthenticationEvents;
use Damijanc\SecurityBundle\User\User;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class LoginAuthenticator
 * @package Damijanc\SecurityBundle\Security
 *
 * @Service("login_authenticator")
 */
class LoginAuthenticator implements SimpleFormAuthenticatorInterface
{

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     *
     * @InjectParams({
     *      "dispatcher" = @Inject("event_dispatcher")
     * })
     */
    public function __construct($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $event = new ApiAuthenticateEvent();

            if (null !== $token->getCredentials()) {
                $event->setUserName($token->getUsername());
                $event->setPassword($token->getCredentials());
                $this->dispatcher->dispatch(ApiAuthenticationEvents::AUTHENTICATE, $event);
            } elseif ($token->getUser() instanceof User) {
                //if we have no password then only way to authenticate is by api key
                $event->setApiKey($token->getUser()->getApiKey());
                $this->dispatcher->dispatch(ApiAuthenticationEvents::API_KEY_AUTHENTICATE, $event);
            }

            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException($e->getMessage());
        }

        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
                && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param Request $request
     * @param $username
     * @param $password
     * @param $providerKey
     * @return UsernamePasswordToken
     */
    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
