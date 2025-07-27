<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Withdrawals;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateWithdrawalRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected array $data,
        protected ?string $profileUid = null
    ) {}

    public function resolveEndpoint(): string
    {
        if ($this->profileUid) {
            return "/merchants/{$this->merchantUid}/profiles/{$this->profileUid}/withdrawals";
        }

        return "/merchants/{$this->merchantUid}/withdrawals";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
