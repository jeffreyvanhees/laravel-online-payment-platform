<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantProfileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\ProfileData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantProfileRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $profileUid,
        protected UpdateMerchantProfileData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/profiles/{$this->profileUid}";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof UpdateMerchantProfileData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): ProfileData
    {
        return ProfileData::from($response->json());
    }
}
