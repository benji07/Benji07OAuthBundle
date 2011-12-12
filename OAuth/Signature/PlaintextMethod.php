<?php

namespace Benji07\Bundle\OAuthBundle\OAuth\Signature;

use Benji07\Bundle\OAuthBundle\OAuth\Request,
    Benji07\Bundle\OAuthBundle\OAuth\Consumer,
    Benji07\Bundle\OAuthBundle\OAuth\Token;

/**
 * The PLAINTEXT method does not provide any security protection and SHOULD only be used 
 * over a secure channel such as HTTPS. It does not use the Signature Base String.
 *   - Chapter 9.4 ("PLAINTEXT")
 */
class PlaintextMethod extends AbstractMethod
{
    /**
     * Needs to return the name of the Signature Method (ie HMAC-SHA1)
     *
     * @return string
     */
    public function getName()
    {
        return "PLAINTEXT";
    }

    /**
     * oauth_signature is set to the concatenated encoded values of the Consumer Secret and 
     * Token Secret, separated by a '&' character (ASCII code 38), even if either secret is 
     * empty. The result MUST be encoded again.
     *   - Chapter 9.4.1 ("Generating Signatures")
     *
     * Please note that the second encoding MUST NOT happen in the SignatureMethod, as
     * OAuthRequest handles this!
     *
     * @param Request  $request  request
     * @param Consumer $consumer consumer
     * @param Token    $token    token
     *
     * @return string
     */
    public function buildSignature(Request $request, Consumer $consumer, Token $token = null)
    {
        $keyParts = array($consumer->secret, ($token) ? $token->secret : "");

        $keyParts = OAuthUtil::urlencodeRfc3986($keyParts);
        $key = implode('&', $keyParts);
        $request->baseString = $key;

        return $key;
    }
}