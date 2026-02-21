<?php

namespace MeteoFlow\Location;

use MeteoFlow\Exception\ValidationException;

/**
 * Location identified by an IP address.
 */
class LocationIp extends Location
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @param string $ip
     * @throws ValidationException If IP is invalid
     */
    public function __construct($ip)
    {
        if (!is_string($ip) || trim($ip) === '') {
            throw ValidationException::forField('ip', $ip, 'must be a non-empty string');
        }

        $ip = trim($ip);

        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            throw ValidationException::forField('ip', $ip, 'must be a valid IP address');
        }

        $this->ip = $ip;
    }

    /**
     * Get the IP value.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * {@inheritdoc}
     */
    public function toQueryParams()
    {
        return array('ip' => $this->ip);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->ip;
    }
}
