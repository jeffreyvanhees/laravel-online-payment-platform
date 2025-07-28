<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Mandates\CreateMandateData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Mandates\MandateData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateMandateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateMandateData|array $data) {}

    public function resolveEndpoint(): string
    {
        return '/mandates';
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof CreateMandateData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): MandateData
    {
        return MandateData::from($response->json());
    }
}
