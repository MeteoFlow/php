<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Exception\ValidationException;
use MeteoFlow\Response\CitiesResponse;
use MeteoFlow\Response\CountriesResponse;
use MeteoFlow\WeatherClient;
use MeteoFlow\ClientConfig;
use PHPUnit\Framework\TestCase;

class GeographyResponseTest extends TestCase
{
    // -------------------------------------------------------------------------
    // CountriesResponse
    // -------------------------------------------------------------------------

    public function testCountriesResponseParsing()
    {
        $data = array(
            array('country_slug' => 'germany', 'country_name' => 'Germany', 'country_code' => 'DE'),
            array('country_slug' => 'france',  'country_name' => 'France',  'country_code' => 'FR'),
            array('country_slug' => 'russia',  'country_name' => 'Russia',  'country_code' => 'RU'),
        );

        $response = CountriesResponse::fromArray($data);

        $this->assertEquals(3, $response->getCountriesCount());
        $this->assertCount(3, $response->countries);

        $this->assertEquals('germany', $response->countries[0]->slug);
        $this->assertEquals('Germany', $response->countries[0]->name);
        $this->assertEquals('DE',      $response->countries[0]->code);

        $this->assertEquals('france', $response->countries[1]->slug);
        $this->assertEquals('FR',     $response->countries[1]->code);

        $this->assertEquals('russia', $response->countries[2]->slug);
        $this->assertEquals('RU',     $response->countries[2]->code);
    }

    public function testCountriesResponseEmpty()
    {
        $response = CountriesResponse::fromArray(array());

        $this->assertEquals(0, $response->getCountriesCount());
        $this->assertCount(0, $response->countries);
    }

    // -------------------------------------------------------------------------
    // CitiesResponse
    // -------------------------------------------------------------------------

    public function testCitiesResponseParsing()
    {
        $data = array(
            array(
                'country'          => 'RU',
                'slug'             => 'russia-moscow',
                'timezone_offset'  => 180,
                'latitude'         => 55.76,
                'longitude'        => 37.62,
                'city_name'        => 'Moscow',
                'country_name'     => 'Russia',
                'region_name'      => 'Moskva',
            ),
            array(
                'country'          => 'RU',
                'slug'             => 'russia-saint-petersburg',
                'timezone_offset'  => 180,
                'latitude'         => 59.94,
                'longitude'        => 30.31,
                'city_name'        => 'Saint Petersburg',
                'country_name'     => 'Russia',
                'region_name'      => 'Sankt-Peterburg',
            ),
        );

        $response = CitiesResponse::fromArray($data);

        $this->assertEquals(2, $response->getCitiesCount());
        $this->assertCount(2, $response->cities);

        $this->assertEquals('russia-moscow', $response->cities[0]->slug);
        $this->assertEquals('Moscow',        $response->cities[0]->name);
        $this->assertEquals('RU',            $response->cities[0]->countryCode);
        $this->assertEquals('Russia',        $response->cities[0]->country);
        $this->assertEquals('Moskva',        $response->cities[0]->region);
        $this->assertEquals(55.76,           $response->cities[0]->lat);
        $this->assertEquals(37.62,           $response->cities[0]->lon);
        $this->assertEquals(180,             $response->cities[0]->timezoneOffset);

        $this->assertEquals('Saint Petersburg', $response->cities[1]->name);
        $this->assertEquals('russia-saint-petersburg', $response->cities[1]->slug);
    }

    public function testCitiesSearchResponseParsing()
    {
        $data = array(
            array(
                'country'         => 'DE',
                'slug'            => 'germany-berlin',
                'timezone_offset' => 60,
                'latitude'        => 52.52,
                'longitude'       => 13.41,
                'city_name'       => 'Berlin',
                'country_name'    => 'Germany',
                'region_name'     => 'Berlin',
            ),
            array(
                'country'         => 'US',
                'slug'            => 'united-states-berlin',
                'timezone_offset' => -300,
                'latitude'        => 44.49,
                'longitude'       => -71.26,
                'city_name'       => 'Berlin',
                'country_name'    => 'United States',
                'region_name'     => 'New Hampshire',
            ),
        );

        $response = CitiesResponse::fromArray($data);

        $this->assertEquals(2, $response->getCitiesCount());

        $this->assertEquals('germany-berlin', $response->cities[0]->slug);
        $this->assertEquals('Berlin',         $response->cities[0]->name);
        $this->assertEquals('DE',             $response->cities[0]->countryCode);
        $this->assertEquals(52.52,            $response->cities[0]->lat);
        $this->assertEquals(13.41,            $response->cities[0]->lon);

        $this->assertEquals('United States',       $response->cities[1]->country);
        $this->assertEquals('New Hampshire',        $response->cities[1]->region);
    }

    public function testCitiesResponseEmpty()
    {
        $response = CitiesResponse::fromArray(array());

        $this->assertEquals(0, $response->getCitiesCount());
    }

    // -------------------------------------------------------------------------
    // WeatherClient validation
    // -------------------------------------------------------------------------

    public function testCitiesByCountryValidatesEmptyCode()
    {
        $client = new WeatherClient(new ClientConfig('test-key'));

        try {
            $client->citiesByCountry('');
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('country_code', $e->getMessage());
        }
    }

    public function testSearchCitiesValidatesEmptyQuery()
    {
        $client = new WeatherClient(new ClientConfig('test-key'));

        try {
            $client->searchCities('');
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('"q"', $e->getMessage());
        }
    }

    public function testSearchCitiesValidatesNegativeLimit()
    {
        $client = new WeatherClient(new ClientConfig('test-key'));

        try {
            $client->searchCities('Berlin', 0);
            $this->fail('Expected ValidationException');
        } catch (ValidationException $e) {
            $this->assertStringContainsString('limit', $e->getMessage());
        }
    }
}
