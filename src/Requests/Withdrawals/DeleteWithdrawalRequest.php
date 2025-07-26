<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Withdrawals;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteWithdrawalRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(protected string $withdrawalUid)
    {
    }

    public function resolveEndpoint(): string
    {
        return "/withdrawals/{$this->withdrawalUid}";
    }
}