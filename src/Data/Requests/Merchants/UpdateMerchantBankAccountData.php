<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class UpdateMerchantBankAccountData extends BaseData
{
    public function __construct(
        public ?string $reference = null, // up to 50 characters
        public ?string $return_url = null, // up to 255 characters - URL customer is redirected to once bank account is verified
        public ?string $notify_url = null, // up to 255 characters - notification URL to receive status updates
    ) {}
}