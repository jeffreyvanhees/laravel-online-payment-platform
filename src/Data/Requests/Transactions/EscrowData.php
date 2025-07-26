<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class EscrowData extends BaseData
{
    public function __construct(
        public bool $enabled,
        public ?string $release_date = null,
    ) {
    }
}