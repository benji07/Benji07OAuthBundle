<?php

namespace Benji07\Bundle\OAuthBundle;

use Buzz\Browser;

class OAuthManager
{
    private $providers = array();

    private $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function addProvider(OAuth2Provider $provider)
    {
        $this->providers[$provider->getName()] = $provider;

        $provider->setBrowser($this->browser);
    }

    public function getProvider($name)
    {
        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }
        
        return null;
    }
}