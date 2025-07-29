<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantBankAccountStatusData extends BaseData
{
    public function __construct(
        public string $status, // One of: pending, approved, disapproved
    ) {}
}