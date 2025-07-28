<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Settlements;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\PaginatedListResponse;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements\SettlementRowData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSettlementSpecificationRowsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $settlementUid,
        protected string $specificationUid,
        protected array $params = []
    ) {}

    public function resolveEndpoint(): string
    {
        return "/settlements/{$this->settlementUid}/specifications/{$this->specificationUid}/rows";
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }

    public function createDtoFromResponse(Response $response): PaginatedListResponse
    {
        $responseData = $response->json();
        
        return new PaginatedListResponse(
            data: collect($responseData['data'] ?? [])->map(fn($row) => SettlementRowData::from($row))->toArray(),
            pagination: $responseData
        );
    }
}