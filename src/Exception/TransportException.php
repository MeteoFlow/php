<?php

namespace MeteoFlow\Exception;

use Exception;

/**
 * Exception thrown when a transport/network error occurs (cURL errors, connection failures, etc.).
 */
class TransportException extends MeteoFlowException
{
    /**
     * @var int
     */
    private $curlErrorCode;

    /**
     * @var string
     */
    private $curlErrorMessage;

    /**
     * @param string $message
     * @param int $curlErrorCode
     * @param string $curlErrorMessage
     * @param Exception|null $previous
     */
    public function __construct($message, $curlErrorCode = 0, $curlErrorMessage = '', Exception $previous = null)
    {
        parent::__construct($message, $curlErrorCode, $previous);
        $this->curlErrorCode = $curlErrorCode;
        $this->curlErrorMessage = $curlErrorMessage;
    }

    /**
     * @return int
     */
    public function getCurlErrorCode()
    {
        return $this->curlErrorCode;
    }

    /**
     * @return string
     */
    public function getCurlErrorMessage()
    {
        return $this->curlErrorMessage;
    }

    /**
     * Create exception from cURL error.
     *
     * @param int $errorCode
     * @param string $errorMessage
     * @return self
     */
    public static function fromCurlError($errorCode, $errorMessage)
    {
        $message = sprintf('cURL error %d: %s', $errorCode, $errorMessage);
        return new self($message, $errorCode, $errorMessage);
    }
}
