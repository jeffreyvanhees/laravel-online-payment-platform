<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Exceptions;

/**
 * Exception thrown when request validation fails
 * 
 * This exception is raised when required fields are missing or
 * when provided data doesn't meet validation requirements.
 */
class ValidationException extends OppException
{
    /**
     * Create an exception for invalid data with validation errors
     * 
     * @param array $errors Array of validation error messages
     * @return self Validation exception instance
     */
    public static function invalidData(array $errors): self
    {
        return new self('Validation failed', 422, null, ['errors' => $errors]);
    }

    /**
     * Create an exception for a missing required field
     * 
     * @param string $field The name of the missing field
     * @return self Validation exception instance
     */
    public static function missingRequiredField(string $field): self
    {
        return new self("Missing required field: {$field}", 422);
    }
}