<?php

namespace Benji07\Bundle\OAuthBundle\Provider;

class OAuth1aProvider extends OAuthProvider
{

    public $requestTokenUrl;

    public $authorizeUrl;

    public $accessTokenUrl;

    public function getAuthorizeUri(Request $request, $redirectUri)
    {
        $token = $this->getRequestToken($redirectUri);

        return $this->authorizeUrl.'?'.http_build_query(array('oauth_token' => $token))
    }

    public function getAccessToken(Request $request, $redirectUri)
    {
        
    }

    public function getRequestToken($redirectUri)
    {
        
    }
}