<?php

namespace MeteoFlow\Response;

use MeteoFlow\Response\Model\AirQualityDay;

/**
 * Response from the air quality API endpoint.
 */
class AirQualityResponse
{
    /**
     * @var AirQualityDay[]
     */
    public $items = array();

    /**
     * Create response from API data array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $response = new self();

        foreach ($data as $item) {
            if (is_array($item)) {
                $response->items[] = AirQualityDay::fromArray($item);
            }
        }

        return $response;
    }

    /**
     * Get items count.
     *
     * @return int
     */
    public function getItemsCount()
    {
        return count($this->items);
    }
}
