<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetMerchantRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $merchantUid)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}";
    }

    public function createDtoFromResponse(Response $response): MerchantData
    {
        return MerchantData::from($response->json());
    }
}