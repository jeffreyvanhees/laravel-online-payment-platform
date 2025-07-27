<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Withdrawals;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateWithdrawalData extends BaseData
{
    public function __construct(
        public string $merchant_uid,
        public int $amount, // in cents
        public string $bank_account_uid,
        public ?string $description = null,
        public ?string $reference = null,
        public ?array $metadata = null,
    ) {}
}
