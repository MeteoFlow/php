<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Options\Unit;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testMetricConstant()
    {
        $this->assertEquals('metric', Unit::METRIC);
    }

    public function testImperialConstant()
    {
        $this->assertEquals('imperial', Unit::IMPERIAL);
    }

    public function testIsValidWithMetric()
    {
        $this->assertTrue(Unit::isValid(Unit::METRIC));
        $this->assertTrue(Unit::isValid('metric'));
    }

    public function testIsValidWithImperial()
    {
        $this->assertTrue(Unit::isValid(Unit::IMPERIAL));
        $this->assertTrue(Unit::isValid('imperial'));
    }

    public function testIsValidWithInvalidValue()
    {
        $this->assertFalse(Unit::isValid('invalid'));
        $this->assertFalse(Unit::isValid(''));
        $this->assertFalse(Unit::isValid('METRIC'));  // case sensitive
    }

    public function testAllReturnsAllUnits()
    {
        $all = Unit::all();

        $this->assertCount(2, $all);
        $this->assertContains(Unit::METRIC, $all);
        $this->assertContains(Unit::IMPERIAL, $all);
    }
}
