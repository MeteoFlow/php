<?php

/**
 * Example: Get the list of all supported countries.
 *
 * Usage: php geography_countries.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\WeatherClient;

$config = new ClientConfig('YOUR_API_KEY');
$client = new WeatherClient($config);

try {
    $response = $client->countries();

    echo "Total countries: {$response->getCountriesCount()}\n\n";

    foreach ($response->countries as $country) {
        echo "[{$country->code}] {$country->name} ({$country->slug})\n";
    }

} catch (\MeteoFlow\Exception\ApiException $e) {
    echo "API Error: {$e->getMessage()} (HTTP {$e->getStatusCode()})\n";
} catch (\MeteoFlow\Exception\TransportException $e) {
    echo "Network Error: {$e->getMessage()}\n";
} catch (\MeteoFlow\Exception\MeteoFlowException $e) {
    echo "Error: {$e->getMessage()}\n";
}
