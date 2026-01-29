<?php

namespace MeteoFlow\Location;

use MeteoFlow\Exception\ValidationException;

/**
 * Location identified by a slug string.
 */
class LocationSlug extends Location
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @param string $slug
     * @throws ValidationException If slug is empty
     */
    public function __construct($slug)
    {
        if (!is_string($slug) || trim($slug) === '') {
            throw ValidationException::forField('slug', $slug, 'must be a non-empty string');
        }

        $this->slug = trim($slug);
    }

    /**
     * Get the slug value.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function toQueryParams()
    {
        return array('slug' => $this->slug);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->slug;
    }
}
