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
        // New fields for API compatibility
        public ?string $mandate_method = null,
        public ?string $mandate_type = null,
        public ?string $mandate_repeat = null,
        public ?int $amount = null,
        public ?string $redirect_url = null,
        public ?string $return_url = null,
        public ?string $notify_url = null,
        public ?int $created = null,
        public ?int $updated = null,
        public ?int $completed = null,
        public ?int $expired = null,
        public ?int $revoked = null,
        public ?int $start = null,
        public ?int $end = null,
        public ?array $customer = null,
        public ?array $order = null,
        public ?array $statuses = null,
        public ?bool $livemode = null,
        public ?string $object = null,
        public ?bool $has_checkout = null,
        public ?bool $skip_confirmation = null,
        public ?string $mandate_flow = null,
        public ?string $payment_method = null,
        public ?int $repeats = null,
        public ?string $interval = null,
    ) {}
}
