<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Withdrawals;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class WithdrawalData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public string $merchant_uid,
        public int $amount,
        public string $currency,
        public string $bank_account_uid,
        public ?string $description = null,
        public ?string $reference = null,
        public ?array $metadata = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $processed_at = null,
    ) {
    }
}