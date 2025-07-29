<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantUBOData extends BaseData
{
    public function __construct(
        public ?string $name_prefix = null,
        public ?string $name_first = null,
        public ?string $name_last = null,
        public ?string $birthdate = null, // YYYY-MM-DD format
        public ?string $address_line_1 = null,
        public ?string $address_line_2 = null,
        public ?string $address_postal_code = null,
        public ?string $address_city = null,
        public ?string $address_country = null,
        public ?int $ownership_percentage = null,
        public ?bool $is_control_person = null,
    ) {}
}
