<?php

namespace Benji07\Bundle\OAuthBundle\OAuth;

/**
 * OAuth Consumer
 */
class Consumer
{
    public $key;
    public $secret;

    public $callbackUrl;

    /**
     * __construct
     *
     * @param string $key         key
     * @param string $secret      secret
     * @param string $callbackUrl callback
     */
    public function __construct($key, $secret, $callbackUrl = null)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }
}