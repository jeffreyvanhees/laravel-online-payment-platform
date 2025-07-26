<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateRefundData extends BaseData
{
    public function __construct(
        public int $amount, // in cents
        public ?string $payout_description = null,
        public ?string $message = null,
    ) {
    }
}