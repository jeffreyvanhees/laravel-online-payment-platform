<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class MigrateMerchantData extends BaseData
{
    public function __construct(
        public string $coc_nr,
        public string $country,
    ) {}
}
