<?php

namespace MeteoFlow\Exception;

use Exception;

/**
 * Exception thrown when JSON decoding fails or response structure doesn't match expectations.
 */
class SerializationException extends MeteoFlowException
{
    /**
     * @var string
     */
    private $rawBody;

    /**
     * @var string
     */
    private $jsonError;

    /**
     * @param string $message
     * @param string $rawBody
     * @param string $jsonError
     * @param Exception|null $previous
     */
    public function __construct($message, $rawBody = '', $jsonError = '', Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->rawBody = $rawBody;
        $this->jsonError = $jsonError;
    }

    /**
     * @return string
     */
    public function getRawBody()
    {
        return $this->rawBody;
    }

    /**
     * @return string
     */
    public function getJsonError()
    {
        return $this->jsonError;
    }

    /**
     * Create exception from JSON decode error.
     *
     * @param string $rawBody
     * @return self
     */
    public static function fromJsonError($rawBody)
    {
        $jsonError = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
        $message = 'Failed to decode JSON response: ' . $jsonError;

        return new self($message, $rawBody, $jsonError);
    }

    /**
     * Create exception for unexpected response structure.
     *
     * @param string $rawBody
     * @param string $expectedField
     * @return self
     */
    public static function fromMissingField($rawBody, $expectedField)
    {
        $message = sprintf('Response is missing expected field: %s', $expectedField);

        return new self($message, $rawBody, '');
    }
}
