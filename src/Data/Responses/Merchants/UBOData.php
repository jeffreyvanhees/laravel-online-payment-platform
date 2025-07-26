<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UBOData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $name_first,
        public string $name_last,
        public string $date_of_birth,
        public string $country_of_residence,
        public ?string $name_prefix = null,
        public ?bool $is_decision_maker = null,
        public ?bool $is_pep = null,
        public ?float $percentage_of_shares = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
    }
}