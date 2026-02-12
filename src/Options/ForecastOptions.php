<?php

namespace MeteoFlow\Options;

use MeteoFlow\Exception\ValidationException;

/**
 * Options for forecast API requests.
 *
 * All options are optional. When not set, SDK defaults will be applied.
 */
class ForecastOptions
{
    /**
     * @var int|null
     */
    private $days;

    /**
     * @var string|null Unit::METRIC or Unit::IMPERIAL
     */
    private $unit;

    /**
     * @var string|null BCP-47 language code (en, ru, de, etc.)
     */
    private $lang;

    /**
     * Create a new ForecastOptions instance.
     */
    public function __construct()
    {
        // All options are null by default
    }

    /**
     * Set the number of forecast days.
     *
     * @param int $days Must be >= 1
     * @return $this
     * @throws ValidationException If days < 1
     */
    public function setDays($days)
    {
        $days = (int) $days;

        if ($days < 1) {
            throw ValidationException::forField('days', $days, 'must be >= 1');
        }

        $this->days = $days;

        return $this;
    }

    /**
     * Set the measurement unit.
     *
     * @param string $unit Unit::METRIC or Unit::IMPERIAL
     * @return $this
     * @throws ValidationException If unit value is invalid
     */
    public function setUnit($unit)
    {
        if (!Unit::isValid($unit)) {
            throw ValidationException::forField('unit', $unit, 'must be "metric" or "imperial"');
        }

        $this->unit = $unit;

        return $this;
    }

    /**
     * Set the language for response text.
     *
     * @param string $lang BCP-47 language code (en, ru, de, etc.)
     * @return $this
     * @throws ValidationException If lang is empty
     */
    public function setLang($lang)
    {
        if (!is_string($lang) || trim($lang) === '') {
            throw ValidationException::forField('lang', $lang, 'must be a non-empty string');
        }

        $this->lang = trim($lang);

        return $this;
    }

    /**
     * Get the number of forecast days.
     *
     * @return int|null
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Get the measurement unit.
     *
     * @return string|null
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Get the language code.
     *
     * @return string|null
     */
    public function getLang()
    {
        return $this->lang;
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

        if ($this->unit !== null) {
            $params['units'] = $this->unit;
        }

        if ($this->lang !== null) {
            $params['lang'] = $this->lang;
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
