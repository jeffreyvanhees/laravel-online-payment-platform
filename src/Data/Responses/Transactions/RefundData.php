<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class RefundData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public int $amount,
        public string $currency,
        public ?string $payout_description = null,
        public ?string $message = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $processed_at = null,
    ) {}
}
