<?php

namespace MeteoFlow\Tests\Unit;

use MeteoFlow\Exception\ApiException;
use MeteoFlow\Exception\MeteoFlowException;
use MeteoFlow\Exception\SerializationException;
use MeteoFlow\Exception\TransportException;
use MeteoFlow\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testMeteoFlowExceptionIsBaseException()
    {
        $exception = new MeteoFlowException('Test error');

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertEquals('Test error', $exception->getMessage());
    }

    public function testTransportExceptionFromCurlError()
    {
        $exception = TransportException::fromCurlError(28, 'Connection timed out');

        $this->assertInstanceOf(MeteoFlowException::class, $exception);
        $this->assertEquals(28, $exception->getCurlErrorCode());
        $this->assertEquals('Connection timed out', $exception->getCurlErrorMessage());
        $this->assertStringContainsString('cURL error 28', $exception->getMessage());
        $this->assertStringContainsString('Connection timed out', $exception->getMessage());
    }

    public function testApiExceptionFromResponse()
    {
        $responseBody = json_encode(array(
            'error' => array(
                'code' => 'INVALID_API_KEY',
                'message' => 'The provided API key is invalid',
            ),
        ));

        $exception = ApiException::fromResponse(401, $responseBody);

        $this->assertInstanceOf(MeteoFlowException::class, $exception);
        $this->assertEquals(401, $exception->getStatusCode());
        $this->assertEquals($responseBody, $exception->getResponseBody());
        $this->assertEquals('INVALID_API_KEY', $exception->getErrorCode());
        $this->assertEquals('The provided API key is invalid', $exception->getErrorMessage());
        $this->assertStringContainsString('401', $exception->getMessage());
    }

    public function testApiExceptionWithPlainErrorResponse()
    {
        $responseBody = json_encode(array(
            'code' => 'NOT_FOUND',
            'message' => 'Location not found',
        ));

        $exception = ApiException::fromResponse(404, $responseBody);

        $this->assertEquals(404, $exception->getStatusCode());
        $this->assertEquals('NOT_FOUND', $exception->getErrorCode());
        $this->assertEquals('Location not found', $exception->getErrorMessage());
    }

    public function testApiExceptionWithNonJsonResponse()
    {
        $exception = ApiException::fromResponse(500, 'Internal Server Error');

        $this->assertEquals(500, $exception->getStatusCode());
        $this->assertNull($exception->getErrorCode());
        $this->assertNull($exception->getErrorMessage());
    }

    public function testSerializationExceptionFromJsonError()
    {
        $rawBody = 'not valid json {';

        $exception = SerializationException::fromJsonError($rawBody);

        $this->assertInstanceOf(MeteoFlowException::class, $exception);
        $this->assertEquals($rawBody, $exception->getRawBody());
        $this->assertNotEmpty($exception->getJsonError());
        $this->assertStringContainsString('Failed to decode JSON', $exception->getMessage());
    }

    public function testSerializationExceptionFromMissingField()
    {
        $rawBody = '{"place": {}}';

        $exception = SerializationException::fromMissingField($rawBody, 'current');

        $this->assertEquals($rawBody, $exception->getRawBody());
        $this->assertStringContainsString('current', $exception->getMessage());
    }

    public function testValidationExceptionForField()
    {
        $exception = ValidationException::forField('days', -5, 'must be >= 1');

        $this->assertInstanceOf(MeteoFlowException::class, $exception);
        $this->assertEquals('days', $exception->getField());
        $this->assertEquals(-5, $exception->getInvalidValue());
        $this->assertStringContainsString('days', $exception->getMessage());
        $this->assertStringContainsString('must be >= 1', $exception->getMessage());
    }

    public function testValidationExceptionRequiredField()
    {
        $exception = ValidationException::requiredField('apiKey');

        $this->assertEquals('apiKey', $exception->getField());
        $this->assertNull($exception->getInvalidValue());
        $this->assertStringContainsString('apiKey', $exception->getMessage());
        $this->assertStringContainsString('required', $exception->getMessage());
    }

    public function testExceptionHierarchy()
    {
        $this->assertInstanceOf(MeteoFlowException::class, new TransportException('test'));
        $this->assertInstanceOf(MeteoFlowException::class, new ApiException('test', 500));
        $this->assertInstanceOf(MeteoFlowException::class, new SerializationException('test'));
        $this->assertInstanceOf(MeteoFlowException::class, new ValidationException('test'));
    }
}
