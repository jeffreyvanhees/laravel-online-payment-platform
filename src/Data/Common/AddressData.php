<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class AddressData extends BaseData
{
    public function __construct(
        public string $type,
        public string $address_line_1,
        public string $zipcode,
        public string $city,
        public string $country,
        public ?string $address_line_2 = null,
        public ?string $state = null,
    ) {
    }
}