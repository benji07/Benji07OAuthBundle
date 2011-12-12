<?php

namespace Benji07\Bundle\OAuthBundle\OAuth;

/**
 * Token
 */
class Token
{
    public $key;
    public $secret;

    /**
     * key = the token
     * secret = the token secret
     *
     * @param string $key    key
     * @param string $secret secret
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * generates the basic string serialization of a token that a server
     * would respond to request_token and access_token calls with
     *
     * @return string
     */
    public function __toString()
    {
        return "oauth_token=" . Utils::urlencodeRfc3986($this->key)
            . "&oauth_token_secret=" . Utils::urlencodeRfc3986($this->secret);
    }

}