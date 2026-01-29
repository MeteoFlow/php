<?php

namespace MeteoFlow\Location;

use MeteoFlow\Exception\ValidationException;

/**
 * Location identified by latitude and longitude coordinates.
 */
class LocationCoords extends Location
{
    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lon;

    /**
     * @param float $lat Latitude (-90 to 90)
     * @param float $lon Longitude (-180 to 180)
     * @throws ValidationException If coordinates are out of range
     */
    public function __construct($lat, $lon)
    {
        $lat = (float) $lat;
        $lon = (float) $lon;

        if ($lat < -90 || $lat > 90) {
            throw ValidationException::forField('lat', $lat, 'must be between -90 and 90');
        }

        if ($lon < -180 || $lon > 180) {
            throw ValidationException::forField('lon', $lon, 'must be between -180 and 180');
        }

        $this->lat = $lat;
        $this->lon = $lon;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * {@inheritdoc}
     */
    public function toQueryParams()
    {
        return array(
            'lat' => $this->lat,
            'lon' => $this->lon,
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%f,%f', $this->lat, $this->lon);
    }
}
