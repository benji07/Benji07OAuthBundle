<?php

namespace Benji07\Bundle\OAuthBundle\Security\Firewall;

use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;

use Benji07\Bundle\OAuthBundle\Security\Authentication\Token\OAuthToken;

/**
 * Security OAuth Listener
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
class OAuthListener extends AbstractAuthenticationListener
{
    /**
     * Attempt Authentication
     *
     * @param Request $request the request
     *
     * @return OAuthToken|Response
     */
    public function attemptAuthentication(Request $request)
    {
        return $this->authenticationManager->authenticate(new OAuthToken);
    }
}