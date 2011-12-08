<?php

namespace Benji07\Bundle\OAuthBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Benji07\Bundle\OAuthBundle\Security\Authentication\Token\OAuthToken;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Benji07\Bundle\OAuthBundle\OAuthManager;

use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\DependencyInjection\Container;



/**
 * Security OAuth Provider
 */
class OAuthProvider implements AuthenticationProviderInterface
{
    protected $request;

    protected $manager;

    /**
     * __construct
     *
     * @param OAuthManager $manager   the oauth maanger
     * @param Container    $container container
     */
    public function __construct(OAuthManager $manager, $container)
    {
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * Get Request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * Authenticate
     *
     * @param TokenInterface $token the token
     *
     * @return TokenInterface|Response
     */
    public function authenticate(TokenInterface $token)
    {
        if (false === $this->supports($token)) {
            return null;
        }

        $name = $this->getRequest()->query->get('name');

        $provider = $this->manager->getProvider($name);

        if (null == $provider) {
            throw new \RuntimeException(strtr('The provider %name% does not exist', array('%name%' => $name)));
        }

        $token = null;

        try {
            $token = $provider->getAccessToken($this->getRequest(), $this->getRequest()->query->get('referer'));
        } catch (\Exception $e) {
            // user denied
            return null;
        }

        // find user
        $user = $this->manager->getUserManager()->findUser($provider, $token);

        if (null === $user) {
            // if user doesn't exist throw exception
            throw new UsernameNotFoundException('You don\'t have an account on this application.');
        }

        if (!$user instanceof UserInterface) {
            throw new \RuntimeException('User provider did not return an implementation of user interface.');
        }

        return new OAuthToken($user, $user->getRoles());
    }

    /**
     * Supports
     *
     * @param TokenInterface $token the token
     *
     * @return boolean
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof OAuthToken;
    }
}