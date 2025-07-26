<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
        ];
    }

    protected function getApiKey(): string
    {
        return $_ENV['OPP_API_KEY'] ?? 'test_api_key';
    }

    protected function getSandboxApiKey(): string
    {
        return $_ENV['OPP_SANDBOX_API_KEY'] ?? '83aa836c1137e58726a7e11ead108b5a';
    }
}