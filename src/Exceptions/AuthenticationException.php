<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Exceptions;

/**
 * Exception thrown when authentication fails
 *
 * This exception is raised when API key validation fails or authentication
 * is required but not provided.
 */
class AuthenticationException extends OppException
{
    /**
     * Create an exception for invalid API key
     *
     * @return self Authentication exception instance
     */
    public static function invalidApiKey(): self
    {
        return new self('Invalid API key provided', 401);
    }

    /**
     * Create an exception for missing API key
     *
     * @return self Authentication exception instance
     */
    public static function missingApiKey(): self
    {
        return new self('API key is required but not provided', 401);
    }
}
