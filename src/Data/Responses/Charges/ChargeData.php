<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Charges;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class ChargeData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $type,
        public string $status,
        public int $amount,
        public string $currency,
        public string $from_owner_uid,
        public string $to_owner_uid,
        public ?string $description = null,
        public ?array $metadata = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}
}
