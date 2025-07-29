<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Mandates;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateMandateTransactionData extends BaseData
{
    public function __construct(
        public string $merchant_uid,
        public string $reference,
        public string $token,
        public int $total_price, // in cents
        public string $notify_url,
        public ?int $escrow_period = null,
        public ?array $metadata = null,
    ) {}
}
