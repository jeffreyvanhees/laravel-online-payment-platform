<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class NameData extends BaseData
{
    public function __construct(
        public string $first,
        public string $last,
        public ?string $initials = null,
        public ?string $prefix = null,
    ) {}
}
