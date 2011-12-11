# Bundle Benji07OAuthBundle

This bundle is **Work in progress**

Currently this bundle only Support OAuth2.0

With this bundle, your user can login, link and unlink they account.

This bundle is only compatible with Doctrine ORM

## Installation

### Step 1: Download Benji07OAuthBundle

Add the following lines in your `deps` file:

```
[Benji07OAuthBundle]
git=git://github.com/Benji07/Benji07OAuthBundle.git
target=bundles/Benji07/Bundle/OAuthBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

### Step 2: Configure the Autoloader

Add the `Benji07` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Benji07' => __DIR__.'/../vendor/bundles',
));
```

### Step 3: Enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Benji07\Bundle\OAuthBundle\Benji07OAuthBundle(),
    );
}
```

### Step 4: Configure your application's security.yml

```yml
security:

    factories:
        - %kernel.root_dir%/../vendors/bundles/Benji07/Bundle/OAuthBundle/Resources/config/security_factories.xml

    firewalls:
        main:
            pattern: ^/
            oauth:
                check_path: /oauth/secure
```

You can add a failure handler if you want to create user if none match the access token.

### Step 5: Configure the Benji07OAuthBundle

```
benji07_o_auth:
    user_manager:
        id: benji07.oauth.usermanager.doctrine_orm
        options:
            class: Acme\UserBundle\Entity\User
```

### Step 6: Create or add providere

```php
<?php

namespace Benji07\Bundle\OAuthGithubBundle\Provider;

use Benji07\Bundle\OAuthBundle\Provider\OAuth2Provider;

use Benji07\Bundle\OAuthGithubBundle\Api\GithubApi;

class GithubProvider extends OAuth2Provider
{
    protected $authorizeUri = 'https://github.com/login/oauth/authorize';

    protected $accessTokenUri = 'https://github.com/login/oauth/access_token';

    public function getName()
    {
        return 'github';
    }
}
```

Register the provider using the tag `benji07.oauth.provider`

```xml
<service id="benji07.oauth.provider.github" class="%benji07.oauth.provider.github.class%">
    <tag name="benji07.oauth.provider"/>
    <argument>%benji07.oauth.provider.github.key%</argument>
    <argument>%benji07.oauth.provider.github.secret%</argument>
</service>
```

### Step 7 Add columns for each provider you register

For OAuth2 Providers you need to add one column `providerNameToken`, for version 1.0a you need to add 2 column `providerNameToken` and `providerNameSecret`