<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\PaginatedListResponse;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundData;
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

    public function createDtoFromResponse(Response $response): PaginatedListResponse
    {
        $responseData = $response->json();
        
        $refunds = collect($responseData['data'] ?? [])->map(fn($refund) => RefundData::from($refund));
        
        return new PaginatedListResponse(
            object: $responseData['object'] ?? 'list',
            url: '/transactions/' . $this->transactionUid . '/refunds',
            has_more: $responseData['has_more'] ?? false,
            total_item_count: $responseData['total_item_count'] ?? 0,
            items_per_page: $responseData['items_per_page'] ?? 10,
            current_page: $responseData['current_page'] ?? 1,
            last_page: $responseData['last_page'] ?? 1,
            data: \Spatie\LaravelData\DataCollection::from($refunds, RefundData::class)
        );
    }
}