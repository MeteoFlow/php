<?php

namespace MeteoFlow\Options;

/**
 * Units constants for weather data.
 */
class Units
{
    /**
     * Metric units (Celsius, km/h, mm, etc.)
     */
    const METRIC = 'metric';

    /**
     * Imperial units (Fahrenheit, mph, inches, etc.)
     */
    const IMPERIAL = 'imperial';

    /**
     * Check if a value is a valid unit.
     *
     * @param string $value
     * @return bool
     */
    public static function isValid($value)
    {
        return $value === self::METRIC || $value === self::IMPERIAL;
    }

    /**
     * Get all valid unit values.
     *
     * @return array
     */
    public static function all()
    {
        return array(self::METRIC, self::IMPERIAL);
    }
}
