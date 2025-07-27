<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Charges;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateChargeData extends BaseData
{
    public function __construct(
        public string $type,
        public int $amount, // in cents
        public string $from_owner_uid,
        public string $to_owner_uid,
        public ?string $description = null,
        public ?array $metadata = null,
    ) {}
}
