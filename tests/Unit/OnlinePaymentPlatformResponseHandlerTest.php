<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\RateLimitException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformResponseHandler;
use Saloon\Http\Response;

describe('OnlinePaymentPlatformResponseHandler', function () {
    test('it returns response when successful', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(true);

        $result = OnlinePaymentPlatformResponseHandler::handle($response);

        expect($result)->toBe($response);
    });

    test('it throws AuthenticationException for 401 status', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(401);
        $response->shouldReceive('json')->once()->andReturn([]);

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(AuthenticationException::class, 'Invalid API key provided');
    });

    test('it throws ValidationException for 422 status with errors', function () {
        $errors = [
            'email' => ['The email field is required.'],
            'total_price' => ['The amount must be greater than 0.'],
        ];

        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(422);
        $response->shouldReceive('json')->once()->andReturn(['errors' => $errors]);

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(ValidationException::class, 'Validation failed');
    });

    test('it throws ValidationException for 422 status without errors', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(422);
        $response->shouldReceive('json')->once()->andReturn([]);

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(ValidationException::class, 'Validation failed');
    });

    test('it throws RateLimitException for 429 status with retry-after header', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(429);
        $response->shouldReceive('json')->once()->andReturn([]);
        $response->shouldReceive('header')->with('Retry-After')->twice()->andReturn('60');

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(RateLimitException::class, 'Rate limit exceeded. Retry after 60 seconds');
    });

    test('it throws RateLimitException for 429 status without retry-after header', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(429);
        $response->shouldReceive('json')->once()->andReturn([]);
        $response->shouldReceive('header')->with('Retry-After')->once()->andReturn(null);

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(RateLimitException::class, 'Rate limit exceeded');
    });

    test('it throws ApiException for 404 status', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(404);
        $response->shouldReceive('json')->once()->andReturn([]);

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(ApiException::class, 'Resource not found');
    });

    test('it throws ApiException for server error statuses', function () {
        $serverStatuses = [500, 502, 503, 504];

        foreach ($serverStatuses as $status) {
            $response = Mockery::mock(Response::class);
            $response->shouldReceive('successful')->once()->andReturn(false);
            $response->shouldReceive('status')->once()->andReturn($status);
            $response->shouldReceive('json')->once()->andReturn([]);

            expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
                ->toThrow(ApiException::class, 'Internal server error');
        }
    });

    test('it throws ApiException for other status codes', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(400);
        $response->shouldReceive('json')->once()->andReturn(['message' => 'Bad request']);

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(ApiException::class, 'Bad request');
    });

    test('it handles response with null json body', function () {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('successful')->once()->andReturn(false);
        $response->shouldReceive('status')->once()->andReturn(400);
        $response->shouldReceive('json')->once()->andReturn(null);

        expect(fn () => OnlinePaymentPlatformResponseHandler::handle($response))
            ->toThrow(ApiException::class, 'API request failed');
    });
});
