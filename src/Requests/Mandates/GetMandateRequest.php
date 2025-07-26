<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMandateRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $mandateUid)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/mandates/{$this->mandateUid}";
    }
}