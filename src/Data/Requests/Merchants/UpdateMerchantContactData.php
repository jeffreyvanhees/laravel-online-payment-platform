<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantContactData extends BaseData
{
    public function __construct(
        public ?string $type = null,
        public ?string $title = null,
        public ?string $name_initials = null,
        public ?string $name_first = null,
        public ?string $name_last = null,
        public ?array $names_given = null,
        public ?string $birthdate = null, // YYYY-MM-DD format
        public ?string $partner_name_last = null,
        public ?array $emailaddresses = null,
        public ?array $phonenumbers = null,
    ) {}
}
