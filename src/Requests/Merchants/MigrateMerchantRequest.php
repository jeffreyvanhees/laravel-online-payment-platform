<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\MigrateMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class MigrateMerchantRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected MigrateMerchantData|array $migrationData
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/migrate";
    }

    protected function defaultBody(): array
    {
        if ($this->migrationData instanceof MigrateMerchantData) {
            return $this->migrationData->toArray();
        }

        return $this->migrationData;
    }

    public function createDtoFromResponse(Response $response): MerchantData
    {
        return MerchantData::from($response->json());
    }
}
