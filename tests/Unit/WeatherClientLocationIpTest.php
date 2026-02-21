<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\ClientConfig;
use MeteoFlow\Location\Location;
use MeteoFlow\Transport\HttpTransportInterface;
use MeteoFlow\WeatherClient;
use PHPUnit\Framework\TestCase;

class WeatherClientLocationIpTest extends TestCase
{
    public function testCurrentUsesIpLocationParams()
    {
        $transport = new TestTransport();
        $client = new WeatherClient(new ClientConfig('test-key'), $transport);

        $client->current(Location::fromIp('8.8.8.8'));

        $params = $this->getQueryParams($transport->lastUrl);

        $this->assertEquals('8.8.8.8', $params['ip']);
        $this->assertEquals('test-key', $params['key']);
        $this->assertArrayNotHasKey('slug', $params);
        $this->assertArrayNotHasKey('lat', $params);
        $this->assertArrayNotHasKey('lon', $params);
    }

    public function testForecastUsesIpLocationParamsWithDefaults()
    {
        $transport = new TestTransport();
        $client = new WeatherClient(new ClientConfig('test-key'), $transport);

        $client->forecastDaily(Location::fromIp('8.8.8.8'));

        $params = $this->getQueryParams($transport->lastUrl);

        $this->assertEquals('8.8.8.8', $params['ip']);
        $this->assertEquals('test-key', $params['key']);
        $this->assertEquals(7, (int) $params['days']);
        $this->assertEquals('en', $params['lang']);
        $this->assertEquals('metric', $params['units']);
        $this->assertArrayNotHasKey('slug', $params);
        $this->assertArrayNotHasKey('lat', $params);
        $this->assertArrayNotHasKey('lon', $params);
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

class TestTransport implements HttpTransportInterface
{
    public $lastUrl;

    public function request($method, $url, array $headers = array())
    {
        $this->lastUrl = $url;

        return array(
            'statusCode' => 200,
            'body' => '{}',
            'headers' => array(),
        );
    }
}
