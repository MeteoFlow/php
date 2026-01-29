<?php

/**
 * Example: Get 3-hourly weather forecast.
 *
 * Usage: php forecast_3hourly.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\Options\ForecastOptions;
use MeteoFlow\Options\Units;
use MeteoFlow\WeatherClient;

// Create client configuration with your API key
$config = new ClientConfig('YOUR_API_KEY');

// Create the weather client
$client = new WeatherClient($config);

// Create location by coordinates
$location = Location::fromCoords(51.5074, -0.1278); // London

// Create forecast options (5 days, metric units, English language)
$options = ForecastOptions::create()
    ->setDays(5)
    ->setUnits(Units::METRIC)
    ->setLang('en');

try {
    // Get 3-hourly forecast
    $response = $client->forecast3Hourly($location, $options);

    // Show location info
    echo "3-Hourly Forecast for {$response->place->name}, {$response->place->country}\n";
    echo "Total intervals: {$response->getForecastCount()}\n";
    echo str_repeat('-', 70) . "\n";

    // Show all forecast data
    foreach ($response->forecast as $interval) {
        printf(
            "%s | %5.1fC (feels %5.1fC) | %3d%% humidity | Wind %4.1f m/s | %s\n",
            $interval->date,
            $interval->temperature,
            $interval->feelsLike ?: $interval->temperature,
            $interval->humidity,
            $interval->windSpeed,
            $interval->description
        );
    }

} catch (\MeteoFlow\Exception\ApiException $e) {
    echo "API Error: {$e->getMessage()}\n";
    if ($e->getErrorMessage()) {
        echo "  Details: {$e->getErrorMessage()}\n";
    }
} catch (\MeteoFlow\Exception\MeteoFlowException $e) {
    echo "Error: {$e->getMessage()}\n";
}
