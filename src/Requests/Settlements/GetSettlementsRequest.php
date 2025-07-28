<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Settlements;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\SettlementData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\PaginatedListResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetSettlementsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected array $params = []) {}

    public function resolveEndpoint(): string
    {
        return '/settlements';
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }

    public function createDtoFromResponse(Response $response): PaginatedListResponse
    {
        $responseData = $response->json();
        
        return new PaginatedListResponse(
            data: collect($responseData['data'] ?? [])->map(fn($settlement) => SettlementData::from($settlement))->toArray(),
            pagination: $responseData
        );
    }
}