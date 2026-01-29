<?php

/**
 * Example: Get daily weather forecast.
 *
 * Usage: php forecast_daily.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\Options\ForecastOptions;
use MeteoFlow\Options\Units;
use MeteoFlow\WeatherClient;

// Create client configuration with your API key
// You can also customize other settings
$config = (new ClientConfig('YOUR_API_KEY'))
    ->withTimeout(15)
    ->withConnectTimeout(5);

// Create the weather client
$client = new WeatherClient($config);

// Create location
$location = Location::fromSlug('united-kingdom-london');

// Create forecast options - request 14 days in imperial units
$options = ForecastOptions::create()
    ->setDays(14)
    ->setUnits(Units::IMPERIAL)
    ->setLang('en');

try {
    // Get daily forecast
    $response = $client->forecastDaily($location, $options);

    // Show location info
    echo "Daily Forecast for {$response->place->name}, {$response->place->country}\n";
    echo "Total days: {$response->getDailyCount()}\n";
    echo str_repeat('=', 80) . "\n\n";

    // Show daily forecast data
    foreach ($response->daily as $day) {
        echo "Date: {$day->date}\n";
        echo "  Temperature: {$day->temperatureMin}F - {$day->temperatureMax}F\n";
        echo "  Condition: {$day->description}\n";
        echo "  Humidity: {$day->humidityMin}% - {$day->humidityMax}%\n";
        echo "  Wind: {$day->windSpeed} mph\n";

        if ($day->precipitationMm !== null && $day->precipitationMm > 0) {
            echo "  Precipitation: {$day->precipitationMm} mm ({$day->precipitationType})\n";
        }

        echo "\n";
    }

    // Show astronomy data if available separately
    if (!empty($response->astronomy)) {
        echo "Detailed Astronomy Data:\n";
        echo str_repeat('-', 40) . "\n";
        foreach ($response->astronomy as $astro) {
            echo "{$astro->date}:\n";
            echo "  Sunrise: {$astro->sunrise}\n";
            echo "  Sunset: {$astro->sunset}\n";
            echo "  Moon phase: {$astro->moonPhase}\n";
            echo "  Moon illumination: {$astro->moonIllumination}%\n";
            echo "\n";
        }
    }

} catch (\MeteoFlow\Exception\ValidationException $e) {
    echo "Validation Error: {$e->getMessage()}\n";
    echo "  Invalid field: {$e->getField()}\n";
    echo "  Value: " . var_export($e->getInvalidValue(), true) . "\n";
} catch (\MeteoFlow\Exception\ApiException $e) {
    echo "API Error: {$e->getMessage()}\n";
    echo "  HTTP Status: {$e->getStatusCode()}\n";
    if ($e->getErrorCode()) {
        echo "  Error Code: {$e->getErrorCode()}\n";
    }
} catch (\MeteoFlow\Exception\TransportException $e) {
    echo "Network Error: {$e->getMessage()}\n";
    echo "  cURL Error Code: {$e->getCurlErrorCode()}\n";
} catch (\MeteoFlow\Exception\MeteoFlowException $e) {
    echo "Error: {$e->getMessage()}\n";
}
