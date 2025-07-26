<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantsResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetMerchantsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected array $params = [])
    {
    }

    public function resolveEndpoint(): string
    {
        return '/merchants';
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }

    public function createDtoFromResponse(Response $response): MerchantsResponse
    {
        return MerchantsResponse::from($response->json());
    }
}