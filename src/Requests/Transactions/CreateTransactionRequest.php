<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateTransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\TransactionData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateTransactionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateTransactionData|array $data)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/transactions';
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof CreateTransactionData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): TransactionData
    {
        return TransactionData::from($response->json());
    }
}