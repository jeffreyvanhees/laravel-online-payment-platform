<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateMandateRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected array $data) {}

    public function resolveEndpoint(): string
    {
        return '/mandates';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
