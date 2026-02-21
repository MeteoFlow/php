<?php

namespace MeteoFlow\Options;

use MeteoFlow\Exception\ValidationException;

/**
 * Options for air quality API requests.
 *
 * Only days is supported, max 8.
 */
class AirQualityOptions
{
    /**
     * @var int|null
     */
    private $days;

    /**
     * Create a new AirQualityOptions instance.
     */
    public function __construct()
    {
        // All options are null by default
    }

    /**
     * Set the number of days.
     *
     * @param int $days Must be between 1 and 8
     * @return $this
     * @throws ValidationException If days out of range
     */
    public function setDays($days)
    {
        $days = (int) $days;

        if ($days < 1 || $days > 8) {
            throw ValidationException::forField('days', $days, 'must be between 1 and 8');
        }

        $this->days = $days;

        return $this;
    }

    /**
     * Get the number of days.
     *
     * @return int|null
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Convert options to query parameters.
     *
     * Only includes non-null values.
     *
     * @return array
     */
    public function toQueryParams()
    {
        $params = array();

        if ($this->days !== null) {
            $params['days'] = $this->days;
        }

        return $params;
    }

    /**
     * Create options with fluent interface.
     *
     * @return self
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Create options with days preset.
     *
     * @param int $days
     * @return self
     */
    public static function withDays($days)
    {
        $options = new self();
        $options->setDays($days);
        return $options;
    }
}
