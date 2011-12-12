<?php

namespace Benji07\Bundle\OAuthBundle\OAuth\Signature;

use Benji07\Bundle\OAuthBundle\OAuth\Request,
    Benji07\Bundle\OAuthBundle\OAuth\Consumer,
    Benji07\Bundle\OAuthBundle\OAuth\Token;

/**
 * Signature AbstractMethod
 */
abstract class AbstractMethod
{
    /**
     * Needs to return the name of the Signature Method (ie HMAC-SHA1)
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Build up the signature
     * NOTE: The output of this function MUST NOT be urlencoded.
     * the encoding is handled in OAuthRequest when the final
     * request is serialized
     *
     * @param Request  $request  request
     * @param Consumer $consumer consumer
     * @param Token    $token    token
     *
     * @return string
     */
    abstract public function buildSignature(Request $request, Consumer $consumer, Token $token = null);

    /**
     * Verifies that a given signature is correct
     *
     * @param Request  $request   request
     * @param Consumer $consumer  consumer
     * @param Token    $token     token
     * @param string   $signature signature
     *
     * @return bool
     */
    public function checkSignature(Request $request, Consumer $consumer, Token $token = null, $signature = '')
    {
        $built = $this->buildSignature($request, $consumer, $token);

        // Check for zero length, although unlikely here
        if (strlen($built) == 0 || strlen($signature) == 0) {
            return false;
        }

        if (strlen($built) != strlen($signature)) {
            return false;
        }

        // Avoid a timing leak with a (hopefully) time insensitive compare
        $result = 0;
        for ($i = 0; $i < strlen($signature); $i++) {
            $result |= ord($built{$i}) ^ ord($signature{$i});
        }

        return $result == 0;
    }
}