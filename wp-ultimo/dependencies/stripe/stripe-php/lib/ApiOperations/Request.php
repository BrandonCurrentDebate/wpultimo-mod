<?php

namespace WP_Ultimo\Dependencies\Stripe\ApiOperations;

/**
 * Trait for resources that need to make API requests.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait Request
{
    /**
     * @param null|array|mixed $params The list of parameters to validate
     *
     * @throws \Stripe\Exception\InvalidArgumentException if $params exists and is not an array
     */
    protected static function _validateParams($params = null)
    {
        if ($params && !\is_array($params)) {
            $message = 'You must pass an array as the first argument to Stripe API ' . 'method calls.  (HINT: an example call to create a charge ' . "would be: \"Stripe\\Charge::create(['amount' => 100, " . "'currency' => 'usd', 'source' => 'tok_1234'])\")";
            throw new \WP_Ultimo\Dependencies\Stripe\Exception\InvalidArgumentException($message);
        }
    }
    /**
     * @param 'delete'|'get'|'post' $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param array $params list of parameters for the request
     * @param null|array|string $options
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return array tuple containing (the JSON response, $options)
     */
    protected function _request($method, $url, $params = [], $options = null)
    {
        $opts = $this->_opts->merge($options);
        list($resp, $options) = static::_staticRequest($method, $url, $params, $opts);
        $this->setLastResponse($resp);
        return [$resp->json, $options];
    }
    /**
     * @param 'delete'|'get'|'post' $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param callable $readBodyChunk function that will receive chunks of data from a successful request body
     * @param array $params list of parameters for the request
     * @param null|array|string $options
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    protected function _requestStream($method, $url, $readBodyChunk, $params = [], $options = null)
    {
        $opts = $this->_opts->merge($options);
        static::_staticStreamingRequest($method, $url, $readBodyChunk, $params, $opts);
    }
    /**
     * @param 'delete'|'get'|'post' $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param array $params list of parameters for the request
     * @param null|array|string $options
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     *
     * @return array tuple containing (the JSON response, $options)
     */
    protected static function _staticRequest($method, $url, $params, $options)
    {
        $opts = \WP_Ultimo\Dependencies\Stripe\Util\RequestOptions::parse($options);
        $baseUrl = isset($opts->apiBase) ? $opts->apiBase : static::baseUrl();
        $requestor = new \WP_Ultimo\Dependencies\Stripe\ApiRequestor($opts->apiKey, $baseUrl);
        list($response, $opts->apiKey) = $requestor->request($method, $url, $params, $opts->headers);
        $opts->discardNonPersistentHeaders();
        return [$response, $opts];
    }
    /**
     * @param 'delete'|'get'|'post' $method HTTP method ('get', 'post', etc.)
     * @param string $url URL for the request
     * @param callable $readBodyChunk function that will receive chunks of data from a successful request body
     * @param array $params list of parameters for the request
     * @param null|array|string $options
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    protected static function _staticStreamingRequest($method, $url, $readBodyChunk, $params, $options)
    {
        $opts = \WP_Ultimo\Dependencies\Stripe\Util\RequestOptions::parse($options);
        $baseUrl = isset($opts->apiBase) ? $opts->apiBase : static::baseUrl();
        $requestor = new \WP_Ultimo\Dependencies\Stripe\ApiRequestor($opts->apiKey, $baseUrl);
        $requestor->requestStream($method, $url, $readBodyChunk, $params, $opts->headers);
    }
}
