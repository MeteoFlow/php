<?php

namespace MeteoFlow\Response;

use MeteoFlow\Response\Model\Astronomy;
use MeteoFlow\Response\Model\Place;
use MeteoFlow\Response\Model\ThreeHourlyForecast;

/**
 * Response from the 3-hourly forecast API endpoint.
 */
class ThreeHourlyForecastResponse
{
    /**
     * @var Place|null
     */
    public $place;

    /**
     * @var ThreeHourlyForecast[]
     */
    public $forecast = array();

    /**
     * @var Astronomy[]
     */
    public $astronomy = array();

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

        if (isset($data['forecast']) && is_array($data['forecast'])) {
            foreach ($data['forecast'] as $item) {
                if (is_array($item)) {
                    $response->forecast[] = ThreeHourlyForecast::fromArray($item);
                }
            }
        }

        if (isset($data['astronomy']) && is_array($data['astronomy'])) {
            foreach ($data['astronomy'] as $item) {
                if (is_array($item)) {
                    $response->astronomy[] = Astronomy::fromArray($item);
                }
            }
        }

        return $response;
    }

    /**
     * Get forecast count.
     *
     * @return int
     */
    public function getForecastCount()
    {
        return count($this->forecast);
    }
}
