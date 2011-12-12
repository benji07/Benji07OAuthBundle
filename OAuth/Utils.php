<?php

namespace Benji07\Bundle\OAuthBundle\OAuth;

/**
 * Utils
 */
class Utils
{
    /**
     * urlencode rfc3986
     *
     * @param string $input input
     *
     * @return string
     */
    public static function urlencodeRfc3986($input)
    {
        if (is_array($input)) {
            return array_map(array(__CLASS__, 'urlencodeRfc3986'), $input);
        } else if (is_scalar($input)) {
            return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
        } else {
            return '';
        }
    }

    /**
     * This function takes a input like a=b&a=c&d=e and returns the parsed parameters like this
     * array('a' => array('b','c'), 'd' => 'e')
     *
     * @param array $input input
     *
     * @return array
     */
    public static function parseParameters($input)
    {
        parse_str($input, $result);
        return $result;
    }

    /**
     * buildHttpQuery
     *
     * @param array $params params
     *
     * @return string
     */
    public static function buildHttpQuery($params)
    {
        if (!$params) {
            return '';
        }

        // Urlencode both keys and values
        $keys = Utils::urlencodeRfc3986(array_keys($params));
        $values = Utils::urlencodeRfc3986(array_values($params));
        $params = array_combine($keys, $values);

        // Parameters are sorted by name, using lexicographical byte value ordering.
        // Ref: Spec: 9.1.1 (1)
        uksort($params, 'strcmp');

        $pairs = array();
        foreach ($params as $parameter => $value) {
            if (is_array($value)) {
                // If two or more parameters share the same name, they are sorted by their value
                // Ref: Spec: 9.1.1 (1)
                // June 12th, 2010 - changed to sort because of issue 164 by hidetaka
                sort($value, SORT_STRING);
                foreach ($value as $duplicateValue) {
                    $pairs[] = $parameter . '=' . $duplicateValue;
                }
            } else {
                $pairs[] = $parameter . '=' . $value;
            }
        }
        // For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
        // Each name-value pair is separated by an '&' character (ASCII code 38)
        return implode('&', $pairs);
    }
}