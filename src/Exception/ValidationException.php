<?php

namespace MeteoFlow\Exception;

use Exception;

/**
 * Exception thrown when input validation fails (e.g., invalid Location, days < 1).
 */
class ValidationException extends MeteoFlowException
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $invalidValue;

    /**
     * @param string $message
     * @param string $field
     * @param mixed $invalidValue
     * @param Exception|null $previous
     */
    public function __construct($message, $field = '', $invalidValue = null, Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->field = $field;
        $this->invalidValue = $invalidValue;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getInvalidValue()
    {
        return $this->invalidValue;
    }

    /**
     * Create exception for invalid field value.
     *
     * @param string $field
     * @param mixed $value
     * @param string $reason
     * @return self
     */
    public static function forField($field, $value, $reason)
    {
        $message = sprintf('Invalid value for "%s": %s', $field, $reason);
        return new self($message, $field, $value);
    }

    /**
     * Create exception for required field.
     *
     * @param string $field
     * @return self
     */
    public static function requiredField($field)
    {
        $message = sprintf('Field "%s" is required', $field);
        return new self($message, $field, null);
    }
}
