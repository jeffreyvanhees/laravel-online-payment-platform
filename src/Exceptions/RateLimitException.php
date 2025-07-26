<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Exceptions;

/**
 * Exception thrown when API rate limits are exceeded
 * 
 * This exception is raised when too many requests have been made
 * within a given time period.
 */
class RateLimitException extends OppException
{
    /**
     * Create an exception for rate limit exceeded
     * 
     * @param int|null $retryAfter Number of seconds to wait before retrying
     * @return self Rate limit exception instance
     */
    public static function exceeded(int $retryAfter = null): self
    {
        $message = 'Rate limit exceeded';
        if ($retryAfter) {
            $message .= ". Retry after {$retryAfter} seconds";
        }
        
        return new self($message, 429, null, ['retry_after' => $retryAfter]);
    }
}