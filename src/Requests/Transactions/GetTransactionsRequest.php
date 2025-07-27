<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetTransactionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected array $params = []) {}

    public function resolveEndpoint(): string
    {
        return '/transactions';
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }
}
