<?php

/**
 * Example: Get current weather by IP address.
 *
 * Usage: php current_by_ip.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\WeatherClient;

// Create client configuration with your API key
$config = new ClientConfig('YOUR_API_KEY');

// Create the weather client
$client = new WeatherClient($config);

// Create location using IP address (strict ONEOF - only IP is sent, no slug or coordinates)
$location = Location::fromIp('8.8.8.8');

try {
    // Get current weather
    $response = $client->current($location);

    // Access place information
    echo "Location: {$response->place->name}, {$response->place->country}\n";
    echo "Timezone: {$response->place->timezoneOffset}\n";
    echo "Local time: {$response->place->localTime}\n";
    echo "\n";

    // Access current weather data
    echo "Current Weather:\n";
    echo "  Temperature: {$response->current->temperature}C\n";
    echo "  Feels like: {$response->current->feelsLike}C\n";
    echo "  Humidity: {$response->current->humidity}%\n";
    echo "  Wind speed: {$response->current->windSpeed} m/s\n";
    echo "  Description: {$response->current->description}\n";
    echo "\n";

    // Access astronomy data
    if ($response->astronomy) {
        echo "Astronomy:\n";
        echo "  Sunrise: {$response->astronomy->sunrise}\n";
        echo "  Sunset: {$response->astronomy->sunset}\n";
    }

} catch (\MeteoFlow\Exception\ApiException $e) {
    echo "API Error: {$e->getMessage()} (HTTP {$e->getStatusCode()})\n";
} catch (\MeteoFlow\Exception\TransportException $e) {
    echo "Network Error: {$e->getMessage()}\n";
} catch (\MeteoFlow\Exception\MeteoFlowException $e) {
    echo "Error: {$e->getMessage()}\n";
}
