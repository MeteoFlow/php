<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\ClientConfig;
use MeteoFlow\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class ClientConfigTest extends TestCase
{
    public function testRequiresApiKey()
    {
        $this->expectException(ValidationException::class);

        new ClientConfig('');
    }

    public function testApiKeyTrimsWhitespace()
    {
        $config = new ClientConfig('  test-api-key  ');

        $this->assertEquals('test-api-key', $config->getApiKey());
    }

    public function testDefaultValues()
    {
        $config = new ClientConfig('test-key');

        $this->assertEquals('https://api.meteoflow.com', $config->getBaseUrl());
        $this->assertEquals('test-key', $config->getApiKey());
        $this->assertEquals(10, $config->getTimeoutSeconds());
        $this->assertEquals(5, $config->getConnectTimeoutSeconds());
        $this->assertEquals('meteoflow-php-sdk/1.0', $config->getUserAgent());
        $this->assertFalse($config->isDebug());
    }

    public function testWithBaseUrl()
    {
        $config = new ClientConfig('test-key');
        $newConfig = $config->withBaseUrl('https://custom.api.com/');

        // Original is unchanged (immutability)
        $this->assertEquals('https://api.meteoflow.com', $config->getBaseUrl());

        // New config has new value (trailing slash removed)
        $this->assertEquals('https://custom.api.com', $newConfig->getBaseUrl());
    }

    public function testWithTimeout()
    {
        $config = new ClientConfig('test-key');
        $newConfig = $config->withTimeout(30);

        $this->assertEquals(10, $config->getTimeoutSeconds());
        $this->assertEquals(30, $newConfig->getTimeoutSeconds());
    }

    public function testWithConnectTimeout()
    {
        $config = new ClientConfig('test-key');
        $newConfig = $config->withConnectTimeout(15);

        $this->assertEquals(5, $config->getConnectTimeoutSeconds());
        $this->assertEquals(15, $newConfig->getConnectTimeoutSeconds());
    }

    public function testWithUserAgent()
    {
        $config = new ClientConfig('test-key');
        $newConfig = $config->withUserAgent('custom-agent/2.0');

        $this->assertEquals('meteoflow-php-sdk/1.0', $config->getUserAgent());
        $this->assertEquals('custom-agent/2.0', $newConfig->getUserAgent());
    }

    public function testWithDebug()
    {
        $config = new ClientConfig('test-key');
        $newConfig = $config->withDebug(true);

        $this->assertFalse($config->isDebug());
        $this->assertTrue($newConfig->isDebug());
    }

    public function testChainedConfiguration()
    {
        $config = (new ClientConfig('my-api-key'))
            ->withBaseUrl('https://staging.api.com')
            ->withTimeout(20)
            ->withConnectTimeout(10)
            ->withUserAgent('my-app/1.0')
            ->withDebug(true);

        $this->assertEquals('my-api-key', $config->getApiKey());
        $this->assertEquals('https://staging.api.com', $config->getBaseUrl());
        $this->assertEquals(20, $config->getTimeoutSeconds());
        $this->assertEquals(10, $config->getConnectTimeoutSeconds());
        $this->assertEquals('my-app/1.0', $config->getUserAgent());
        $this->assertTrue($config->isDebug());
    }
}
