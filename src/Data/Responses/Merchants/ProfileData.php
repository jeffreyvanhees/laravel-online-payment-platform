<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class ProfileData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $name,
        public ?string $description = null,
        public ?array $settings = null,
        public ?string $webhook_url = null,
        public ?string $return_url = null,
        public ?bool $is_default = null,
        public ?string $status = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
    }
}