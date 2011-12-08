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
### Step 4: Add providers class

Examples providers are available in bundle Benji07OAuthGithubBundle, Benji07OAuthFacebookBundle and Benji07OAuthTwiterBundle

### Step 5: Configure your application's security.yml

### Step 6: Configure the Benji07OAuthBundle

```
benji07_o_auth:
    user_manager:
        id: benji07.oauth.usermanager.doctrine_orm
        options:
            class: Acme\UserBundle\Entity\User
```