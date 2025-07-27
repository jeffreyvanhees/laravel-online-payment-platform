<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\UBOData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\PaginatedListResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetMerchantUBOsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $merchantUid,
        protected array $params = []
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/ubos";
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }

    public function createDtoFromResponse(Response $response): PaginatedListResponse
    {
        return PaginatedListResponse::from([
            ...$response->json(),
            'data' => array_map(
                fn ($item) => UBOData::from($item),
                $response->json('data', [])
            ),
        ]);
    }
}
