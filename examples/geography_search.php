<?php

/**
 * Example: Search cities by name.
 *
 * Usage: php geography_search.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\WeatherClient;

$config = new ClientConfig('YOUR_API_KEY');
$client = new WeatherClient($config);

try {
    $response = $client->searchCities('Berlin', 5);

    echo "Results for \"Berlin\": {$response->getCitiesCount()}\n\n";

    foreach ($response->cities as $city) {
        echo "{$city->name}, {$city->country} ({$city->countryCode})\n";
        echo "  Region: {$city->region} | Slug: {$city->slug}\n";
        echo "  Coords: {$city->lat}, {$city->lon}\n\n";
    }

} catch (\MeteoFlow\Exception\ValidationException $e) {
    echo "Validation Error: {$e->getMessage()}\n";
} catch (\MeteoFlow\Exception\ApiException $e) {
    echo "API Error: {$e->getMessage()} (HTTP {$e->getStatusCode()})\n";
} catch (\MeteoFlow\Exception\TransportException $e) {
    echo "Network Error: {$e->getMessage()}\n";
} catch (\MeteoFlow\Exception\MeteoFlowException $e) {
    echo "Error: {$e->getMessage()}\n";
}
