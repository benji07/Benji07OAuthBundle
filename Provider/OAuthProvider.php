<?php

namespace Benji07\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

use Buzz\Browser;

/**
 * Base OAuth 2.0 Provider
 */
abstract class OAuthProvider
{
    protected $clientId;

    protected $secretId;

    protected $browser;

    /**
     * __construct
     *
     * @param string $clientId the client id
     * @param string $secretId the secret id
     */
    public function __construct($clientId, $secretId)
    {
        $this->clientId = $clientId;
        $this->secretId = $secretId;
    }

    /**
     * Set Browser
     *
     * @param Browser $browser the browser
     */
    public function setBrowser(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Get Authorize uri
     *
     * @param Request $request     the request
     * @param string  $redirectUri the uri
     *
     * @return string
     */
    abstract public function getAuthorizeUri(Request $request, $redirectUri);

    /**
     * Get Access Token
     *
     * @param Request $request     the request
     * @param string  $redirectUri the uri
     *
     * @return string|array
     */
    abstract public function getAccessToken(Request $request, $redirectUri);

    /**
     * Get Name
     *
     * @return string
     */
    abstract public function getName();
}