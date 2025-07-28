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
        // New fields for API compatibility
        public ?string $internal_reason = null,
        public ?int $created = null,
        public ?int $updated = null,
        public ?int $paid = null,
        public ?array $fees = null,
        public ?array $metadata = null,
        public ?bool $livemode = null,
        public ?string $object = null,
    ) {}
}
