<?php

/**
 * Example: Get all cities for a given country code.
 *
 * Usage: php geography_cities_by_country.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\WeatherClient;

$config = new ClientConfig('YOUR_API_KEY');
$client = new WeatherClient($config);

try {
    $response = $client->citiesByCountry('DE');

    echo "Cities in Germany: {$response->getCitiesCount()}\n\n";

    foreach ($response->cities as $city) {
        echo "{$city->name} ({$city->region}) — slug: {$city->slug}\n";
        echo "  Coords: {$city->lat}, {$city->lon} | UTC offset: {$city->timezoneOffset} min\n";
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
