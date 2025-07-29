<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantContactStatusData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ContactData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantContactStatusRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $contactUid,
        protected UpdateMerchantContactStatusData|array|string $statusData
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/contacts/{$this->contactUid}/update-status";
    }

    protected function defaultBody(): array
    {
        if ($this->statusData instanceof UpdateMerchantContactStatusData) {
            return $this->statusData->toArray();
        }
        
        if (is_string($this->statusData)) {
            return ['status' => $this->statusData];
        }
        
        return $this->statusData;
    }

    public function createDtoFromResponse(Response $response): ContactData
    {
        return ContactData::from($response->json());
    }
}
