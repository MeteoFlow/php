<?php

namespace MeteoFlow\Response\Model;

/**
 * Astronomy data (sunrise, sunset, moon phases, etc.).
 */
class Astronomy
{
    /**
     * @var string|null Date (ISO 8601 date)
     */
    public $date;

    /**
     * @var string|null Sunrise time (ISO 8601 or HH:MM)
     */
    public $sunrise;

    /**
     * @var string|null Sunset time (ISO 8601 or HH:MM)
     */
    public $sunset;

    /**
     * @var string|null Moonrise time
     */
    public $moonrise;

    /**
     * @var string|null Moonset time
     */
    public $moonset;

    /**
     * @var string|null Moon phase name
     */
    public $moonPhase;

    /**
     * @var float|null Moon illumination percentage (0-100)
     */
    public $moonIllumination;

    /**
     * @var int|null Day length in seconds
     */
    public $dayLength;

    /**
     * @var float|null Moon angle in degrees
     */
    public $moonAngle;

    /**
     * @var int|null Moon phase code
     */
    public $moonPhaseCode;

    /**
     * Create Astronomy from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $astronomy = new self();

        $astronomy->date = isset($data['date']) ? $data['date'] : null;
        $astronomy->sunrise = isset($data['sunrise']) ? $data['sunrise'] : null;
        $astronomy->sunset = isset($data['sunset']) ? $data['sunset'] : null;
        $astronomy->moonrise = isset($data['moonrise']) ? $data['moonrise'] : null;
        $astronomy->moonset = isset($data['moonset']) ? $data['moonset'] : null;
        $astronomy->moonPhase = isset($data['moon_phase']) ? $data['moon_phase'] : null;
        $astronomy->moonIllumination = isset($data['moon_illumination']) ? (float) $data['moon_illumination'] : null;
        $astronomy->dayLength = isset($data['day_length']) ? (int) $data['day_length'] : null;
        $astronomy->moonAngle = isset($data['moon_angle']) ? (float) $data['moon_angle'] : null;
        $astronomy->moonPhaseCode = isset($data['moon_phase_code']) ? (int) $data['moon_phase_code'] : null;

        return $astronomy;
    }
}
