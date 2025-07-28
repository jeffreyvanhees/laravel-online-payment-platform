<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateRefundData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateRefundRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $transactionUid,
        protected CreateRefundData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/transactions/{$this->transactionUid}/refunds";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof CreateRefundData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): RefundData
    {
        return RefundData::from($response->json());
    }
}