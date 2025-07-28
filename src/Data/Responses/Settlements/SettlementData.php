<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class SettlementData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public ?int $period_start = null,
        public ?int $period_end = null,
        public ?int $total_amount = null,
        public ?int $amount_paid = null,
        public ?int $amount_payable = null,
        public ?string $payout_type = null,
        public ?bool $livemode = null,
        public ?string $object = null,
    ) {}
}