<?php

namespace Damijanc\SecurityBundle\UserProvider;

use Damijanc\SecurityBundle\Constants\AuthenticationConstants;
use Damijanc\SecurityBundle\User\User;
use JMS\DiExtraBundle\Annotation as JMS;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @JMS\Service("damijanc_user_provider")
 * Class LoginUserProvider
 * @package Damijanc\SecurityBundle\Security
 */
class LoginUserProvider implements UserProviderInterface
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
     * @JMS\InjectParams({
     *      "session" = @JMS\Inject("session"),
     *      "dispatcher" = @JMS\Inject("event_dispatcher")
     * })
     * @param SessionInterface $session
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(SessionInterface $session, EventDispatcherInterface $dispatcher)
    {
        $this->session = $session;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $username
     *
     * @return mixed
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->session->get(AuthenticationConstants::SECURITY_TOKEN_NAME);

        if ($user instanceof User) {
            return $user;
        }

        $authenticationError = $this->session->get(Security::AUTHENTICATION_ERROR);

        if ($authenticationError instanceof AuthenticationException) {
            throw new UsernameNotFoundException($authenticationError->getMessage());
        }

        throw new UsernameNotFoundException(
            sprintf('User %s was not authenticated by API.', $username)
        );
    }

    /**
     * @param UserInterface $user
     *
     * @return mixed|UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     * @internal param bool $reauthenticate
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
