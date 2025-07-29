<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantContactData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ContactData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantContactRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $contactUid,
        protected UpdateMerchantContactData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/contacts/{$this->contactUid}";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof UpdateMerchantContactData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): ContactData
    {
        return ContactData::from($response->json());
    }
}
