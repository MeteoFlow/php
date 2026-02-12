<?php

namespace MeteoFlow\Response;

use MeteoFlow\Response\Model\Country;

/**
 * Response from the countries list API endpoint.
 */
class CountriesResponse
{
    /**
     * @var Country[]
     */
    public $countries = array();

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
                $response->countries[] = Country::fromArray($item);
            }
        }

        return $response;
    }

    /**
     * @return int
     */
    public function getCountriesCount()
    {
        return count($this->countries);
    }
}
