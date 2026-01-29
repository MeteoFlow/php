<?php

namespace MeteoFlow\Response\Model;

/**
 * Daily forecast data point.
 */
class DailyForecast
{
    /**
     * @var string|null Forecast date (ISO 8601)
     */
    public $date;

    /**
     * @var float|null Minimum temperature
     */
    public $temperatureMin;

    /**
     * @var float|null Maximum temperature
     */
    public $temperatureMax;

    /**
     * @var string|null Weather description
     */
    public $description;

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
     * @var int|null Minimum humidity percentage
     */
    public $humidityMin;

    /**
     * @var int|null Maximum humidity percentage
     */
    public $humidityMax;

    /**
     * @var float|null Minimum visibility
     */
    public $visibilityMin;

    /**
     * @var float|null Maximum visibility
     */
    public $visibilityMax;

    /**
     * @var float|null Minimum pressure
     */
    public $pressureMin;

    /**
     * @var float|null Maximum pressure
     */
    public $pressureMax;

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
     * Create DailyForecast from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $forecast = new self();

        $forecast->date = isset($data['date']) ? $data['date'] : null;
        $forecast->description = isset($data['description']) ? $data['description'] : null;

        // Temperature air object
        if (isset($data['temperature_air']) && is_array($data['temperature_air'])) {
            $forecast->temperatureMin = isset($data['temperature_air']['min']) ? (float) $data['temperature_air']['min'] : null;
            $forecast->temperatureMax = isset($data['temperature_air']['max']) ? (float) $data['temperature_air']['max'] : null;
        }

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

        // Humidity object
        if (isset($data['humidity']) && is_array($data['humidity'])) {
            $forecast->humidityMin = isset($data['humidity']['min']) ? (int) $data['humidity']['min'] : null;
            $forecast->humidityMax = isset($data['humidity']['max']) ? (int) $data['humidity']['max'] : null;
        }

        // Visibility object
        if (isset($data['visibility']) && is_array($data['visibility'])) {
            $forecast->visibilityMin = isset($data['visibility']['min']) ? (float) $data['visibility']['min'] : null;
            $forecast->visibilityMax = isset($data['visibility']['max']) ? (float) $data['visibility']['max'] : null;
        }

        // Pressure object
        if (isset($data['pressure']) && is_array($data['pressure'])) {
            $forecast->pressureMin = isset($data['pressure']['min']) ? (float) $data['pressure']['min'] : null;
            $forecast->pressureMax = isset($data['pressure']['max']) ? (float) $data['pressure']['max'] : null;
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
