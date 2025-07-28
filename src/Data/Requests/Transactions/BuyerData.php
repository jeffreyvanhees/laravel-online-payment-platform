<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class BuyerData extends BaseData
{
    public function __construct(
        public string $emailaddress,
        public ?string $name_first = null,
        public ?string $name_last = null,
        public ?string $phone = null,
        public ?string $company_name = null,
        public ?string $coc_nr = null,
        public ?string $vat_nr = null,
    ) {}
}
