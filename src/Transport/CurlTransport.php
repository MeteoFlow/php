<?php

namespace MeteoFlow\Transport;

use MeteoFlow\ClientConfig;
use MeteoFlow\Exception\TransportException;

/**
 * cURL-based HTTP transport implementation.
 */
class CurlTransport implements HttpTransportInterface
{
    /**
     * @var ClientConfig
     */
    private $config;

    /**
     * @param ClientConfig $config
     */
    public function __construct(ClientConfig $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $url, array $headers = array())
    {
        $ch = curl_init();

        if ($ch === false) {
            throw new TransportException('Failed to initialize cURL');
        }

        try {
            return $this->executeRequest($ch, $method, $url, $headers);
        } finally {
            curl_close($ch);
        }
    }

    /**
     * Execute the cURL request.
     *
     * @param resource $ch cURL handle
     * @param string $method
     * @param string $url
     * @param array $headers
     * @return array
     * @throws TransportException
     */
    private function executeRequest($ch, $method, $url, array $headers)
    {
        // Set URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // Set method
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        // Return response as string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set timeouts
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->config->getTimeoutSeconds());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->config->getConnectTimeoutSeconds());

        // Set User-Agent
        curl_setopt($ch, CURLOPT_USERAGENT, $this->config->getUserAgent());

        // Follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        // Capture response headers
        $responseHeaders = array();
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$responseHeaders) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) {
                return $len;
            }

            $name = strtolower(trim($header[0]));
            $value = trim($header[1]);
            $responseHeaders[$name] = $value;

            return $len;
        });

        // Set request headers
        $requestHeaders = $this->buildRequestHeaders($headers);
        if (!empty($requestHeaders)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        }

        // Enable verbose output if debug mode
        if ($this->config->isDebug()) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }

        // SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // Execute request
        $body = curl_exec($ch);

        // Check for cURL errors
        if ($body === false) {
            $errorCode = curl_errno($ch);
            $errorMessage = curl_error($ch);
            throw TransportException::fromCurlError($errorCode, $errorMessage);
        }

        // Get HTTP status code
        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return array(
            'statusCode' => $statusCode,
            'body' => $body,
            'headers' => $responseHeaders,
        );
    }

    /**
     * Build request headers array for cURL.
     *
     * @param array $headers
     * @return array
     */
    private function buildRequestHeaders(array $headers)
    {
        $result = array();

        // Add Accept header
        if (!isset($headers['Accept'])) {
            $headers['Accept'] = 'application/json';
        }

        foreach ($headers as $name => $value) {
            $result[] = $name . ': ' . $value;
        }

        return $result;
    }
}
