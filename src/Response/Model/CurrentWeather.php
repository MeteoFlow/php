<?php

namespace MeteoFlow\Response\Model;

/**
 * Current weather conditions.
 */
class CurrentWeather
{
    /**
     * @var string|null Observation time (ISO 8601)
     */
    public $date;

    /**
     * @var float|null Temperature
     */
    public $temperature;

    /**
     * @var float|null Feels like temperature
     */
    public $feelsLike;

    /**
     * @var string|null Weather description
     */
    public $description;

    /**
     * @var int|null Humidity percentage
     */
    public $humidity;

    /**
     * @var float|null Pressure in hPa
     */
    public $pressure;

    /**
     * @var float|null Visibility in km
     */
    public $visibility;

    /**
     * @var float|null Wind speed
     */
    public $windSpeed;

    /**
     * @var int|null Wind direction in degrees
     */
    public $windDegree;

    /**
     * @var float|null Wind gust speed
     */
    public $windGust;

    /**
     * @var string|null Precipitation type (rain, snow, etc.)
     */
    public $precipitationType;

    /**
     * @var float|null Precipitation amount in mm
     */
    public $precipitationMm;

    /**
     * @var string|null Cloudiness type
     */
    public $cloudinessType;

    /**
     * @var string|null Weather icon code
     */
    public $iconCode;

    /**
     * @var string|null Weather icon URL
     */
    public $iconUrl;

    /**
     * @var float|null UV index value
     */
    public $uvIndex;

    /**
     * @var string|null UV index description
     */
    public $uvDescription;

    /**
     * Create CurrentWeather from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $weather = new self();

        $weather->date = isset($data['date']) ? $data['date'] : null;
        $weather->temperature = isset($data['temperature_air']) ? (float) $data['temperature_air'] : null;
        $weather->feelsLike = isset($data['temperature_air_feels_like']) ? (float) $data['temperature_air_feels_like'] : null;
        $weather->description = isset($data['description']) ? $data['description'] : null;
        $weather->humidity = isset($data['humidity']) ? (int) $data['humidity'] : null;
        $weather->pressure = isset($data['pressure']) ? (float) $data['pressure'] : null;
        $weather->visibility = isset($data['visibility']) ? (float) $data['visibility'] : null;

        // Wind object
        if (isset($data['wind']) && is_array($data['wind'])) {
            $weather->windSpeed = isset($data['wind']['speed']) ? (float) $data['wind']['speed'] : null;
            $weather->windDegree = isset($data['wind']['degree']) ? (int) $data['wind']['degree'] : null;
            $weather->windGust = isset($data['wind']['gust']) ? (float) $data['wind']['gust'] : null;
        }

        // Precipitation object
        if (isset($data['precipitation']) && is_array($data['precipitation'])) {
            $weather->precipitationType = isset($data['precipitation']['type']) ? $data['precipitation']['type'] : null;
            $weather->precipitationMm = isset($data['precipitation']['mm']) ? (float) $data['precipitation']['mm'] : null;
        }

        // Cloudiness object
        if (isset($data['cloudiness']) && is_array($data['cloudiness'])) {
            $weather->cloudinessType = isset($data['cloudiness']['type']) ? $data['cloudiness']['type'] : null;
        }

        // Icon object
        if (isset($data['icon']) && is_array($data['icon'])) {
            $weather->iconCode = isset($data['icon']['code']) ? $data['icon']['code'] : null;
            $weather->iconUrl = isset($data['icon']['url']) ? $data['icon']['url'] : null;
        }

        // UV index object
        if (isset($data['uvindex']) && is_array($data['uvindex'])) {
            $weather->uvIndex = isset($data['uvindex']['val']) ? (float) $data['uvindex']['val'] : null;
            $weather->uvDescription = isset($data['uvindex']['description']) ? $data['uvindex']['description'] : null;
        }

        return $weather;
    }
}
