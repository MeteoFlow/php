<?php

require __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\Options\AirQualityOptions;
use MeteoFlow\WeatherClient;

$config = new ClientConfig('YOUR_API_KEY');
$client = new WeatherClient($config);

$location = Location::fromSlug('united-kingdom-london');
$options = AirQualityOptions::create()->setDays(6);

$response = $client->airQuality($location, $options);

foreach ($response->items as $item) {
    echo $item->time . ' AQI: ' . $item->aqi . PHP_EOL;
}
