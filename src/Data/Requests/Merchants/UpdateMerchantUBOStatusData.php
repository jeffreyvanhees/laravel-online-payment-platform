<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantUBOStatusData extends BaseData
{
    public function __construct(
        public string $status, // One of: pending, verified, unverified
    ) {}
}
