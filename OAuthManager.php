<?php

namespace Benji07\Bundle\OAuthBundle;

use Buzz\Browser;

use Benji07\Bundle\OAuthBundle\Provider\OAuthProvider;

use Benji07\Bundle\OAuthBundle\UserManager\UserManagerInterface;

/**
 * OAuth Manager
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
class OAuthManager
{
    private $providers = array();

    private $browser;

    private $userManager;

    /**
     * __construct
     *
     * @param Browser              $browser     a browser
     * @param UserManagerInterface $userManager a user manager
     */
    public function __construct(Browser $browser, UserManagerInterface $userManager)
    {
        $this->browser = $browser;
        $this->userManager = $userManager;
    }

    /**
     * Add OAuth Provider
     *
     * @param OAuthProvider $provider a provider
     */
    public function addProvider(OAuthProvider $provider)
    {
        $this->providers[$provider->getName()] = $provider;

        $provider->setBrowser($this->browser);
    }

    /**
     * Get OAuth Provider
     *
     * @param string $name the provider name
     *
     * @return OAuthProvider|null
     */
    public function getProvider($name)
    {
        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }

        throw new \Exception(strtr('The provider %name% does not exist', array('%name%' => $name)));
    }

    /**
     * Get User Manager
     *
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
}