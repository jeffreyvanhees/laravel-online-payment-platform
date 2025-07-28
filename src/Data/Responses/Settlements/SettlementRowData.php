<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class SettlementRowData extends BaseData
{
    public function __construct(
        public string $type,
        public string $reference,
        public int $amount,
        public ?int $amount_payable = null,
        public ?array $metadata = null,
    ) {}
}