<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Mandates;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateMandateData extends BaseData
{
    public function __construct(
        public string $merchant_uid,
        public string $return_url,
        public string $notify_url,
        public ?string $description = null,
        public ?string $reference = null,
        public ?array $metadata = null,
    ) {
    }
}