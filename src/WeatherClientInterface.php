<?php

namespace MeteoFlow;

use MeteoFlow\Exception\MeteoFlowException;
use MeteoFlow\Location\Location;
use MeteoFlow\Options\AirQualityOptions;
use MeteoFlow\Options\ForecastOptions;
use MeteoFlow\Response\CitiesResponse;
use MeteoFlow\Response\CountriesResponse;
use MeteoFlow\Response\CurrentWeatherResponse;
use MeteoFlow\Response\DailyForecastResponse;
use MeteoFlow\Response\GeomagneticResponse;
use MeteoFlow\Response\HourlyForecastResponse;
use MeteoFlow\Response\ThreeHourlyForecastResponse;
use MeteoFlow\Response\AirQualityResponse;

/**
 * Interface for the MeteoFlow Weather API client.
 *
 * All methods follow the canonical contract from the SDK UML diagram.
 */
interface WeatherClientInterface
{
    /**
     * Get current weather for a location.
     *
     * @param Location $location Location (slug, coordinates, or IP)
     * @return CurrentWeatherResponse
     * @throws MeteoFlowException On any error
     */
    public function current(Location $location);

    /**
     * Get hourly forecast for a location.
     *
     * @param Location $location Location (slug, coordinates, or IP)
     * @param ForecastOptions|null $options Forecast options (days, units, lang)
     * @return HourlyForecastResponse
     * @throws MeteoFlowException On any error
     */
    public function forecastHourly(Location $location, ForecastOptions $options = null);

    /**
     * Get 3-hourly forecast for a location.
     *
     * @param Location $location Location (slug, coordinates, or IP)
     * @param ForecastOptions|null $options Forecast options (days, units, lang)
     * @return ThreeHourlyForecastResponse
     * @throws MeteoFlowException On any error
     */
    public function forecast3Hourly(Location $location, ForecastOptions $options = null);

    /**
     * Get daily forecast for a location.
     *
     * @param Location $location Location (slug, coordinates, or IP)
     * @param ForecastOptions|null $options Forecast options (days, units, lang)
     * @return DailyForecastResponse
     * @throws MeteoFlowException On any error
     */
    public function forecastDaily(Location $location, ForecastOptions $options = null);

    /**
     * Get the list of all supported countries.
     *
     * @return CountriesResponse
     * @throws MeteoFlowException On any error
     */
    public function countries();

    /**
     * Get all cities for a given country code.
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code (e.g. "GB", "RU")
     * @return CitiesResponse
     * @throws MeteoFlowException On any error
     */
    public function citiesByCountry($countryCode);

    /**
     * Search cities by name.
     *
     * @param string $query Search query
     * @param int|null $limit Maximum number of results
     * @return CitiesResponse
     * @throws MeteoFlowException On any error
     */
    public function searchCities($query, $limit = null);

    /**
     * Get geomagnetic activity for a location.
     *
     * @param Location $location Location (slug, coordinates, or IP)
     * @return GeomagneticResponse
     * @throws MeteoFlowException On any error
     */
    public function geomagnetic(Location $location);

    /**
     * Get air quality forecast for a location.
     *
     * @param Location $location Location (slug, coordinates, or IP)
     * @param AirQualityOptions|null $options Air quality options (days up to 8)
     * @return AirQualityResponse
     * @throws MeteoFlowException On any error
     */
    public function airQuality(Location $location, AirQualityOptions $options = null);
}
