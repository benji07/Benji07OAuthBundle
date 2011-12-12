<?php

namespace Benji07\Bundle\OAuthBundle\OAuth;

/**
 * Request
 */
class Request
{
    protected $parameters;
    protected $httpMethod;
    protected $httpUrl;

    // for debug purposes
    public $baseString;

    /**
     * __construct
     *
     * @param string $httpMethod httpMethod
     * @param string $httpUrl    httpUrl
     * @param array  $parameters parameters
     */
    public function __construct($httpMethod, $httpUrl, $parameters = null)
    {
        $parameters = ($parameters) ? $parameters : array();
        $parameters = array_merge(Utils::parseParameters(parse_url($httpUrl, PHP_URL_QUERY)), $parameters);
        $this->parameters = $parameters;
        $this->httpMethod = $httpMethod;
        $this->httpUrl = $httpUrl;
    }

    /**
     * pretty much a helper function to set up the request
     *
     * @param Consumer $consumer   consumer
     * @param Token    $token      token
     * @param string   $httpMethod httpMethod
     * @param string   $httpUrl    httpUrl
     * @param array    $parameters parameters
     *
     * @return Request
    */
    public static function fromConsumerAndToken($consumer, $token, $httpMethod, $httpUrl, array $parameters = array())
    {

        $defaults = array("oauth_version" => '1.0',
            "oauth_nonce" => md5(microtime() . mt_rand()),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $consumer->key);

        if ($token) {
            $defaults['oauth_token'] = $token->key;
        }

        $parameters = array_merge($defaults, $parameters);

        return new Request($httpMethod, $httpUrl, $parameters);
    }

    /**
     * Set parameter
     *
     * @param string  $name            name
     * @param string  $value           value
     * @param boolean $allowDuplicates allowDuplicates
     */
    public function setParameter($name, $value, $allowDuplicates = true)
    {
        if ($allowDuplicates && isset($this->parameters[$name])) {
            // We have already added parameter(s) with this name, so add to the list
            if (is_scalar($this->parameters[$name])) {
                // This is the first duplicate, so transform scalar (string)
                // into an array so we can add the duplicates
                $this->parameters[$name] = array($this->parameters[$name]);
            }

            $this->parameters[$name][] = $value;
        } else {
            $this->parameters[$name] = $value;
        }
    }

    /**
     * Get parameter
     *
     * @param string $name name
     *
     * @return string
     */
    public function getParameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Unset parameter
     *
     * @param string $name name
     */
    public function unsetParameter($name)
    {
        unset($this->parameters[$name]);
    }

    /**
     * The request parameters, sorted and concatenated into a normalized string.
     *
     * @return string
     */
    public function getSignableParameters()
    {
        // Grab all parameters
        $params = $this->parameters;

        // Remove oauth_signature if present
        // Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
        if (isset($params['oauth_signature'])) {
            unset($params['oauth_signature']);
        }

        return Utils::buildHttpQuery($params);
    }

    /**
     * Returns the base string of this request
     *
     * The base string defined as the method, the url
     * and the parameters (normalized), each urlencoded
     * and the concated with &.
     *
     * @return string
     */
    public function getSignatureBaseString()
    {
        $parts = array(
            $this->getNormalizedHttpMethod(),
            $this->getNormalizedHttpUrl(),
            $this->getSignableParameters()
        );

        $parts = Utils::urlencodeRfc3986($parts);

        return implode('&', $parts);
    }

    /**
     * just uppercases the http method
     *
     * @return string
     */
    public function getNormalizedHttpMethod()
    {
        return strtoupper($this->httpMethod);
    }

    /**
     * parses the url and rebuilds it to be
     * scheme://host/path
     *
     * @return string
     */
    public function getNormalizedHttpUrl()
    {
        $parts = parse_url($this->httpUrl);

        $scheme = (isset($parts['scheme'])) ? $parts['scheme'] : 'http';
        $port = (isset($parts['port'])) ? $parts['port'] : (($scheme == 'https') ? '443' : '80');
        $host = (isset($parts['host'])) ? strtolower($parts['host']) : '';
        $path = (isset($parts['path'])) ? $parts['path'] : '';

        if (($scheme == 'https' && $port != '443') || ($scheme == 'http' && $port != '80')) {
            $host = "$host:$port";
        }
        return "$scheme://$host$path";
    }

    /**
    * builds a url usable for a GET request
    *
    * @return string
    */
    public function toUrl()
    {
        $postData = $this->toPostdata();
        $out = $this->getNormalizedHttpUrl();
        if ($postData) {
            $out .= '?'.$postPata;
        }
        return $out;
    }

    /**
     * builds the data one would send in a POST request
     *
     * @return string
     */
    public function toPostdata()
    {
        return Utils::buildHttpQuery($this->parameters);
    }

    /**
     * builds the Authorization: header
     *
     * @param string $realm realm
     *
     * @return string
     */
    public function toHeader($realm=null)
    {
        $first = true;
        if ($realm) {
            $out = 'OAuth realm="' . Utils::urlencodeRfc3986($realm) . '"';
            $first = false;
        } else {
            $out = 'OAuth';
        }

        $total = array();
        foreach ($this->parameters as $k => $v) {
            if (substr($k, 0, 5) != "oauth") {
                continue;
            }
            if (is_array($v)) {
                throw new \Exception('Arrays not supported in headers');
            }

            $out .= ($first) ? ' ' : ',';
            $out .= Utils::urlencodeRfc3986($k) . '="' . Utils::urlencodeRfc3986($v) . '"';
            $first = false;
        }

        return $out;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toUrl();
    }

    /**
     * Sign request
     *
     * @param Signature\AbstractMethod $signatureMethod signatureMethod
     * @param Consumer                 $consumer        consumer
     * @param Token                    $token           token
     */
    public function signRequest(Signature\AbstractMethod $signatureMethod, Consumer $consumer, Token $token = null)
    {
        $this->setParameter("oauth_signature_method", $signatureMethod->getName(), false);

        $signature = $this->buildSignature($signatureMethod, $consumer, $token);

        $this->setParameter("oauth_signature", $signature, false);
    }

    /**
     * buildSignature
     *
     * @param Signature\AbstractMethod $signatureMethod signatureMethod
     * @param Consumer                 $consumer        consumer
     * @param Token                    $token           token
     *
     * @return string
     */
    public function buildSignature(Signature\AbstractMethod $signatureMethod, Consumer $consumer, Token $token = null)
    {
        $signature = $signatureMethod->buildSignature($this, $consumer, $token);
        return $signature;
    }

}