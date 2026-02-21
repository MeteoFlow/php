<?php

namespace MeteoFlow\Response\Model;

/**
 * Geomagnetic activity data point.
 */
class GeomagneticDay
{
    /**
     * @var string|null Time (ISO 8601)
     */
    public $time;

    /**
     * @var int|null Maximum value for the day
     */
    public $valueMax;

    /**
     * Create GeomagneticDay from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $item = new self();

        $item->time = isset($data['time']) ? $data['time'] : null;
        $item->valueMax = isset($data['value_max']) ? (int) $data['value_max'] : null;

        return $item;
    }
}
