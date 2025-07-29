<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\AddressData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantAddressData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantAddressRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $addressUid,
        protected UpdateMerchantAddressData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/addresses/{$this->addressUid}";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof UpdateMerchantAddressData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): AddressData
    {
        return AddressData::from($response->json());
    }
}
