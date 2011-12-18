<?php

namespace Benji07\Bundle\OAuthBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * The OAuth Token
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
class OAuthToken extends AbstractToken
{

    /**
     * __construct
     *
     * @param mixed $uid   the user token
     * @param array $roles the user roles
     */
    public function __construct($uid = '', array $roles = array())
    {
        parent::__construct($roles);

        $this->setUser($uid);

        if (!empty($uid)) {
            $this->setAuthenticated(true);
        }
    }

    /**
     * get Credentials
     *
     * @return string
     */
    public function getCredentials()
    {
        return '';
    }

}