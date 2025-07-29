<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantProfileData extends BaseData
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $url = null,
        public ?array $settings = null,
        public ?string $notify_url = null,
        public ?string $return_url = null,
        public ?bool $is_default = null,
    ) {}
}
