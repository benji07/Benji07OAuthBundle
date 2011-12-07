<?php

namespace Benji07\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

/**
 * OAuth 1.0a Provider
 */
abstract class OAuth1aProvider extends OAuthProvider
{

    public $requestTokenUrl;

    public $authorizeUrl;

    public $accessTokenUrl;

    /**
     * Get Authorize uri
     *
     * @param Request $request     the request
     * @param string  $redirectUri the uri
     *
     * @return string
     */
    public function getAuthorizeUri(Request $request, $redirectUri)
    {
        $token = $this->getRequestToken($redirectUri);

        return $this->authorizeUrl.'?'.http_build_query(array('oauth_token' => $token['oauth_token']));
    }

    /**
     * Get Access Token
     *
     * @param Request $request     the request
     * @param string  $redirectUri the uri
     *
     * @return string|array
     */
    public function getAccessToken(Request $request, $redirectUri)
    {
    }

    /**
     * Get Request Token
     *
     * @param string $redirectUri the uri
     *
     * @return array
     */
    public function getRequestToken($redirectUri)
    {
    }

    /**
     * Get columns to persist in the user account
     *
     * @return array
     */
    public function getColumns()
    {
        return array(
            $this->getIdColumn(),
            $this->getName() . '_secret'
        );
    }
}