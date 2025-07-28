<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Mandates;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class MandateData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public string $merchant_uid,
        public string $mandate_url,
        public ?string $description = null,
        public ?string $reference = null,
        public ?array $metadata = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $signed_at = null,
        public ?string $cancelled_at = null,
        public ?string $iban = null,
        public ?string $bic = null,
        public ?string $holder_name = null,
        public ?string $mandate_method = null,
        public ?string $mandate_type = null,
        public ?string $mandate_repeat = null,
        public ?int $amount = null,
        public ?string $return_url = null,
        public ?string $notify_url = null,
        public ?bool $livemode = null,
        public ?string $object = null,
    ) {}
}
