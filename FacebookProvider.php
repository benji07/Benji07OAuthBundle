<?php

namespace Benji07\Bundle\OAuthBundle;

class FacebookProvider extends OAuth2Provider
{

    protected $authorizeUri = 'https://www.facebook.com/dialog/oauth';

    protected $accessTokenUri = 'https://graph.facebook.com/oauth/access_token';

    public function getName()
    {
        return 'facebook';
    }
}