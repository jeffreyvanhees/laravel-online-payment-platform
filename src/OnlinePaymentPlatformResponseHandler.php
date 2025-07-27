<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform;

use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\RateLimitException;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException;
use Saloon\Http\Response;

class OnlinePaymentPlatformResponseHandler
{
    public static function handle(Response $response): Response
    {
        if ($response->successful()) {
            return $response;
        }

        $statusCode = $response->status();
        $body = $response->json() ?? [];

        match ($statusCode) {
            401 => throw AuthenticationException::invalidApiKey(),
            422 => throw ValidationException::invalidData($body['errors'] ?? []),
            429 => throw RateLimitException::exceeded($response->header('Retry-After') ? (int) $response->header('Retry-After') : null),
            404 => throw ApiException::notFound(),
            500, 502, 503, 504 => throw ApiException::serverError(),
            default => throw ApiException::fromResponse($body, $statusCode),
        };
    }
}
