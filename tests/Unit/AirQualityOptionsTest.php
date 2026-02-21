<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Exception\ValidationException;
use MeteoFlow\Options\AirQualityOptions;
use PHPUnit\Framework\TestCase;

class AirQualityOptionsTest extends TestCase
{
    public function testDefaultOptionsAreNull()
    {
        $options = new AirQualityOptions();

        $this->assertNull($options->getDays());
    }

    public function testSetDays()
    {
        $options = AirQualityOptions::create()->setDays(6);

        $this->assertEquals(6, $options->getDays());
    }

    public function testSetDaysRejectsZero()
    {
        $this->expectException(ValidationException::class);

        AirQualityOptions::create()->setDays(0);
    }

    public function testSetDaysRejectsAboveMax()
    {
        $this->expectException(ValidationException::class);

        AirQualityOptions::create()->setDays(9);
    }

    public function testToQueryParamsWithDays()
    {
        $options = AirQualityOptions::create()->setDays(5);

        $params = $options->toQueryParams();

        $this->assertEquals(array('days' => 5), $params);
    }

    public function testToQueryParamsEmptyWhenNoOptionsSet()
    {
        $options = new AirQualityOptions();

        $params = $options->toQueryParams();

        $this->assertEquals(array(), $params);
    }
}
