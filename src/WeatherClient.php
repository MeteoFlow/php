<?php

namespace MeteoFlow;

use MeteoFlow\Exception\ApiException;
use MeteoFlow\Exception\SerializationException;
use MeteoFlow\Exception\ValidationException;
use MeteoFlow\Location\Location;
use MeteoFlow\Options\ForecastOptions;
use MeteoFlow\Options\Unit;
use MeteoFlow\Response\CitiesResponse;
use MeteoFlow\Response\CountriesResponse;
use MeteoFlow\Response\CurrentWeatherResponse;
use MeteoFlow\Response\DailyForecastResponse;
use MeteoFlow\Response\HourlyForecastResponse;
use MeteoFlow\Response\ThreeHourlyForecastResponse;
use MeteoFlow\Transport\CurlTransport;
use MeteoFlow\Transport\HttpTransportInterface;

/**
 * MeteoFlow Weather API client implementation.
 */
class WeatherClient implements WeatherClientInterface
{
    /**
     * Default number of forecast days.
     */
    const DEFAULT_FORECAST_DAYS = 7;

    /**
     * Default language for API responses.
     */
    const DEFAULT_LANG = 'en';

    /**
     * Default units for API responses.
     */
    const DEFAULT_UNITS = Unit::METRIC;

    /**
     * API endpoint for current weather.
     */
    const ENDPOINT_CURRENT = '/v2/current/';

    /**
     * API endpoint for hourly forecast.
     */
    const ENDPOINT_FORECAST_HOURLY = '/v2/forecast/by-hours/';

    /**
     * API endpoint for 3-hourly forecast.
     */
    const ENDPOINT_FORECAST_3HOURLY = '/v2/forecast/by-3hours/';

    /**
     * API endpoint for daily forecast.
     */
    const ENDPOINT_FORECAST_DAILY = '/v2/forecast/by-days/';

    /**
     * API endpoint for countries list.
     */
    const ENDPOINT_COUNTRIES = '/v2/geography/countries/';

    /**
     * API endpoint for cities by country.
     */
    const ENDPOINT_CITIES_BY_COUNTRY = '/v2/geography/countries/cities/';

    /**
     * API endpoint for city search.
     */
    const ENDPOINT_SEARCH = '/v2/geography/search/';

    /**
     * @var ClientConfig
     */
    private $config;

    /**
     * @var HttpTransportInterface
     */
    private $transport;

    /**
     * Create a new WeatherClient instance.
     *
     * @param ClientConfig $config Client configuration
     * @param HttpTransportInterface|null $transport Optional custom HTTP transport (defaults to CurlTransport)
     */
    public function __construct(ClientConfig $config, HttpTransportInterface $transport = null)
    {
        $this->config = $config;
        $this->transport = $transport !== null ? $transport : new CurlTransport($config);
    }

    /**
     * {@inheritdoc}
     */
    public function current(Location $location)
    {
        $params = $this->buildLocationParams($location);

        $data = $this->request(self::ENDPOINT_CURRENT, $params);

        return CurrentWeatherResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function forecastHourly(Location $location, ForecastOptions $options = null)
    {
        $params = $this->buildForecastParams($location, $options);

        $data = $this->request(self::ENDPOINT_FORECAST_HOURLY, $params);

        return HourlyForecastResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function forecast3Hourly(Location $location, ForecastOptions $options = null)
    {
        $params = $this->buildForecastParams($location, $options);

        $data = $this->request(self::ENDPOINT_FORECAST_3HOURLY, $params);

        return ThreeHourlyForecastResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function forecastDaily(Location $location, ForecastOptions $options = null)
    {
        $params = $this->buildForecastParams($location, $options);

        $data = $this->request(self::ENDPOINT_FORECAST_DAILY, $params);

        return DailyForecastResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function countries()
    {
        $data = $this->request(self::ENDPOINT_COUNTRIES, array());

        return CountriesResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function citiesByCountry($countryCode)
    {
        if (!is_string($countryCode) || trim($countryCode) === '') {
            throw ValidationException::forField('country_code', $countryCode, 'must be a non-empty string');
        }

        $data = $this->request(self::ENDPOINT_CITIES_BY_COUNTRY, array('country_code' => $countryCode));

        return CitiesResponse::fromArray($data);
    }

    /**
     * {@inheritdoc}
     */
    public function searchCities($query, $limit = null)
    {
        if (!is_string($query) || trim($query) === '') {
            throw ValidationException::forField('q', $query, 'must be a non-empty string');
        }

        $params = array('q' => $query);

        if ($limit !== null) {
            if (!is_int($limit) || $limit < 1) {
                throw ValidationException::forField('limit', $limit, 'must be a positive integer');
            }
            $params['limit'] = $limit;
        }

        $data = $this->request(self::ENDPOINT_SEARCH, $params);

        return CitiesResponse::fromArray($data);
    }

    /**
     * Build query parameters from location.
     *
     * @param Location $location
     * @return array
     */
    private function buildLocationParams(Location $location)
    {
        return $location->toQueryParams();
    }

    /**
     * Build query parameters for forecast requests.
     *
     * @param Location $location
     * @param ForecastOptions|null $options
     * @return array
     */
    private function buildForecastParams(Location $location, ForecastOptions $options = null)
    {
        $params = $location->toQueryParams();

        // Apply options or defaults
        if ($options !== null) {
            $optionParams = $options->toQueryParams();
            $params = array_merge($params, $optionParams);
        }

        // Apply defaults for missing values
        if (!isset($params['days'])) {
            $params['days'] = self::DEFAULT_FORECAST_DAYS;
        }

        if (!isset($params['lang'])) {
            $params['lang'] = self::DEFAULT_LANG;
        }

        if (!isset($params['units'])) {
            $params['units'] = self::DEFAULT_UNITS;
        }

        return $params;
    }

    /**
     * Perform API request.
     *
     * @param string $endpoint API endpoint path
     * @param array $params Query parameters
     * @return array Parsed JSON response
     * @throws ApiException On HTTP errors
     * @throws SerializationException On JSON decode errors
     */
    private function request($endpoint, array $params)
    {
        // Add API key to params
        $params['key'] = $this->config->getApiKey();

        // Build URL
        $url = $this->buildUrl($endpoint, $params);

        // Execute request
        $response = $this->transport->request('GET', $url);

        // Check HTTP status
        if ($response['statusCode'] < 200 || $response['statusCode'] >= 300) {
            throw ApiException::fromResponse($response['statusCode'], $response['body']);
        }

        // Parse JSON
        $data = json_decode($response['body'], true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw SerializationException::fromJsonError($response['body']);
        }

        return $data;
    }

    /**
     * Build full URL with query parameters.
     *
     * @param string $endpoint
     * @param array $params
     * @return string
     */
    private function buildUrl($endpoint, array $params)
    {
        $baseUrl = rtrim($this->config->getBaseUrl(), '/');
        $url = $baseUrl . $endpoint;

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    /**
     * Get the client configuration.
     *
     * @return ClientConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the HTTP transport.
     *
     * @return HttpTransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }
}
