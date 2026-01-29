<?php

namespace MeteoFlow\Location;

/**
 * Abstract base class for location specification.
 *
 * Location follows a strict ONEOF pattern - it can be either:
 * - LocationSlug: identified by a slug string
 * - LocationCoords: identified by latitude and longitude
 *
 * Use the static factory methods to create instances.
 */
abstract class Location
{
    /**
     * Convert location to query parameters for API request.
     *
     * @return array
     */
    abstract public function toQueryParams();

    /**
     * Create a location from a slug identifier.
     *
     * @param string $slug
     * @return LocationSlug
     */
    public static function fromSlug($slug)
    {
        return new LocationSlug($slug);
    }

    /**
     * Create a location from coordinates.
     *
     * @param float $lat Latitude (-90 to 90)
     * @param float $lon Longitude (-180 to 180)
     * @return LocationCoords
     */
    public static function fromCoords($lat, $lon)
    {
        return new LocationCoords($lat, $lon);
    }
}
