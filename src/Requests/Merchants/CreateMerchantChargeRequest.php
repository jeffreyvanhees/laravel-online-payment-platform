<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Charges\CreateChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Charges\ChargeData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateMerchantChargeRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected CreateChargeData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/charges";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof CreateChargeData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): ChargeData
    {
        return ChargeData::from($response->json());
    }
}
