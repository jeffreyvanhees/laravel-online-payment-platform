<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Partners;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Partners\UpdateConfigurationData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateConfigurationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected UpdateConfigurationData|array $data) {}

    public function resolveEndpoint(): string
    {
        return '/configuration';
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof UpdateConfigurationData) {
            return $this->data->toArray();
        }

        return $this->data;
    }
}
