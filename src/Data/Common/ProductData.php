<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class ProductData extends BaseData
{
    public function __construct(
        public string $name,
        public int $quantity,
        public int $price, // in cents
        public ?string $description = null,
        public ?string $vat_rate = null,
        public ?array $metadata = null,
    ) {}
}
