<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantAddressData extends BaseData
{
    public function __construct(
        public ?string $type = null,
        public ?string $line_1 = null,
        public ?string $line_2 = null,
        public ?string $postal_code = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $state = null,
    ) {}
}
