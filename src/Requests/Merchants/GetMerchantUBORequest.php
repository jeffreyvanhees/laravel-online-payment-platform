<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\UBOData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetMerchantUBORequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $merchantUid,
        protected string $uboUid
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/ubos/{$this->uboUid}";
    }

    public function createDtoFromResponse(Response $response): UBOData
    {
        return UBOData::from($response->json());
    }
}