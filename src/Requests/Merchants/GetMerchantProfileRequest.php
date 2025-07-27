<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\ProfileData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetMerchantProfileRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $merchantUid,
        protected string $profileUid
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/profiles/{$this->profileUid}";
    }

    public function createDtoFromResponse(Response $response): ProfileData
    {
        return ProfileData::from($response->json());
    }
}
