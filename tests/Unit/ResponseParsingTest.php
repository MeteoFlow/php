<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Response\CurrentWeatherResponse;
use MeteoFlow\Response\DailyForecastResponse;
use MeteoFlow\Response\HourlyForecastResponse;
use MeteoFlow\Response\ThreeHourlyForecastResponse;
use PHPUnit\Framework\TestCase;

class ResponseParsingTest extends TestCase
{
    public function testCurrentWeatherResponseParsing()
    {
        $data = array(
            'place' => array(
                'country' => 'GB',
                'timezone_offset' => 0,
                'latitude' => 51.5074,
                'longitude' => -0.1278,
                'city_name' => 'London',
                'country_name' => 'United Kingdom',
                'region_name' => 'England',
            ),
            'current' => array(
                'date' => '2026-01-28T17:00:00Z',
                'temperature_air' => -11.97,
                'temperature_air_feels_like' => -11.97,
                'description' => 'cloudy, snow',
                'humidity' => 85,
                'pressure' => 742,
                'visibility' => 129,
                'wind' => array(
                    'speed' => 1,
                    'degree' => 90,
                    'gust' => 4,
                ),
                'precipitation' => array(
                    'type' => 'snow',
                    'mm' => 0.16,
                ),
                'cloudiness' => array(
                    'type' => 'cloudy',
                ),
                'icon' => array(
                    'code' => 'night_cloudy_light_snow',
                    'url' => '//meteoflow.com/images/weather-icon/dark/v2/night_cloudy_light_snow.svg',
                ),
                'uvindex' => array(
                    'val' => 0,
                    'description' => 'low',
                ),
            ),
            'astronomy' => array(
                'date' => '2026-01-28',
                'sunrise' => '2026-01-28T05:30:51Z',
                'sunset' => '2026-01-28T13:54:40Z',
                'day_length' => 503,
                'moon_angle' => 112.1,
                'moon_illumination' => 68.85,
                'moon_phase_code' => 2,
            ),
        );

        $response = CurrentWeatherResponse::fromArray($data);

        // Place assertions
        $this->assertNotNull($response->place);
        $this->assertEquals('London', $response->place->name);
        $this->assertEquals('United Kingdom', $response->place->country);
        $this->assertEquals('GB', $response->place->countryCode);
        $this->assertEquals(51.5074, $response->place->lat);
        $this->assertEquals(-0.1278, $response->place->lon);

        // Current weather assertions
        $this->assertNotNull($response->current);
        $this->assertEquals(-11.97, $response->current->temperature);
        $this->assertEquals(-11.97, $response->current->feelsLike);
        $this->assertEquals(85, $response->current->humidity);
        $this->assertEquals('cloudy, snow', $response->current->description);
        $this->assertEquals(1, $response->current->windSpeed);
        $this->assertEquals(90, $response->current->windDegree);
        $this->assertEquals('snow', $response->current->precipitationType);
        $this->assertEquals('night_cloudy_light_snow', $response->current->iconCode);

        // Astronomy assertions
        $this->assertNotNull($response->astronomy);
        $this->assertEquals('2026-01-28T05:30:51Z', $response->astronomy->sunrise);
        $this->assertEquals('2026-01-28T13:54:40Z', $response->astronomy->sunset);
        $this->assertEquals(503, $response->astronomy->dayLength);
    }

    public function testHourlyForecastResponseParsing()
    {
        $data = array(
            'place' => array(
                'city_name' => 'Usakos',
                'country_name' => 'Namibia',
            ),
            'forecast' => array(
                array(
                    'date' => '2026-01-28T22:00:00Z',
                    'temperature_air' => 22.54,
                    'temperature_air_feels_like' => 22.54,
                    'description' => 'cloudy',
                    'precipitation' => array(
                        'type' => 'rain',
                        'mm' => 0.0,
                    ),
                    'cloudiness' => array(
                        'type' => 'cloudy',
                    ),
                    'icon' => array(
                        'code' => 'night_cloudy',
                        'url' => '//meteoflow.com/images/weather-icon/dark/v2/night_cloudy.svg',
                    ),
                    'uvindex' => array(
                        'val' => 0,
                        'description' => 'low',
                    ),
                    'pressure' => 667,
                    'humidity' => 70,
                    'visibility' => 24134,
                    'wind' => array(
                        'speed' => 0,
                        'degree' => 180,
                        'gust' => 2,
                    ),
                ),
                array(
                    'date' => '2026-01-29T12:00:00Z',
                    'temperature_air' => 32.01,
                    'temperature_air_feels_like' => 32.01,
                    'description' => 'cloudy',
                    'precipitation' => array(
                        'type' => 'none',
                        'mm' => 0.0,
                    ),
                    'cloudiness' => array(
                        'type' => 'cloudy',
                    ),
                    'icon' => array(
                        'code' => 'day_cloudy',
                        'url' => '//meteoflow.com/images/weather-icon/dark/v2/day_cloudy.svg',
                    ),
                    'uvindex' => array(
                        'val' => 11,
                        'description' => 'extreme',
                    ),
                    'pressure' => 666,
                    'humidity' => 37,
                    'visibility' => 24135,
                    'wind' => array(
                        'speed' => 4,
                        'degree' => 45,
                        'gust' => 8,
                    ),
                ),
            ),
            'astronomy' => array(
                array(
                    'date' => '2026-01-29',
                    'sunrise' => '2026-01-29T04:36:53Z',
                    'sunset' => '2026-01-29T17:44:22Z',
                    'day_length' => 787,
                    'moon_illumination' => 79.43,
                    'moon_phase_code' => 3,
                ),
            ),
        );

        $response = HourlyForecastResponse::fromArray($data);

        $this->assertNotNull($response->place);
        $this->assertEquals('Usakos', $response->place->name);

        $this->assertEquals(2, $response->getForecastCount());

        // First forecast
        $this->assertEquals('2026-01-28T22:00:00Z', $response->forecast[0]->date);
        $this->assertEquals(22.54, $response->forecast[0]->temperature);
        $this->assertEquals(22.54, $response->forecast[0]->feelsLike);
        $this->assertEquals('cloudy', $response->forecast[0]->description);
        $this->assertEquals('rain', $response->forecast[0]->precipitationType);
        $this->assertEquals(0.0, $response->forecast[0]->precipitationMm);
        $this->assertEquals('cloudy', $response->forecast[0]->cloudinessType);
        $this->assertEquals('night_cloudy', $response->forecast[0]->iconCode);
        $this->assertEquals(0, $response->forecast[0]->uvIndex);
        $this->assertEquals('low', $response->forecast[0]->uvDescription);
        $this->assertEquals(667, $response->forecast[0]->pressure);
        $this->assertEquals(70, $response->forecast[0]->humidity);
        $this->assertEquals(24134, $response->forecast[0]->visibility);
        $this->assertEquals(0, $response->forecast[0]->windSpeed);
        $this->assertEquals(180, $response->forecast[0]->windDegree);
        $this->assertEquals(2, $response->forecast[0]->windGust);

        // Second forecast
        $this->assertEquals('2026-01-29T12:00:00Z', $response->forecast[1]->date);
        $this->assertEquals(32.01, $response->forecast[1]->temperature);
        $this->assertEquals('cloudy', $response->forecast[1]->description);
        $this->assertEquals(11, $response->forecast[1]->uvIndex);
        $this->assertEquals('extreme', $response->forecast[1]->uvDescription);

        $this->assertCount(1, $response->astronomy);
        $this->assertEquals('2026-01-29', $response->astronomy[0]->date);
        $this->assertEquals(787, $response->astronomy[0]->dayLength);
    }

    public function testThreeHourlyForecastResponseParsing()
    {
        $data = array(
            'place' => array(
                'city_name' => 'Usakos',
                'country_name' => 'Namibia',
            ),
            'forecast' => array(
                array(
                    'date' => '2026-01-28T21:00:00Z',
                    'temperature_air' => 23.14,
                    'temperature_air_feels_like' => 23.14,
                    'description' => 'clear sky',
                    'precipitation' => array(
                        'type' => 'none',
                        'mm' => 0.0,
                    ),
                    'cloudiness' => array(
                        'type' => 'clear',
                    ),
                    'icon' => array(
                        'code' => 'night_clear',
                        'url' => '//meteoflow.com/images/weather-icon/dark/v2/night_clear.svg',
                    ),
                    'uvindex' => array(
                        'val' => 0,
                        'description' => 'low',
                    ),
                    'pressure' => 673,
                    'humidity' => 47,
                    'visibility' => 24134,
                    'wind' => array(
                        'speed' => 0,
                        'degree' => 225,
                        'gust' => 1,
                    ),
                ),
                array(
                    'date' => '2026-01-29T12:00:00Z',
                    'temperature_air' => 32.47,
                    'temperature_air_feels_like' => 32.47,
                    'description' => 'cloudy, rain',
                    'precipitation' => array(
                        'type' => 'rain',
                        'mm' => 0.017,
                    ),
                    'cloudiness' => array(
                        'type' => 'cloudy',
                    ),
                    'icon' => array(
                        'code' => 'day_cloudy_light_rain',
                        'url' => '//meteoflow.com/images/weather-icon/dark/v2/day_cloudy_light_rain.svg',
                    ),
                    'uvindex' => array(
                        'val' => 8,
                        'description' => 'very high',
                    ),
                    'pressure' => 666,
                    'humidity' => 35,
                    'visibility' => 21822,
                    'wind' => array(
                        'speed' => 4,
                        'degree' => 45,
                        'gust' => 7,
                    ),
                ),
            ),
            'astronomy' => array(
                array(
                    'date' => '2026-01-29',
                    'sunrise' => '2026-01-29T04:36:53Z',
                    'sunset' => '2026-01-29T17:44:22Z',
                    'day_length' => 787,
                    'moon_illumination' => 79.43,
                    'moon_phase_code' => 3,
                ),
            ),
        );

        $response = ThreeHourlyForecastResponse::fromArray($data);

        $this->assertEquals(2, $response->getForecastCount());

        // First forecast
        $this->assertEquals('2026-01-28T21:00:00Z', $response->forecast[0]->date);
        $this->assertEquals(23.14, $response->forecast[0]->temperature);
        $this->assertEquals(23.14, $response->forecast[0]->feelsLike);
        $this->assertEquals('clear sky', $response->forecast[0]->description);
        $this->assertEquals('none', $response->forecast[0]->precipitationType);
        $this->assertEquals(0.0, $response->forecast[0]->precipitationMm);
        $this->assertEquals('clear', $response->forecast[0]->cloudinessType);
        $this->assertEquals('night_clear', $response->forecast[0]->iconCode);
        $this->assertEquals(0, $response->forecast[0]->uvIndex);
        $this->assertEquals('low', $response->forecast[0]->uvDescription);
        $this->assertEquals(673, $response->forecast[0]->pressure);
        $this->assertEquals(47, $response->forecast[0]->humidity);
        $this->assertEquals(24134, $response->forecast[0]->visibility);
        $this->assertEquals(0, $response->forecast[0]->windSpeed);
        $this->assertEquals(225, $response->forecast[0]->windDegree);
        $this->assertEquals(1, $response->forecast[0]->windGust);

        // Second forecast
        $this->assertEquals('2026-01-29T12:00:00Z', $response->forecast[1]->date);
        $this->assertEquals(32.47, $response->forecast[1]->temperature);
        $this->assertEquals('cloudy, rain', $response->forecast[1]->description);
        $this->assertEquals('rain', $response->forecast[1]->precipitationType);
        $this->assertEquals(8, $response->forecast[1]->uvIndex);
        $this->assertEquals('very high', $response->forecast[1]->uvDescription);

        $this->assertCount(1, $response->astronomy);
        $this->assertEquals(787, $response->astronomy[0]->dayLength);
    }

    public function testDailyForecastResponseParsing()
    {
        $data = array(
            'place' => array(
                'city_name' => 'London',
                'country_name' => 'United Kingdom',
            ),
            'daily' => array(
                array(
                    'date' => '2026-01-29T00:00:00Z',
                    'temperature_air' => array(
                        'max' => -10.72,
                        'min' => -12.22,
                    ),
                    'description' => 'cloudy, snow',
                    'precipitation' => array(
                        'type' => 'snow',
                        'mm' => 6.308,
                    ),
                    'cloudiness' => array(
                        'type' => 'cloudy',
                    ),
                    'icon' => array(
                        'code' => 'day_cloudy_light_snow',
                        'url' => '//meteoflow.com/images/weather-icon/dark/v2/day_cloudy_light_snow.svg',
                    ),
                    'uvindex' => array(
                        'val' => 0,
                        'description' => 'low',
                    ),
                    'humidity' => array(
                        'max' => 85,
                        'min' => 82,
                    ),
                    'visibility' => array(
                        'max' => 1922,
                        'min' => 127,
                    ),
                    'pressure' => array(
                        'max' => 743,
                        'min' => 741,
                    ),
                    'wind' => array(
                        'speed' => 2,
                        'degree' => 45,
                        'gust' => 6,
                    ),
                ),
                array(
                    'date' => '2026-01-30T00:00:00Z',
                    'temperature_air' => array(
                        'max' => -12.32,
                        'min' => -18.93,
                    ),
                    'description' => 'partly cloudy, snow',
                    'precipitation' => array(
                        'type' => 'snow',
                        'mm' => 0.041,
                    ),
                    'cloudiness' => array(
                        'type' => 'partly cloudy',
                    ),
                    'icon' => array(
                        'code' => 'day_partlycloudy_light_snow',
                        'url' => '//meteoflow.com/images/weather-icon/dark/v2/day_partlycloudy_light_snow.svg',
                    ),
                    'uvindex' => array(
                        'val' => 0,
                        'description' => 'low',
                    ),
                    'humidity' => array(
                        'max' => 82,
                        'min' => 77,
                    ),
                    'visibility' => array(
                        'max' => 24135,
                        'min' => 2338,
                    ),
                    'pressure' => array(
                        'max' => 752,
                        'min' => 744,
                    ),
                    'wind' => array(
                        'speed' => 2,
                        'degree' => 0,
                        'gust' => 6,
                    ),
                ),
            ),
            'astronomy' => array(
                array(
                    'date' => '2026-01-29',
                    'sunrise' => '2026-01-29T05:29:06Z',
                    'sunset' => '2026-01-29T13:56:47Z',
                    'day_length' => 507,
                    'moon_angle' => 125.55,
                    'moon_illumination' => 79.04,
                    'moon_phase_code' => 3,
                ),
            ),
        );

        $response = DailyForecastResponse::fromArray($data);

        $this->assertNotNull($response->place);
        $this->assertEquals('London', $response->place->name);

        $this->assertEquals(2, $response->getDailyCount());

        // First day
        $this->assertEquals('2026-01-29T00:00:00Z', $response->daily[0]->date);
        $this->assertEquals(-12.22, $response->daily[0]->temperatureMin);
        $this->assertEquals(-10.72, $response->daily[0]->temperatureMax);
        $this->assertEquals('cloudy, snow', $response->daily[0]->description);
        $this->assertEquals('snow', $response->daily[0]->precipitationType);
        $this->assertEquals(6.308, $response->daily[0]->precipitationMm);
        $this->assertEquals('cloudy', $response->daily[0]->cloudinessType);
        $this->assertEquals('day_cloudy_light_snow', $response->daily[0]->iconCode);
        $this->assertEquals(0, $response->daily[0]->uvIndex);
        $this->assertEquals('low', $response->daily[0]->uvDescription);
        $this->assertEquals(82, $response->daily[0]->humidityMin);
        $this->assertEquals(85, $response->daily[0]->humidityMax);
        $this->assertEquals(127, $response->daily[0]->visibilityMin);
        $this->assertEquals(1922, $response->daily[0]->visibilityMax);
        $this->assertEquals(741, $response->daily[0]->pressureMin);
        $this->assertEquals(743, $response->daily[0]->pressureMax);
        $this->assertEquals(2, $response->daily[0]->windSpeed);
        $this->assertEquals(45, $response->daily[0]->windDegree);
        $this->assertEquals(6, $response->daily[0]->windGust);

        // Second day
        $this->assertEquals('2026-01-30T00:00:00Z', $response->daily[1]->date);
        $this->assertEquals(-18.93, $response->daily[1]->temperatureMin);
        $this->assertEquals('partly cloudy, snow', $response->daily[1]->description);

        $this->assertCount(1, $response->astronomy);
        $this->assertEquals(79.04, $response->astronomy[0]->moonIllumination);
    }

    public function testEmptyResponseHandling()
    {
        $response = CurrentWeatherResponse::fromArray(array());

        $this->assertNull($response->place);
        $this->assertNull($response->current);
        $this->assertNull($response->astronomy);
    }

    public function testPartialDataHandling()
    {
        $data = array(
            'place' => array(
                'city_name' => 'London',
                // Missing other fields
            ),
            'current' => array(
                'temperature_air' => 15.0,
                // Missing other fields
            ),
            // Missing astronomy
        );

        $response = CurrentWeatherResponse::fromArray($data);

        $this->assertNotNull($response->place);
        $this->assertEquals('London', $response->place->name);
        $this->assertNull($response->place->country);
        $this->assertNull($response->place->lat);

        $this->assertNotNull($response->current);
        $this->assertEquals(15.0, $response->current->temperature);
        $this->assertNull($response->current->humidity);

        $this->assertNull($response->astronomy);
    }

    public function testNestedObjectsParsing()
    {
        // Test that nested objects (wind, precipitation, etc.) are properly parsed
        $data = array(
            'current' => array(
                'temperature_air' => 22.0,
                'wind' => array(
                    'speed' => 5.5,
                    'degree' => 180,
                    'gust' => 8.0,
                ),
                'precipitation' => array(
                    'type' => 'rain',
                    'mm' => 2.5,
                ),
                'cloudiness' => array(
                    'type' => 'partly_cloudy',
                ),
                'icon' => array(
                    'code' => 'day_rain',
                    'url' => '//example.com/icon.svg',
                ),
                'uvindex' => array(
                    'val' => 5,
                    'description' => 'moderate',
                ),
            ),
        );

        $response = CurrentWeatherResponse::fromArray($data);

        $this->assertEquals(22.0, $response->current->temperature);
        $this->assertEquals(5.5, $response->current->windSpeed);
        $this->assertEquals(180, $response->current->windDegree);
        $this->assertEquals(8.0, $response->current->windGust);
        $this->assertEquals('rain', $response->current->precipitationType);
        $this->assertEquals(2.5, $response->current->precipitationMm);
        $this->assertEquals('partly_cloudy', $response->current->cloudinessType);
        $this->assertEquals('day_rain', $response->current->iconCode);
        $this->assertEquals('//example.com/icon.svg', $response->current->iconUrl);
        $this->assertEquals(5, $response->current->uvIndex);
        $this->assertEquals('moderate', $response->current->uvDescription);
    }
}
