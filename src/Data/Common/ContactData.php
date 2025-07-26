<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class ContactData extends BaseData
{
    public function __construct(
        public string $type,
        public string $gender,
        public string $title,
        public NameData $name,
        public ?string $birthdate = null,
        public ?array $emailaddresses = null,
        public ?array $phonenumbers = null,
    ) {
    }
}