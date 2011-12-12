<?php

namespace Benji07\Bundle\OAuthBundle\OAuth\Signature;

use Benji07\Bundle\OAuthBundle\OAuth\Request,
    Benji07\Bundle\OAuthBundle\OAuth\Consumer,
    Benji07\Bundle\OAuthBundle\OAuth\Token,
    Benji07\Bundle\OAuthBundle\OAuth\Utils;

/**
 * The HMAC-SHA1 signature method uses the HMAC-SHA1 signature algorithm as defined in [RFC2104] 
 * where the Signature Base String is the text and the key is the concatenated values (each first 
 * encoded per Parameter Encoding) of the Consumer Secret and Token Secret, separated by an '&' 
 * character (ASCII code 38) even if empty.
 *   - Chapter 9.2 ("HMAC-SHA1")
*/
class HMACSHA1Method extends AbstractMethod
{
    /**
     * Needs to return the name of the Signature Method (ie HMAC-SHA1)
     *
     * @return string
     */
    public function getName()
    {
        return "HMAC-SHA1";
    }

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
    public function buildSignature(Request $request, Consumer $consumer, Token $token = null)
    {
        $baseString = $request->getSignatureBaseString();
        $request->baseString = $baseString;

        $keyParts = array($consumer->secret, ($token) ? $token->secret : "");

        $keyParts = Utils::urlencodeRfc3986($keyParts);
        $key = implode('&', $keyParts);

        return base64_encode(hash_hmac('sha1', $baseString, $key, true));
    }
}