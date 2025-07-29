<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundsResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetTransactionRefundsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $transactionUid,
        protected array $params = []
    ) {}

    public function resolveEndpoint(): string
    {
        return "/transactions/{$this->transactionUid}/refunds";
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }

    public function createDtoFromResponse(Response $response): RefundsResponse
    {
        $responseData = $response->json();

        $refunds = collect($responseData['data'] ?? [])->map(fn ($refund) => RefundData::from($refund));

        return RefundsResponse::from([
            ...$responseData,
            'data' => $refunds->toArray(),
        ]);
    }
}
