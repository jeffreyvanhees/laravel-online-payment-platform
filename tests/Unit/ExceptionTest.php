<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\OppException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\RateLimitException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException;

describe('Exception Classes', function () {
    test('it can create OppException with message and context', function () {
        $context = ['error_code' => 'TEST_ERROR', 'field' => 'test_field'];
        $exception = new OppException('Test error message', 0, null, $context);

        expect($exception->getMessage())->toBe('Test error message');
        expect($exception->getContext())->toBe($context);
        expect($exception->getCode())->toBe(0);
    });

    test('it can create OppException without context', function () {
        $exception = new OppException('Simple error message');

        expect($exception->getMessage())->toBe('Simple error message');
        expect($exception->getContext())->toBe([]);
        expect($exception->getCode())->toBe(0);
    });

    test('it can create AuthenticationException', function () {
        $context = ['error_code' => 'INVALID_API_KEY'];
        $exception = new AuthenticationException('Invalid API key', 401, null, $context);

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Invalid API key');
        expect($exception->getContext())->toBe($context);
        expect($exception->getCode())->toBe(401);
    });

    test('it can create ValidationException with invalidData factory', function () {
        $validationErrors = [
            'email' => ['The email field is required.'],
            'amount' => ['The amount must be greater than 0.'],
        ];
        $exception = ValidationException::invalidData($validationErrors);

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Validation failed');
        expect($exception->getCode())->toBe(422);
        expect($exception->getContext())->toBe(['errors' => $validationErrors]);
    });

    test('it can create ValidationException with missingRequiredField factory', function () {
        $exception = ValidationException::missingRequiredField('email');

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Missing required field: email');
        expect($exception->getCode())->toBe(422);
        expect($exception->getContext())->toBe([]);
    });

    test('it can create RateLimitException with exceeded factory', function () {
        $exception = RateLimitException::exceeded(60);

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Rate limit exceeded. Retry after 60 seconds');
        expect($exception->getCode())->toBe(429);
        expect($exception->getContext())->toBe(['retry_after' => 60]);
    });

    test('it can create RateLimitException without retry after', function () {
        $exception = RateLimitException::exceeded();

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Rate limit exceeded');
        expect($exception->getCode())->toBe(429);
        expect($exception->getContext())->toBe(['retry_after' => null]);
    });

    test('it can create ApiException', function () {
        $context = ['status_code' => 500, 'error_code' => 'INTERNAL_ERROR'];
        $exception = new ApiException('Internal server error', 500, null, $context);

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Internal server error');
        expect($exception->getCode())->toBe(500);
        expect($exception->getContext())->toBe($context);
    });

    test('it handles exception context properly', function () {
        $context = [
            'request_id' => 'req_123456789',
            'timestamp' => '2024-01-15T10:30:00Z',
            'user_id' => 'user_987654321',
        ];

        $exception = new OppException('Context test', 0, null, $context);

        expect($exception->getContext())->toBe($context);
        expect($exception->getContext()['request_id'])->toBe('req_123456789');
        expect($exception->getContext()['timestamp'])->toBe('2024-01-15T10:30:00Z');
        expect($exception->getContext()['user_id'])->toBe('user_987654321');
    });

    test('it can chain exceptions', function () {
        $previous = new \Exception('Previous exception');
        $exception = new OppException('Current exception', 0, $previous);

        expect($exception->getPrevious())->toBe($previous);
        expect($exception->getPrevious()->getMessage())->toBe('Previous exception');
    });

    test('it inherits from Exception correctly', function () {
        $exception = new OppException('Test message', 123);

        expect($exception)->toBeInstanceOf(\Exception::class);
        expect($exception->getMessage())->toBe('Test message');
        expect($exception->getCode())->toBe(123);
    });

    test('it can create ValidationException with empty errors', function () {
        $exception = ValidationException::invalidData([]);

        expect($exception->getMessage())->toBe('Validation failed');
        expect($exception->getCode())->toBe(422);
        expect($exception->getContext())->toBe(['errors' => []]);
    });

    test('it can create exception with complex context data', function () {
        $context = [
            'request' => [
                'method' => 'POST',
                'url' => '/api/merchants',
                'headers' => ['Content-Type' => 'application/json'],
            ],
            'response' => [
                'status' => 400,
                'body' => ['error' => 'Invalid data'],
            ],
        ];

        $exception = new OppException('Request failed', 400, null, $context);

        expect($exception->getContext())->toBe($context);
        expect($exception->getContext()['request']['method'])->toBe('POST');
        expect($exception->getContext()['response']['status'])->toBe(400);
    });

    test('it can create AuthenticationException with invalidApiKey factory', function () {
        $exception = AuthenticationException::invalidApiKey();

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Invalid API key provided');
        expect($exception->getCode())->toBe(401);
    });

    test('it can create AuthenticationException with missingApiKey factory', function () {
        $exception = AuthenticationException::missingApiKey();

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('API key is required but not provided');
        expect($exception->getCode())->toBe(401);
    });

    test('it can create ApiException with fromResponse factory', function () {
        $response = [
            'message' => 'Validation failed',
            'code' => 422,
            'errors' => ['field' => 'required'],
        ];
        $exception = ApiException::fromResponse($response, 422);

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Validation failed');
        expect($exception->getCode())->toBe(422);
        expect($exception->getContext())->toBe($response);
    });

    test('it can create ApiException with serverError factory', function () {
        $exception = ApiException::serverError();

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Internal server error');
        expect($exception->getCode())->toBe(500);
    });

    test('it can create ApiException with notFound factory', function () {
        $exception = ApiException::notFound('Merchant');

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Merchant not found');
        expect($exception->getCode())->toBe(404);
    });

    test('it can create ApiException with default notFound factory', function () {
        $exception = ApiException::notFound();

        expect($exception)->toBeInstanceOf(OppException::class);
        expect($exception->getMessage())->toBe('Resource not found');
        expect($exception->getCode())->toBe(404);
    });
});
