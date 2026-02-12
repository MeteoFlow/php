<?php

namespace MeteoFlow\Response;

use MeteoFlow\Response\Model\Place;

/**
 * Response from the cities list and city search API endpoints.
 */
class CitiesResponse
{
    /**
     * @var Place[]
     */
    public $cities = array();

    /**
     * Create response from API data array (top-level JSON array).
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $response = new self();

        foreach ($data as $item) {
            if (is_array($item)) {
                $response->cities[] = Place::fromArray($item);
            }
        }

        return $response;
    }

    /**
     * @return int
     */
    public function getCitiesCount()
    {
        return count($this->cities);
    }
}
