<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Disputes;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetDisputesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected array $params = [])
    {
    }

    public function resolveEndpoint(): string
    {
        return '/disputes';
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }
}