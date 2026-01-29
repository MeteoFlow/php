<?php

namespace MeteoFlow\Transport;

use MeteoFlow\Exception\TransportException;

/**
 * Interface for HTTP transport implementations.
 *
 * This interface allows for custom transport implementations,
 * enabling framework-specific integrations (e.g., Laravel HTTP client, Symfony HttpClient).
 */
interface HttpTransportInterface
{
    /**
     * Perform an HTTP request.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $url Full URL including query parameters
     * @param array $headers Optional headers (key => value)
     * @return array Response data with keys: 'statusCode' (int), 'body' (string), 'headers' (array)
     * @throws TransportException On network or transport errors
     */
    public function request($method, $url, array $headers = array());
}
