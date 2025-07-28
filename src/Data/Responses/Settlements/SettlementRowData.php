<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class SettlementRowData extends BaseData
{
    public function __construct(
        public string $type, // 'transaction', 'refund', 'chargeback', 'mandate'
        public string $reference, // UID of related object
        public int $total_partner_fee,
        public int $amount, // gross amount
        public int $amount_payable, // net amount after fees
        public ?array $metadata = null,
    ) {}
}