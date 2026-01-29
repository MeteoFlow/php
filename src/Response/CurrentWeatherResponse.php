<?php

namespace MeteoFlow\Response;

use MeteoFlow\Response\Model\Astronomy;
use MeteoFlow\Response\Model\CurrentWeather;
use MeteoFlow\Response\Model\Place;

/**
 * Response from the current weather API endpoint.
 */
class CurrentWeatherResponse
{
    /**
     * @var Place|null
     */
    public $place;

    /**
     * @var CurrentWeather|null
     */
    public $current;

    /**
     * @var Astronomy|null
     */
    public $astronomy;

    /**
     * Create response from API data array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $response = new self();

        if (isset($data['place']) && is_array($data['place'])) {
            $response->place = Place::fromArray($data['place']);
        }

        if (isset($data['current']) && is_array($data['current'])) {
            $response->current = CurrentWeather::fromArray($data['current']);
        }

        if (isset($data['astronomy']) && is_array($data['astronomy'])) {
            $response->astronomy = Astronomy::fromArray($data['astronomy']);
        }

        return $response;
    }
}
