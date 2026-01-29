<?php

namespace MeteoFlow\Exception;

use Exception;

/**
 * Exception thrown when the API returns a non-2xx HTTP status code.
 */
class ApiException extends MeteoFlowException
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $responseBody;

    /**
     * @var string|null
     */
    private $errorCode;

    /**
     * @var string|null
     */
    private $errorMessage;

    /**
     * @param string $message
     * @param int $statusCode
     * @param string $responseBody
     * @param string|null $errorCode
     * @param string|null $errorMessage
     * @param Exception|null $previous
     */
    public function __construct(
        $message,
        $statusCode,
        $responseBody = '',
        $errorCode = null,
        $errorMessage = null,
        Exception $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Create exception from HTTP response.
     *
     * @param int $statusCode
     * @param string $responseBody
     * @return self
     */
    public static function fromResponse($statusCode, $responseBody)
    {
        $errorCode = null;
        $errorMessage = null;

        // Try to parse error details from JSON response
        $data = json_decode($responseBody, true);
        if (is_array($data)) {
            $errorCode = isset($data['error']['code']) ? $data['error']['code'] : (isset($data['code']) ? $data['code'] : null);
            $errorMessage = isset($data['error']['message']) ? $data['error']['message'] : (isset($data['message']) ? $data['message'] : null);
        }

        $message = sprintf('API request failed with status %d', $statusCode);
        if ($errorMessage) {
            $message .= ': ' . $errorMessage;
        }

        return new self($message, $statusCode, $responseBody, $errorCode, $errorMessage);
    }
}
