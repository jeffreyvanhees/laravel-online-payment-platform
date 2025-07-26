<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetTransactionRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $transactionUid)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/transactions/{$this->transactionUid}";
    }
}