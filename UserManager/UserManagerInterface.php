<?php

namespace Benji07\Bundle\OAuthBundle\UserManager;

use Benji07\Bundle\OAuthBundle\Provider\OAuthProvider;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserManager Interface
 */
interface UserManagerInterface
{
    /**
     * Find a user
     *
     * @param OAuthProvider $provider the provider
     * @param string|array  $token    the token
     *
     * @return UserInterface|null
     */
    function findUser(OAuthProvider $provider, $token);

    /**
     * Link a user to a provider
     *
     * @param OAuthProvider $provider the provider
     * @param UserInterface $user     the user
     * @param string|array  $token    the token
     *
     * @throw \Exception if we can't link the user
     */
    function link(OAuthProvider $provider, UserInterface $user, $token);

    /**
     * Unlink a user with  a provider
     *
     * @param OAuthProvider $provider the provider
     * @param UserInterface $user     the user
     *
     * @throw \Exception if we can't unlink the user
     */
    function unlink(OAuthProvider $provider, UserInterface $user);
}