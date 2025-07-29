<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantUBOStatusData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\UBOData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantUBOStatusRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $uboUid,
        protected UpdateMerchantUBOStatusData|array|string $statusData
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/ubos/{$this->uboUid}/update-status";
    }

    protected function defaultBody(): array
    {
        if ($this->statusData instanceof UpdateMerchantUBOStatusData) {
            return $this->statusData->toArray();
        }
        
        if (is_string($this->statusData)) {
            return ['status' => $this->statusData];
        }
        
        return $this->statusData;
    }

    public function createDtoFromResponse(Response $response): UBOData
    {
        return UBOData::from($response->json());
    }
}
