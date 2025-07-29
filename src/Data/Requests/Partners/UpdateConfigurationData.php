<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Partners;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateConfigurationData extends BaseData
{
    public function __construct(
        public ?string $notify_url = null,
        public ?array $settings = null,
        public ?array $metadata = null,
    ) {}
}
