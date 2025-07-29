<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateTransactionData extends BaseData
{
    public function __construct(
        public ?string $escrow_date = null, // YYYY-MM-DD HH:MM:SS format
    ) {}
}
