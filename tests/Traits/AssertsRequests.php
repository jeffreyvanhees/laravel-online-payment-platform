<?php

declare(strict_types=1);

namespace Tests\Traits;

use Saloon\Enums\Method;
use Saloon\Http\Request;

trait AssertsRequests
{
    /**
     * Assert a request matches expected properties using a fluent interface
     */
    protected function assertRequest(Request $request): RequestAssertion
    {
        return new RequestAssertion($request);
    }
}

class RequestAssertion
{
    public function __construct(private Request $request) {}

    public function hasMethod(Method $method): self
    {
        expect($this->request->getMethod())->toBe($method);

        return $this;
    }

    public function hasEndpoint(string $endpoint): self
    {
        expect($this->request->resolveEndpoint())->toBe($endpoint);

        return $this;
    }

    public function hasBody(array $expectedBody): self
    {
        $actualBody = $this->request->body()->all();

        foreach ($expectedBody as $key => $value) {
            expect($actualBody)->toHaveKey($key);
            expect($actualBody[$key])->toBe($value);
        }

        return $this;
    }

    public function hasExactBody(array $expectedBody): self
    {
        expect($this->request->body()->all())->toBe($expectedBody);

        return $this;
    }

    public function hasQuery(array $expectedQuery): self
    {
        $actualQuery = $this->request->query()->all();

        foreach ($expectedQuery as $key => $value) {
            expect($actualQuery)->toHaveKey($key);
            expect($actualQuery[$key])->toBe($value);
        }

        return $this;
    }

    public function hasHeaders(array $expectedHeaders): self
    {
        $actualHeaders = $this->request->headers()->all();

        foreach ($expectedHeaders as $key => $value) {
            expect($actualHeaders)->toHaveKey($key);
            expect($actualHeaders[$key])->toBe($value);
        }

        return $this;
    }

    public function hasProtectedProperty(string $property, mixed $expectedValue): self
    {
        $reflection = new \ReflectionClass($this->request);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);

        expect($prop->getValue($this->request))->toBe($expectedValue);

        return $this;
    }

    public function hasProtectedProperties(array $properties): self
    {
        foreach ($properties as $property => $expectedValue) {
            $this->hasProtectedProperty($property, $expectedValue);
        }

        return $this;
    }
}
