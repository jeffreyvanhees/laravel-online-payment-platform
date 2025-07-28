<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Settlements;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements\SettlementData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements\SettlementsResponse;
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

    public function createDtoFromResponse(Response $response): SettlementsResponse
    {
        $responseData = $response->json();
        
        return SettlementsResponse::from([
            ...$responseData,
            'data' => array_map(
                fn ($item) => SettlementData::from($item),
                $responseData['data'] ?? []
            ),
        ]);
    }
}