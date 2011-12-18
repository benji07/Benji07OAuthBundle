<?php

namespace Benji07\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

use Buzz\Browser;

/**
 * OAuth 2.0 Provider
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
abstract class OAuth2Provider extends OAuthProvider
{
    protected $scope;

    protected $authorizeUri;

    protected $accessTokenUri;

    /**
     * Set scope
     *
     * @param string $scope the user scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

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
        return $this->authorizeUri.'?'.http_build_query(array(
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $this->scope));
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
        if ($request->query->has('error')) {
            throw new \Exception(strtr('We could not link your %provider% account', array('%name%' => $this->getName())));
        }

        $code = $request->query->get('code');

        $response = $this->browser->get($this->accessTokenUri . '?'. http_build_query(array(
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'client_secret' => $this->secretId,
            'code' => $code
        )));

        parse_str($response->getContent(), $data);

        if (isset($data['error'])) {
            throw new \Exception($data['error']);
        }

        return $data['access_token'];
    }
}