<?php

declare(strict_types=1);

use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;

require_once __DIR__.'/../../Helpers/TestHelpers.php';

/**
 * Base test case for resource tests with common assertions
 */
abstract class ResourceTestCase extends \PHPUnit\Framework\TestCase
{
    protected Connector $connector;

    protected Response $mockResponse;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connector = mockConnector();
        $this->mockResponse = mockJsonResponse([]);
    }

    /**
     * Assert that a resource method sends the expected request
     */
    protected function assertResourceSendsRequest(
        callable $resourceMethod,
        string $expectedRequestClass,
        array $expectedProperties = []
    ): void {
        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($expectedRequestClass, $expectedProperties) {
                if (! $request instanceof $expectedRequestClass) {
                    return false;
                }

                if (! empty($expectedProperties)) {
                    assertProtectedProperties($request, $expectedProperties);
                }

                return true;
            }))
            ->andReturn($this->mockResponse);

        $result = $resourceMethod();

        expect($result)->toBe($this->mockResponse);
    }

    /**
     * Create a data provider for testing various parameter combinations
     */
    protected function resourceMethodScenarios(): array
    {
        return [
            'minimal params' => [[]],
            'with filters' => [['status' => 'active', 'limit' => 20]],
            'with sorting' => [['sort' => 'created_at', 'order' => 'desc']],
            'complex params' => [[
                'status' => 'active',
                'limit' => 50,
                'offset' => 100,
                'created_after' => '2024-01-01',
                'sort' => 'updated_at',
            ]],
        ];
    }
}
