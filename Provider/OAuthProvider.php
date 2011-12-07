<?php

namespace Benji07\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

use Buzz\Browser;

abstract class OAuthProvider
{
    protected $clientId;

    protected $secretId;

    protected $browser;

    public function __construct($clientId, $secretId)
    {
        $this->clientId = $clientId;
        $this->secretId = $secretId;
    }

    public function setBrowser(Browser $browser)
    {
        $this->browser = $browser;
    }

    abstract public function getAuthorizeUri(Request $request, $redirectUri);

    abstract public function getAccessToken(Request $request, $redirectUri);

    abstract public function getName();
}