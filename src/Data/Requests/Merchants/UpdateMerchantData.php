<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantData extends BaseData
{
    public function __construct(
        public ?string $status = null,
        public ?string $emailaddress = null,
        public ?bool $is_pep = null,
        public ?string $notify_url = null,
        public ?string $return_url = null,
    ) {}
}
