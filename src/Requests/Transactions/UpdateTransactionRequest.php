<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\UpdateTransactionData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateTransactionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $transactionUid,
        protected UpdateTransactionData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/transactions/{$this->transactionUid}";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof UpdateTransactionData) {
            return $this->data->toArray();
        }

        return $this->data;
    }
}
