<?php

namespace Damijanc\SecurityBundle\Authenticator;

use Damijanc\SecurityBundle\Event\ApiAuthenticateEvent;
use Damijanc\SecurityBundle\Event\ApiAuthenticationEvents;
use Damijanc\SecurityBundle\User\User;
use JMS\DiExtraBundle\Annotation as JMS;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class ApiKeyAuthenticator
 * @package Damijanc\SecurityBundle\Authenticator
 *
 * @JMS\Service("damijanc_api_key_authenticator")
 */
class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface
{

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param SessionInterface         $session
     * @param EventDispatcherInterface $dispatcher
     *
     * @JMS\InjectParams({
     *       "session" = @JMS\Inject("session"),
     *      "dispatcher" = @JMS\Inject("event_dispatcher")
     * })
     */
    public function __construct(SessionInterface $session, EventDispatcherInterface $dispatcher)
    {
        $this->session = $session;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        return $this->createApiKeyToken($request, $providerKey);
    }

    /**
     * @param Request $request
     * @param $providerKey
     * @return PreAuthenticatedToken
     */
    protected function createApiKeyToken(Request $request, $providerKey)
    {
        // look for an apikey query parameter
        $apiKey = $request->query->get('apiKey');


        // or if you want to use an "apikey" header, then do something like this:
        // $apiKey = $request->headers->get('apikey');

        if (!$apiKey) {
            //throw new BadCredentialsException('No API key found');
            // or to just skip api key authentication
            return null;
        }

        $token = new PreAuthenticatedToken(
            'anon.',
            $apiKey,
            $providerKey
        );

        $token->setAttribute('apiKey', true);

        return $token;
    }

    /**
     * @param TokenInterface $token
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     *
     * @return PreAuthenticatedToken
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        return $this->authenticateApiKeyToken($token, $userProvider, $providerKey);
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param                                                                      $providerKey
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken
     * @throws \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    protected function authenticateApiKeyToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $event = new ApiAuthenticateEvent();

            if (null !== $token->getCredentials()) {
                $event->setApiKey($token->getCredentials());
            } elseif ($token->getUser() instanceof User) {
                $event->setApiKey($token->getUser()->getApiKey());
            }

            $this->dispatcher->dispatch(ApiAuthenticationEvents::API_KEY_AUTHENTICATE, $event);

            $user = $userProvider->loadUserByUsername($token->getCredentials());
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException($e->getMessage());
        }

        return new PreAuthenticatedToken($user, $token->getCredentials(), $providerKey, $user->getRoles());
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}
