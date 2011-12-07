# Bundle OAuth

## Step 1: Download Benji07OAuthBundle

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
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Benji07' => __DIR__.'/../vendor/bundles',
));
```

### Step 3: Enable the bundle

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\UserBundle\FOSUserBundle(),
    );
}
```
### Step 4: Add providers class

### Step 5: Configure your application's security.yml

### Step 6: Configure the Benji07OAuthBundle