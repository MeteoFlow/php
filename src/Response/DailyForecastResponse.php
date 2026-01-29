<?php

namespace MeteoFlow\Response;

use MeteoFlow\Response\Model\Astronomy;
use MeteoFlow\Response\Model\DailyForecast;
use MeteoFlow\Response\Model\Place;

/**
 * Response from the daily forecast API endpoint.
 */
class DailyForecastResponse
{
    /**
     * @var Place|null
     */
    public $place;

    /**
     * @var DailyForecast[]
     */
    public $daily = array();

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

        if (isset($data['daily']) && is_array($data['daily'])) {
            foreach ($data['daily'] as $item) {
                if (is_array($item)) {
                    $response->daily[] = DailyForecast::fromArray($item);
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
     * Get daily forecast count.
     *
     * @return int
     */
    public function getDailyCount()
    {
        return count($this->daily);
    }
}
