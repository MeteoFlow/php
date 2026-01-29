<?php

/**
 * Example: Get hourly weather forecast.
 *
 * Usage: php forecast_hourly.php
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

// Create location
$location = Location::fromSlug('london-gb');

// Create forecast options
$options = ForecastOptions::create()
    ->setDays(3)
    ->setUnits(Units::METRIC)
    ->setLang('en');

try {
    // Get hourly forecast
    $response = $client->forecastHourly($location, $options);

    // Show location info
    echo "Hourly Forecast for {$response->place->name}, {$response->place->country}\n";
    echo "Total hours: {$response->getForecastCount()}\n";
    echo str_repeat('-', 60) . "\n";

    // Show first 24 hours
    $count = min(24, count($response->forecast));
    for ($i = 0; $i < $count; $i++) {
        $hour = $response->forecast[$i];
        printf(
            "%s | %5.1fC | %3d%% humidity | %s\n",
            $hour->date,
            $hour->temperature,
            $hour->humidity,
            $hour->description
        );
    }

    // Show astronomy data if available
    if (!empty($response->astronomy)) {
        echo "\n";
        echo "Astronomy:\n";
        foreach ($response->astronomy as $astro) {
            echo "  {$astro->date}: Sunrise {$astro->sunrise}, Sunset {$astro->sunset}\n";
        }
    }

} catch (\MeteoFlow\Exception\ValidationException $e) {
    echo "Validation Error: {$e->getMessage()}\n";
} catch (\MeteoFlow\Exception\ApiException $e) {
    echo "API Error: {$e->getMessage()} (HTTP {$e->getStatusCode()})\n";
} catch (\MeteoFlow\Exception\MeteoFlowException $e) {
    echo "Error: {$e->getMessage()}\n";
}
