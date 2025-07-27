<?php

declare(strict_types=1);

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Assert that a request has the expected HTTP method and endpoint
 */
function assertRequest(Request $request, Method $method, string $endpoint): void
{
    expect($request->getMethod())->toBe($method);
    expect($request->resolveEndpoint())->toBe($endpoint);
}

/**
 * Assert that a request's body contains the expected data
 */
function assertRequestBody(Request $request, array $expectedData): void
{
    $body = $request->body()->all();

    foreach ($expectedData as $key => $value) {
        expect($body)->toHaveKey($key);
        expect($body[$key])->toBe($value);
    }

    // Also check that no extra fields are present
    expect(array_keys($body))->toBe(array_keys($expectedData));
}

/**
 * Assert that a request's query parameters contain the expected data
 */
function assertRequestQuery(Request $request, array $expectedParams): void
{
    $query = $request->query()->all();

    foreach ($expectedParams as $key => $value) {
        expect($query)->toHaveKey($key);
        expect($query[$key])->toBe($value);
    }
}

/**
 * Assert that a request has specific headers
 */
function assertRequestHeaders(Request $request, array $expectedHeaders): void
{
    $headers = $request->headers()->all();

    foreach ($expectedHeaders as $key => $value) {
        expect($headers)->toHaveKey($key);
        expect($headers[$key])->toBe($value);
    }
}

/**
 * Use reflection to get a protected property value
 */
function getProtectedProperty(object $object, string $property): mixed
{
    $reflection = new ReflectionClass($object);
    $prop = $reflection->getProperty($property);
    $prop->setAccessible(true);

    return $prop->getValue($object);
}

/**
 * Assert multiple protected properties at once
 */
function assertProtectedProperties(object $object, array $expectedProperties): void
{
    foreach ($expectedProperties as $property => $expectedValue) {
        $actualValue = getProtectedProperty($object, $property);
        expect($actualValue)->toBe($expectedValue);
    }
}

/**
 * Create a mock response with JSON data
 */
function mockJsonResponse(array $data): \Mockery\MockInterface
{
    $response = Mockery::mock(\Saloon\Http\Response::class);
    $response->shouldReceive('json')->andReturn($data);
    $response->shouldReceive('successful')->andReturn(true);
    $response->shouldReceive('status')->andReturn(200);

    return $response;
}

/**
 * Create a mock connector
 */
function mockConnector(): \Mockery\MockInterface
{
    return Mockery::mock(\Saloon\Http\Connector::class);
}
