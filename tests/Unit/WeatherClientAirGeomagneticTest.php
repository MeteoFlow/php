<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\Options\AirQualityOptions;
use MeteoFlow\Transport\HttpTransportInterface;
use MeteoFlow\WeatherClient;
use PHPUnit\Framework\TestCase;

class WeatherClientAirGeomagneticTest extends TestCase
{
    public function testAirQualityUsesDefaultDays()
    {
        $transport = new AirGeomagneticTestTransport();
        $client = new WeatherClient(new ClientConfig('test-key'), $transport);

        $client->airQuality(Location::fromSlug('united-kingdom-london'));

        $params = $this->getQueryParams($transport->lastUrl);

        $this->assertEquals('united-kingdom-london', $params['slug']);
        $this->assertEquals('test-key', $params['key']);
        $this->assertEquals(7, (int) $params['days']);
        $this->assertArrayNotHasKey('units', $params);
        $this->assertArrayNotHasKey('lang', $params);
    }

    public function testAirQualityUsesCustomDays()
    {
        $transport = new AirGeomagneticTestTransport();
        $client = new WeatherClient(new ClientConfig('test-key'), $transport);

        $client->airQuality(
            Location::fromSlug('united-kingdom-london'),
            AirQualityOptions::create()->setDays(6)
        );

        $params = $this->getQueryParams($transport->lastUrl);

        $this->assertEquals(6, (int) $params['days']);
    }

    public function testGeomagneticUsesOnlyLocation()
    {
        $transport = new AirGeomagneticTestTransport();
        $client = new WeatherClient(new ClientConfig('test-key'), $transport);

        $client->geomagnetic(Location::fromSlug('united-kingdom-london'));

        $params = $this->getQueryParams($transport->lastUrl);

        $this->assertEquals('united-kingdom-london', $params['slug']);
        $this->assertEquals('test-key', $params['key']);
        $this->assertArrayNotHasKey('days', $params);
        $this->assertArrayNotHasKey('units', $params);
        $this->assertArrayNotHasKey('lang', $params);
    }

    private function getQueryParams($url)
    {
        $parts = parse_url($url);
        $params = array();

        if (isset($parts['query'])) {
            parse_str($parts['query'], $params);
        }

        return $params;
    }
}

class AirGeomagneticTestTransport implements HttpTransportInterface
{
    public $lastUrl;

    public function request($method, $url, array $headers = array())
    {
        $this->lastUrl = $url;

        return array(
            'statusCode' => 200,
            'body' => '[]',
            'headers' => array(),
        );
    }
}
