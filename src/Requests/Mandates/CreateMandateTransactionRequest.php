<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateMandateTransactionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $mandateUid,
        protected array $data
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/mandates/{$this->mandateUid}/transactions";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}