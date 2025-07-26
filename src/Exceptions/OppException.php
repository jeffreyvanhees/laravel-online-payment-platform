<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Exceptions;

use Exception;

/**
 * Base exception class for all OPP SDK exceptions
 * 
 * Provides additional context information beyond standard PHP exceptions.
 */
class OppException extends Exception
{
    protected array $context = [];

    /**
     * Create a new OPP exception
     * 
     * @param string $message The exception message
     * @param int $code The exception code
     * @param Exception|null $previous The previous exception for chaining
     * @param array $context Additional context information
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get additional context information about the exception
     * 
     * @return array Context data associated with this exception
     */
    public function getContext(): array
    {
        return $this->context;
    }
}