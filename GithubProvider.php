<?php

namespace Benji07\Bundle\OAuthBundle;

class GithubProvider extends OAuth2Provider
{
    protected $authorizeUri = 'https://github.com/login/oauth/authorize';

    protected $accessTokenUri = 'https://github.com/login/oauth/access_token';

    public function getName()
    {
        return 'github';
    }
}