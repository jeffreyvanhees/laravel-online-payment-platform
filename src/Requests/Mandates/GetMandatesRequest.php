<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMandatesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected array $params = []) {}

    public function resolveEndpoint(): string
    {
        return '/mandates';
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }
}
