<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Disputes;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateDisputeData extends BaseData
{
    public function __construct(
        public string $transaction_uid,
        public string $reason,
        public ?string $message = null,
        public ?array $evidence_file_uids = null,
    ) {
    }
}