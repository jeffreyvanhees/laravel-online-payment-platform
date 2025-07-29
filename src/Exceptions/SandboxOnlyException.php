<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Exceptions;

class SandboxOnlyException extends OppException
{
    public function __construct(string $method = 'This method')
    {
        parent::__construct(
            "{$method} is only available in sandbox environment. It cannot be used in production.",
            400
        );
    }
}