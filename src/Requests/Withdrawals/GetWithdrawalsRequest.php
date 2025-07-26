<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Withdrawals;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetWithdrawalsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected array $params = [],
        protected ?string $merchantUid = null
    ) {
    }

    public function resolveEndpoint(): string
    {
        if ($this->merchantUid) {
            return "/merchants/{$this->merchantUid}/withdrawals";
        }
        
        return '/withdrawals';
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }
}