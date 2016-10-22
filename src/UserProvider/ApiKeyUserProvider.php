<?php

namespace Damijanc\SecurityBundle\UserProvider;

use Damijanc\SecurityBundle\Constants\AuthenticationConstants;
use Damijanc\SecurityBundle\User\User;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class ApiKeyUserProvider
 * @package Damijanc\SecurityBundle\UserProvider
 * @Service("damijanc_api_key_user_provider")
 */
class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @InjectParams({
     *      "session" = @Inject("session")
     * })
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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

        throw new UsernameNotFoundException('Invalid user token.');
    }

    /**
     * @param UserInterface $user
     *
     * @return mixed
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
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
