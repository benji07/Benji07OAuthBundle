<?php

namespace Benji07\Bundle\OAuthBundle;

use Symfony\Component\HttpFoundation\Request;

use Buzz\Browser;

class OAuth2Provider
{
    protected $clientId;

    protected $secretId;

    protected $scope;

    protected $authorizeUri;

    protected $accessTokenUri;

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

    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function getAuthorizeUri($redirectUri)
    {
        return $this->authorizeUri.'?'.http_build_query(array(
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $this->scope));
    }

    public function getAccessToken(Request $request, $redirectUri)
    {
        if ($request->query->has('error')) {
            throw new Exception('We could not link your %provider% account');
        }

        $code = $request->query->get('code');

        $response = $this->browser->get($this->accessTokenUri . '?'. http_build_query(array(
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'client_secret' => $this->secretId,
            'code' => $code
        )));

        parse_str($response->getContent(), $data);

        return $data['access_token'];
    }
}