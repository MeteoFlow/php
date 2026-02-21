<?php

require __DIR__ . '/../vendor/autoload.php';

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\WeatherClient;

$config = new ClientConfig('YOUR_API_KEY');
$client = new WeatherClient($config);

$location = Location::fromSlug('united-kingdom-london');

$response = $client->geomagnetic($location);

foreach ($response->items as $item) {
    echo $item->time . ' Kp max: ' . $item->valueMax . PHP_EOL;
}
