<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateMerchantProfileData extends BaseData
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?array $settings = null,
        public ?string $webhook_url = null,
        public ?string $return_url = null,
        public ?bool $is_default = null,
    ) {
    }
}