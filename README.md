# Bundle OAuth

Provider:

- Github (Benji07OAuthFacebookBundle)
- Facebook (Benji07OAuthFacebookBundle)


## Installation

OauthBundle
OAuthManager
OAuthProvider
OAuthFacebookProvider

Séparer le code en plusieurs bundle
1 par provider. + 1 principal

Méthode à implémenter :
- login
- link
- unlink
- register (avec gestion d'une étape)
- gestion des permissions (request permission)


manager::register(provider)

gestion de la persistance

http://tools.ietf.org/pdf/draft-ietf-oauth-v2-22.pdf

Architecture:
- manager
- provider (twitter, github,...)
- consumer (buzz,...)
- persister(orm, odm, propel)
- user interface

manager->get(provider)->requestToken(URL)
->accessToken


Github (OAuth2)

client_id = 6d232f2c63e383cc52a0
secret = 51a0f83bb91d6e60cac83bae35974f825fb8b5df

authorize url = https://github.com/login/oauth/authorize
access token = https://github.com/login/oauth/access_token

Workflow:

provider->getAuthorizeURL()

Exemple:

<?php

namespace Benji07\Bundle\OAuthGithubBundle\Provider;

use Benji07\Bundle\OAuthBundle\Provider\OAuth2Provider

class GithubProvider extends OAuth2Provider
{
    protected $authorizeUri = 'https://github.com/login/oauth/authorize';

    protected $accessTokenUri = 'https://github.com/login/oauth/access_token';

    public function getName()
    {
        return 'github';
    }
}