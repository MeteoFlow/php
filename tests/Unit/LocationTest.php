<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Exception\ValidationException;
use MeteoFlow\Location\Location;
use MeteoFlow\Location\LocationCoords;
use MeteoFlow\Location\LocationSlug;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    public function testFromSlugCreatesLocationSlug()
    {
        $location = Location::fromSlug('united-kingdom-london');

        $this->assertInstanceOf(LocationSlug::class, $location);
        $this->assertEquals('united-kingdom-london', $location->getSlug());
    }

    public function testFromCoordsCreatesLocationCoords()
    {
        $location = Location::fromCoords(51.5074, -0.1278);

        $this->assertInstanceOf(LocationCoords::class, $location);
        $this->assertEquals(51.5074, $location->getLat());
        $this->assertEquals(-0.1278, $location->getLon());
    }

    public function testSlugToQueryParams()
    {
        $location = new LocationSlug('united-kingdom-london');
        $params = $location->toQueryParams();

        $this->assertEquals(array('slug' => 'united-kingdom-london'), $params);
    }

    public function testCoordsToQueryParams()
    {
        $location = new LocationCoords(51.5074, -0.1278);
        $params = $location->toQueryParams();

        $this->assertEquals(array('lat' => 51.5074, 'lon' => -0.1278), $params);
    }

    public function testSlugTrimsWhitespace()
    {
        $location = new LocationSlug('  united-kingdom-london  ');

        $this->assertEquals('united-kingdom-london', $location->getSlug());
    }

    public function testEmptySlugThrowsException()
    {
        $this->expectException(ValidationException::class);

        new LocationSlug('');
    }

    public function testWhitespaceOnlySlugThrowsException()
    {
        $this->expectException(ValidationException::class);

        new LocationSlug('   ');
    }

    public function testInvalidLatitudeTooLowThrowsException()
    {
        $this->expectException(ValidationException::class);

        new LocationCoords(-91, 0);
    }

    public function testInvalidLatitudeTooHighThrowsException()
    {
        $this->expectException(ValidationException::class);

        new LocationCoords(91, 0);
    }

    public function testInvalidLongitudeTooLowThrowsException()
    {
        $this->expectException(ValidationException::class);

        new LocationCoords(0, -181);
    }

    public function testInvalidLongitudeTooHighThrowsException()
    {
        $this->expectException(ValidationException::class);

        new LocationCoords(0, 181);
    }

    public function testBoundaryLatitudeValuesAreValid()
    {
        $north = new LocationCoords(90, 0);
        $south = new LocationCoords(-90, 0);

        $this->assertEquals(90, $north->getLat());
        $this->assertEquals(-90, $south->getLat());
    }

    public function testBoundaryLongitudeValuesAreValid()
    {
        $east = new LocationCoords(0, 180);
        $west = new LocationCoords(0, -180);

        $this->assertEquals(180, $east->getLon());
        $this->assertEquals(-180, $west->getLon());
    }

    public function testSlugToString()
    {
        $location = new LocationSlug('united-kingdom-london');

        $this->assertEquals('united-kingdom-london', (string) $location);
    }

    public function testCoordsToString()
    {
        $location = new LocationCoords(51.5074, -0.1278);

        $this->assertStringContainsString('51.5074', (string) $location);
        $this->assertStringContainsString('-0.1278', (string) $location);
    }

    public function testStrictOneofSlugOnlyContainsSlug()
    {
        $location = Location::fromSlug('united-kingdom-london');
        $params = $location->toQueryParams();

        $this->assertArrayHasKey('slug', $params);
        $this->assertArrayNotHasKey('lat', $params);
        $this->assertArrayNotHasKey('lon', $params);
    }

    public function testStrictOneofCoordsOnlyContainsLatLon()
    {
        $location = Location::fromCoords(51.5074, -0.1278);
        $params = $location->toQueryParams();

        $this->assertArrayHasKey('lat', $params);
        $this->assertArrayHasKey('lon', $params);
        $this->assertArrayNotHasKey('slug', $params);
    }
}
