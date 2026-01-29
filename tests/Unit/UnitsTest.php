<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Options\Units;
use PHPUnit\Framework\TestCase;

class UnitsTest extends TestCase
{
    public function testMetricConstant()
    {
        $this->assertEquals('metric', Units::METRIC);
    }

    public function testImperialConstant()
    {
        $this->assertEquals('imperial', Units::IMPERIAL);
    }

    public function testIsValidWithMetric()
    {
        $this->assertTrue(Units::isValid(Units::METRIC));
        $this->assertTrue(Units::isValid('metric'));
    }

    public function testIsValidWithImperial()
    {
        $this->assertTrue(Units::isValid(Units::IMPERIAL));
        $this->assertTrue(Units::isValid('imperial'));
    }

    public function testIsValidWithInvalidValue()
    {
        $this->assertFalse(Units::isValid('invalid'));
        $this->assertFalse(Units::isValid(''));
        $this->assertFalse(Units::isValid('METRIC'));  // case sensitive
    }

    public function testAllReturnsAllUnits()
    {
        $all = Units::all();

        $this->assertCount(2, $all);
        $this->assertContains(Units::METRIC, $all);
        $this->assertContains(Units::IMPERIAL, $all);
    }
}
