<?php

namespace MeteoFlow\Response\Model;

/**
 * Place/location information from API response.
 */
class Place
{
    /**
     * @var string|null
     */
    public $slug;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null
     */
    public $country;

    /**
     * @var string|null
     */
    public $countryCode;

    /**
     * @var string|null
     */
    public $region;

    /**
     * @var float|null
     */
    public $lat;

    /**
     * @var float|null
     */
    public $lon;

    /**
     * @var string|null Timezone identifier (e.g., "Europe/London")
     */
    public $timezone;

    /**
     * @var int|null Timezone offset in seconds
     */
    public $timezoneOffset;

    /**
     * @var string|null Local time at the location (ISO 8601)
     */
    public $localTime;

    /**
     * Create Place from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $place = new self();

        $place->slug = isset($data['slug']) ? $data['slug'] : null;
        $place->name = isset($data['city_name']) ? $data['city_name'] : (isset($data['name']) ? $data['name'] : null);
        $place->country = isset($data['country_name']) ? $data['country_name'] : (isset($data['country']) ? $data['country'] : null);
        $place->countryCode = isset($data['country']) ? $data['country'] : (isset($data['country_code']) ? $data['country_code'] : null);
        $place->region = isset($data['region_name']) ? $data['region_name'] : (isset($data['region']) ? $data['region'] : null);
        $place->lat = isset($data['latitude']) ? (float) $data['latitude'] : (isset($data['lat']) ? (float) $data['lat'] : null);
        $place->lon = isset($data['longitude']) ? (float) $data['longitude'] : (isset($data['lon']) ? (float) $data['lon'] : null);
        $place->timezone = isset($data['timezone']) ? $data['timezone'] : null;
        $place->timezoneOffset = isset($data['timezone_offset']) ? (int) $data['timezone_offset'] : null;
        $place->localTime = isset($data['local_time']) ? $data['local_time'] : null;

        return $place;
    }
}
