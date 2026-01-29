<?php

namespace MeteoFlow\Response\Model;

/**
 * Three-hourly forecast data point.
 */
class ThreeHourlyForecast
{
    /**
     * @var string|null Forecast time (ISO 8601)
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
     * @var string|null Precipitation type (rain, snow, none, etc.)
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
     * @var float|null Pressure
     */
    public $pressure;

    /**
     * @var int|null Humidity percentage
     */
    public $humidity;

    /**
     * @var float|null Visibility
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
     * Create ThreeHourlyForecast from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $forecast = new self();

        $forecast->date = isset($data['date']) ? $data['date'] : null;
        $forecast->temperature = isset($data['temperature_air']) ? (float) $data['temperature_air'] : null;
        $forecast->feelsLike = isset($data['temperature_air_feels_like']) ? (float) $data['temperature_air_feels_like'] : null;
        $forecast->description = isset($data['description']) ? $data['description'] : null;
        $forecast->pressure = isset($data['pressure']) ? (float) $data['pressure'] : null;
        $forecast->humidity = isset($data['humidity']) ? (int) $data['humidity'] : null;
        $forecast->visibility = isset($data['visibility']) ? (float) $data['visibility'] : null;

        // Precipitation object
        if (isset($data['precipitation']) && is_array($data['precipitation'])) {
            $forecast->precipitationType = isset($data['precipitation']['type']) ? $data['precipitation']['type'] : null;
            $forecast->precipitationMm = isset($data['precipitation']['mm']) ? (float) $data['precipitation']['mm'] : null;
        }

        // Cloudiness object
        if (isset($data['cloudiness']) && is_array($data['cloudiness'])) {
            $forecast->cloudinessType = isset($data['cloudiness']['type']) ? $data['cloudiness']['type'] : null;
        }

        // Icon object
        if (isset($data['icon']) && is_array($data['icon'])) {
            $forecast->iconCode = isset($data['icon']['code']) ? $data['icon']['code'] : null;
            $forecast->iconUrl = isset($data['icon']['url']) ? $data['icon']['url'] : null;
        }

        // UV index object
        if (isset($data['uvindex']) && is_array($data['uvindex'])) {
            $forecast->uvIndex = isset($data['uvindex']['val']) ? (float) $data['uvindex']['val'] : null;
            $forecast->uvDescription = isset($data['uvindex']['description']) ? $data['uvindex']['description'] : null;
        }

        // Wind object
        if (isset($data['wind']) && is_array($data['wind'])) {
            $forecast->windSpeed = isset($data['wind']['speed']) ? (float) $data['wind']['speed'] : null;
            $forecast->windDegree = isset($data['wind']['degree']) ? (int) $data['wind']['degree'] : null;
            $forecast->windGust = isset($data['wind']['gust']) ? (float) $data['wind']['gust'] : null;
        }

        return $forecast;
    }
}
