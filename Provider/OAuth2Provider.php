<?php

namespace Benji07\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;

use Buzz\Browser;

class OAuth2Provider extends OAuthProvider
{
    protected $scope;

    protected $authorizeUri;

    protected $accessTokenUri;
    
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function getAuthorizeUri(Request $request, $redirectUri)
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