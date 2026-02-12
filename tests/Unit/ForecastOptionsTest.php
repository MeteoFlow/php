<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Exception\ValidationException;
use MeteoFlow\Options\ForecastOptions;
use MeteoFlow\Options\Unit;
use PHPUnit\Framework\TestCase;

class ForecastOptionsTest extends TestCase
{
    public function testDefaultOptionsAreNull()
    {
        $options = new ForecastOptions();

        $this->assertNull($options->getDays());
        $this->assertNull($options->getUnits());
        $this->assertNull($options->getLang());
    }

    public function testSetDays()
    {
        $options = ForecastOptions::create()->setDays(7);

        $this->assertEquals(7, $options->getDays());
    }

    public function testSetDaysValidatesMinimum()
    {
        $this->expectException(ValidationException::class);

        ForecastOptions::create()->setDays(0);
    }

    public function testSetDaysRejectsNegative()
    {
        $this->expectException(ValidationException::class);

        ForecastOptions::create()->setDays(-1);
    }

    public function testSetUnitsMetric()
    {
        $options = ForecastOptions::create()->setUnits(Unit::METRIC);

        $this->assertEquals(Unit::METRIC, $options->getUnits());
    }

    public function testSetUnitsImperial()
    {
        $options = ForecastOptions::create()->setUnits(Unit::IMPERIAL);

        $this->assertEquals(Unit::IMPERIAL, $options->getUnits());
    }

    public function testSetUnitsRejectsInvalid()
    {
        $this->expectException(ValidationException::class);

        ForecastOptions::create()->setUnits('invalid');
    }

    public function testSetLang()
    {
        $options = ForecastOptions::create()->setLang('ru');

        $this->assertEquals('ru', $options->getLang());
    }

    public function testSetLangTrimsWhitespace()
    {
        $options = ForecastOptions::create()->setLang('  de  ');

        $this->assertEquals('de', $options->getLang());
    }

    public function testSetLangRejectsEmpty()
    {
        $this->expectException(ValidationException::class);

        ForecastOptions::create()->setLang('');
    }

    public function testToQueryParamsWithAllOptions()
    {
        $options = ForecastOptions::create()
            ->setDays(5)
            ->setUnits(Unit::IMPERIAL)
            ->setLang('fr');

        $params = $options->toQueryParams();

        $this->assertEquals(array(
            'days' => 5,
            'units' => 'imperial',
            'lang' => 'fr',
        ), $params);
    }

    public function testToQueryParamsOmitsNullValues()
    {
        $options = ForecastOptions::create()->setDays(3);

        $params = $options->toQueryParams();

        $this->assertEquals(array('days' => 3), $params);
        $this->assertArrayNotHasKey('units', $params);
        $this->assertArrayNotHasKey('lang', $params);
    }

    public function testToQueryParamsEmptyWhenNoOptionsSet()
    {
        $options = new ForecastOptions();

        $params = $options->toQueryParams();

        $this->assertEquals(array(), $params);
    }

    public function testFluentInterface()
    {
        $options = ForecastOptions::create()
            ->setDays(7)
            ->setUnits(Unit::METRIC)
            ->setLang('en');

        $this->assertInstanceOf(ForecastOptions::class, $options);
        $this->assertEquals(7, $options->getDays());
        $this->assertEquals(Unit::METRIC, $options->getUnits());
        $this->assertEquals('en', $options->getLang());
    }

    public function testWithDaysFactory()
    {
        $options = ForecastOptions::withDays(14);

        $this->assertEquals(14, $options->getDays());
        $this->assertNull($options->getUnits());
        $this->assertNull($options->getLang());
    }
}
