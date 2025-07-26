<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class SettlementData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public int $amount,
        public string $currency,
        public string $date,
        public ?string $bank_account_uid = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?array $transactions = null,
    ) {
    }
}