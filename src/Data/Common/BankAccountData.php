<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class BankAccountData extends BaseData
{
    public function __construct(
        public string $return_url,
        public ?string $type = null,
        public ?string $iban = null,
        public ?string $bic = null,
        public ?string $holder_name = null,
    ) {}
}
