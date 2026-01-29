<?php

namespace MeteoFlow;

use MeteoFlow\Exception\ValidationException;

/**
 * Configuration for the MeteoFlow Weather API client.
 *
 * This class is immutable. Use the with* methods to create modified copies.
 */
class ClientConfig
{
    const DEFAULT_BASE_URL = 'https://api.meteoflow.com';
    const DEFAULT_TIMEOUT = 10;
    const DEFAULT_CONNECT_TIMEOUT = 5;
    const DEFAULT_USER_AGENT = 'meteoflow-php-sdk/1.0';

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var int
     */
    private $timeoutSeconds;

    /**
     * @var int
     */
    private $connectTimeoutSeconds;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param string $apiKey API key for authentication (required)
     * @throws ValidationException If apiKey is empty
     */
    public function __construct($apiKey)
    {
        if (!is_string($apiKey) || trim($apiKey) === '') {
            throw ValidationException::requiredField('apiKey');
        }

        $this->apiKey = trim($apiKey);
        $this->baseUrl = self::DEFAULT_BASE_URL;
        $this->timeoutSeconds = self::DEFAULT_TIMEOUT;
        $this->connectTimeoutSeconds = self::DEFAULT_CONNECT_TIMEOUT;
        $this->userAgent = self::DEFAULT_USER_AGENT;
        $this->debug = false;
    }

    /**
     * Get the base URL for API requests.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Get the API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get the request timeout in seconds.
     *
     * @return int
     */
    public function getTimeoutSeconds()
    {
        return $this->timeoutSeconds;
    }

    /**
     * Get the connection timeout in seconds.
     *
     * @return int
     */
    public function getConnectTimeoutSeconds()
    {
        return $this->connectTimeoutSeconds;
    }

    /**
     * Get the User-Agent header value.
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Check if debug mode is enabled.
     *
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Create a new config with a different base URL.
     *
     * @param string $baseUrl
     * @return self
     */
    public function withBaseUrl($baseUrl)
    {
        $config = clone $this;
        $config->baseUrl = rtrim($baseUrl, '/');
        return $config;
    }

    /**
     * Create a new config with a different request timeout.
     *
     * @param int $seconds
     * @return self
     */
    public function withTimeout($seconds)
    {
        $config = clone $this;
        $config->timeoutSeconds = (int) $seconds;
        return $config;
    }

    /**
     * Create a new config with a different connection timeout.
     *
     * @param int $seconds
     * @return self
     */
    public function withConnectTimeout($seconds)
    {
        $config = clone $this;
        $config->connectTimeoutSeconds = (int) $seconds;
        return $config;
    }

    /**
     * Create a new config with a different User-Agent.
     *
     * @param string $userAgent
     * @return self
     */
    public function withUserAgent($userAgent)
    {
        $config = clone $this;
        $config->userAgent = $userAgent;
        return $config;
    }

    /**
     * Create a new config with debug mode enabled or disabled.
     *
     * @param bool $debug
     * @return self
     */
    public function withDebug($debug)
    {
        $config = clone $this;
        $config->debug = (bool) $debug;
        return $config;
    }
}
