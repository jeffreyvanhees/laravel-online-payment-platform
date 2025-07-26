<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Exceptions;

/**
 * Exception thrown for general API errors
 * 
 * This exception is raised for various API errors including
 * server errors, not found errors, and other HTTP status codes.
 */
class ApiException extends OppException
{
    /**
     * Create an exception from API response data
     * 
     * @param array $response The API response data
     * @param int $statusCode The HTTP status code
     * @return self API exception instance
     */
    public static function fromResponse(array $response, int $statusCode): self
    {
        $message = $response['message'] ?? 'API request failed';
        $code = $response['code'] ?? $statusCode;
        
        return new self($message, $code, null, $response);
    }

    /**
     * Create an exception for server errors (5xx)
     * 
     * @return self API exception instance
     */
    public static function serverError(): self
    {
        return new self('Internal server error', 500);
    }

    /**
     * Create an exception for resource not found (404)
     * 
     * @param string $resource The name of the resource that was not found
     * @return self API exception instance
     */
    public static function notFound(string $resource = 'Resource'): self
    {
        return new self("{$resource} not found", 404);
    }
}