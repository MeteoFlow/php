<?php

namespace MeteoFlow\Response\Model;

/**
 * Country item from geography API response.
 */
class Country
{
    /**
     * @var string|null
     */
    public $slug;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var string|null ISO 3166-1 alpha-2 code (e.g. "GB")
     */
    public $code;

    /**
     * Create Country from API response array.
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data)
    {
        $country = new self();

        $country->slug = isset($data['country_slug']) ? $data['country_slug'] : null;
        $country->name = isset($data['country_name']) ? $data['country_name'] : null;
        $country->code = isset($data['country_code']) ? $data['country_code'] : null;

        return $country;
    }
}
