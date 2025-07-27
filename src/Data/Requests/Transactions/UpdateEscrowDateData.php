<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateEscrowDateData extends BaseData
{
    public function __construct(
        public string $escrow_date,
    ) {}
}
