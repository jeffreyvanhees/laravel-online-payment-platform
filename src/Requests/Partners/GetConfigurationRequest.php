<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Partners;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetConfigurationRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/configuration';
    }
}