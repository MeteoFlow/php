<?php

/**
 * Example: Get current weather by coordinates.
 *
 * Usage: php current_by_coords.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\WeatherClient;

// Create client configuration with your API key
$config = new ClientConfig('YOUR_API_KEY');

// Create the weather client
$client = new WeatherClient($config);

// Create location using coordinates (strict ONEOF - only lat/lon sent, no slug)
// Example: London, UK coordinates
$location = Location::fromCoords(51.5074, -0.1278);

try {
    // Get current weather
    $response = $client->current($location);

    // Access place information
    echo "Location: {$response->place->name}, {$response->place->country}\n";
    echo "Coordinates: {$response->place->lat}, {$response->place->lon}\n";
    echo "\n";

    // Access current weather data
    echo "Current Weather:\n";
    echo "  Temperature: {$response->current->temperature}C\n";
    echo "  Feels like: {$response->current->feelsLike}C\n";
    echo "  Humidity: {$response->current->humidity}%\n";
    echo "  Pressure: {$response->current->pressure} hPa\n";
    echo "  Wind: {$response->current->windSpeed} m/s from {$response->current->windDegree}deg\n";
    echo "  Cloudiness: {$response->current->cloudinessType}\n";
    echo "  Visibility: {$response->current->visibility} km\n";
    echo "  UV index: {$response->current->uvIndex}\n";
    echo "  Description: {$response->current->description}\n";
    echo "\n";

    // Access astronomy data
    if ($response->astronomy) {
        echo "Astronomy:\n";
        echo "  Sunrise: {$response->astronomy->sunrise}\n";
        echo "  Sunset: {$response->astronomy->sunset}\n";
        echo "  Moon phase: {$response->astronomy->moonPhase}\n";
    }

} catch (\MeteoFlow\Exception\ValidationException $e) {
    echo "Validation Error: {$e->getMessage()} (field: {$e->getField()})\n";
} catch (\MeteoFlow\Exception\ApiException $e) {
    echo "API Error: {$e->getMessage()} (HTTP {$e->getStatusCode()})\n";
} catch (\MeteoFlow\Exception\MeteoFlowException $e) {
    echo "Error: {$e->getMessage()}\n";
}
