<?php

namespace Benji07\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

use Buzz\Browser;

use Benji07\Bundle\OAuthBundle\OAuth\Request as OAuthRequest;

use Benji07\Bundle\OAuthBundle\OAuth\Consumer;
use Benji07\Bundle\OAuthBundle\OAuth\Token;

use Benji07\Bundle\OAuthBundle\OAuth\Signature\HMACSHA1Method;

/**
 * OAuth 1.0a Provider
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
abstract class OAuth1aProvider extends OAuthProvider
{

    protected $requestTokenUrl;

    protected $authorizeUrl;

    protected $accessTokenUrl;

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

        $request->getSession()->set('oauth.'.$this->getName().'.token', $token);

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
        if ($request->query->has('oauth_problem')) {
            throw new \Exception($request->query->get('oauth_problem'));
        }
        $token = $request->getSession()->get('oauth.'.$this->getName().'.token', array());
        $verifier = $request->query->get('oauth_verifier');

        $result = $this->post($this->accessTokenUrl, array('oauth_verifier' => $verifier), $token);

        parse_str($result, $data);

        return $data;
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
        $result = $this->post($this->requestTokenUrl, array('oauth_callback' => $redirectUri));

        parse_str($result, $data);

        return $data;
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
            $this->getName() . 'Secret'
        );
    }

    /**
     * Get data from OAuth provider
     *
     * @param string $url    url
     * @param array  $params the query string
     * @param array  $token  the usr token
     *
     * @return string
     */
    public function get($url, array $params = array(), $token = null)
    {
        $request = $this->prepareRequest($url, 'GET', $params, $token);

        $response = $this->browser->get($request->getUrl());

        return $response->getContent();
    }

    /**
     * Post data to OAuth provider
     *
     * @param string $url    url
     * @param array  $params the query string
     * @param array  $token  the usr token
     *
     * @return string
     */
    public function post($url, array $params = array(), $token = null)
    {
        $request = $this->prepareRequest($url, 'POST', $params, $token);

        $response = $this->browser->post($request->getNormalizedHttpUrl(), array(), $request->toPostdata());

        return $response->getContent();
    }

    /**
     * Prepare request
     *
     * @param string $url    url
     * @param string $method method
     * @param array  $params params
     * @param array  $token  token
     *
     * @return OAuthRequest
     */
    protected function prepareRequest($url, $method, array $params = array(), $token = null)
    {
        $consumer = new Consumer($this->clientId, $this->secretId);

        if ($token) {
            $token = new Token($token['oauth_token'], $token['oauth_token_secret']);
        }

        $request = OAuthRequest::fromConsumerAndToken($consumer, $token, $method, $url, $params);

        $request->signRequest(new HMACSHA1Method(), $consumer, $token);

        return $request;
    }
}