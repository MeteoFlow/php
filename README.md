# MeteoFlow PHP SDK

Official PHP SDK for the MeteoFlow Weather API.

## Requirements

- PHP 5.6 or higher
- ext-curl
- ext-json

## Installation

Install via Composer:

```bash
composer require meteoflow/php
```

## Quick Start

```php
<?php

// Create client
$config = new ClientConfig('YOUR_API_KEY');
$client = new WeatherClient($config);

// Get current weather by location slug
$location = Location::fromSlug('united-kingdom-london');
$response = $client->current($location);

echo "Temperature: {$response->current->temperature}C\n";
echo "Description: {$response->current->description}\n";
```

## API Methods

| Method                                 | Description           |
|----------------------------------------|-----------------------|
| `current($location)`                   | Get current weather   |
| `forecastHourly($location, $options)`  | Get hourly forecast   |
| `forecast3Hourly($location, $options)` | Get 3-hourly forecast |
| `forecastDaily($location, $options)`   | Get daily forecast    |

## Location

The SDK uses a strict ONEOF pattern for locations. You can specify a location either by slug or by coordinates, but not
both:

### By Slug

```php
$location = Location::fromSlug('united-kingdom-london');
```

### By Coordinates

```php
$location = Location::fromCoords(51.5074, -0.1278);
```

## Forecast Options

Use `ForecastOptions` to customize forecast requests:

```php
use MeteoFlow\Options\ForecastOptions;
use MeteoFlow\Options\Units;

$options = ForecastOptions::create()
    ->setDays(7)                    // Number of days (>= 1)
    ->setUnits(Units::METRIC)       // 'metric' or 'imperial'
    ->setLang('en');                // BCP-47 language code

$response = $client->forecastDaily($location, $options);
```

### Default Values

When options are not specified, the SDK uses these defaults:

| Option | Default |
|--------|---------|
| days   | 7       |
| units  | metric  |
| lang   | en      |

## Configuration

```php
use MeteoFlow\ClientConfig;

$config = (new ClientConfig('YOUR_API_KEY'))
    ->withBaseUrl('https://api.meteoflow.com')  // Base URL
    ->withTimeout(10)                            // Request timeout in seconds
    ->withConnectTimeout(5)                      // Connection timeout in seconds
    ->withUserAgent('my-app/1.0')               // Custom User-Agent
    ->withDebug(true);                          // Enable debug mode
```

## Response Objects

### CurrentWeatherResponse

```php
$response = $client->current($location);

// Place information
$response->place->name;           // City name
$response->place->country;        // Country name
$response->place->lat;            // Latitude
$response->place->lon;            // Longitude

// Current weather
$response->current->temperature;      // Temperature
$response->current->feelsLike;        // Feels like temperature
$response->current->description;      // Weather description
$response->current->humidity;         // Humidity %
$response->current->pressure;         // Pressure
$response->current->windSpeed;        // Wind speed
$response->current->precipitationType; // Precipitation type (rain, snow, none)
$response->current->precipitationMm;  // Precipitation amount in mm
$response->current->iconCode;         // Weather icon code
$response->current->uvIndex;          // UV index value

// Astronomy
$response->astronomy->sunrise;        // Sunrise time (ISO 8601)
$response->astronomy->sunset;         // Sunset time (ISO 8601)
$response->astronomy->dayLength;      // Day length in minutes
$response->astronomy->moonIllumination; // Moon illumination %
```

### Hourly & 3-Hourly Forecast

```php
// Hourly forecast
$response = $client->forecastHourly($location, $options);

// 3-hourly forecast
$response = $client->forecast3Hourly($location, $options);

// Both have same structure
foreach ($response->forecast as $item) {
    $item->date;              // Forecast time (ISO 8601)
    $item->temperature;       // Temperature
    $item->feelsLike;         // Feels like temperature
    $item->description;       // Weather description
    $item->humidity;          // Humidity %
    $item->pressure;          // Pressure
    $item->visibility;        // Visibility in meters
    $item->windSpeed;         // Wind speed
    $item->windDegree;        // Wind direction in degrees
    $item->windGust;          // Wind gust speed
    $item->precipitationType; // Precipitation type
    $item->precipitationMm;   // Precipitation amount in mm
    $item->cloudinessType;    // Cloudiness type (clear, partly cloudy, cloudy)
    $item->iconCode;          // Weather icon code
    $item->iconUrl;           // Weather icon URL
    $item->uvIndex;           // UV index value
    $item->uvDescription;     // UV description (low, moderate, high, very high, extreme)
}

// Astronomy data
foreach ($response->astronomy as $astro) {
    $astro->date;             // Date
    $astro->sunrise;          // Sunrise time
    $astro->sunset;           // Sunset time
}
```

### Daily Forecast

```php
$response = $client->forecastDaily($location, $options);

foreach ($response->daily as $day) {
    $day->date;               // Forecast date (ISO 8601)
    $day->temperatureMin;     // Min temperature
    $day->temperatureMax;     // Max temperature
    $day->description;        // Weather description
    $day->humidityMin;        // Min humidity %
    $day->humidityMax;        // Max humidity %
    $day->pressureMin;        // Min pressure
    $day->pressureMax;        // Max pressure
    $day->visibilityMin;      // Min visibility
    $day->visibilityMax;      // Max visibility
    $day->windSpeed;          // Wind speed
    $day->windDegree;         // Wind direction
    $day->windGust;           // Wind gust speed
    $day->precipitationType;  // Precipitation type
    $day->precipitationMm;    // Precipitation amount in mm
    $day->cloudinessType;     // Cloudiness type
    $day->iconCode;           // Weather icon code
    $day->iconUrl;            // Weather icon URL
    $day->uvIndex;            // UV index value
    $day->uvDescription;      // UV description
}
```

## Error Handling

The SDK throws typed exceptions for different error scenarios:

```php
use MeteoFlow\Exception\ApiException;
use MeteoFlow\Exception\TransportException;
use MeteoFlow\Exception\SerializationException;
use MeteoFlow\Exception\ValidationException;
use MeteoFlow\Exception\MeteoFlowException;

try {
    $response = $client->current($location);
} catch (ValidationException $e) {
    // Invalid input (e.g., days < 1, invalid coordinates)
    echo "Validation error: {$e->getMessage()}\n";
    echo "Field: {$e->getField()}\n";
} catch (TransportException $e) {
    // Network/cURL errors
    echo "Network error: {$e->getMessage()}\n";
    echo "cURL error code: {$e->getCurlErrorCode()}\n";
} catch (ApiException $e) {
    // HTTP 4xx/5xx errors
    echo "API error: {$e->getMessage()}\n";
    echo "HTTP status: {$e->getStatusCode()}\n";
    echo "Error code: {$e->getErrorCode()}\n";
} catch (SerializationException $e) {
    // JSON decode errors
    echo "JSON error: {$e->getMessage()}\n";
} catch (MeteoFlowException $e) {
    // Base exception for all SDK errors
    echo "Error: {$e->getMessage()}\n";
}
```

### Exception Hierarchy

```
MeteoFlowException (base)
├── TransportException      # Network/cURL errors
├── ApiException            # HTTP 4xx/5xx responses
├── SerializationException  # JSON decode failures
└── ValidationException     # Invalid input
```

## Custom HTTP Transport

You can implement custom HTTP transport for framework integrations:

```php
use MeteoFlow\Transport\HttpTransportInterface;

class MyCustomTransport implements HttpTransportInterface
{
    public function request($method, $url, array $headers = array())
    {
        // Your implementation
        return array(
            'statusCode' => 200,
            'body' => '...',
            'headers' => array(),
        );
    }
}

$client = new WeatherClient($config, new MyCustomTransport());
```

## Examples

See the [examples](examples/) directory for complete usage examples:

- [Current weather by slug](examples/current_by_slug.php)
- [Current weather by coordinates](examples/current_by_coords.php)
- [Hourly forecast](examples/forecast_hourly.php)
- [3-hourly forecast](examples/forecast_3hourly.php)
- [Daily forecast](examples/forecast_daily.php)

## Testing

Run all tests:

```bash
./vendor/bin/phpunit
```

## PHP Version Compatibility

The SDK is designed to work with PHP 5.6 and higher:

- **PHP 5.6+**: Full compatibility, no typed properties or union types
- **PHP 7.x**: Works without modifications
- **PHP 8.x**: Works without modifications, benefits from JIT

## Framework Integration

This SDK is designed as a standalone package. For framework-specific integrations:

- **Laravel**: See `meteoflow/laravel` (coming soon)
- **Symfony**: See `meteoflow/symfony` (coming soon)

The SDK provides `HttpTransportInterface` as an extension point for custom transport implementations.

## License

MIT License. See [LICENSE](LICENSE) for details.
