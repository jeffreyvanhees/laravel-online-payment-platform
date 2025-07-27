<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Disputes;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class DisputeData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public string $transaction_uid,
        public string $reason,
        public ?string $message = null,
        public ?array $evidence_file_uids = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $resolved_at = null,
    ) {}
}
