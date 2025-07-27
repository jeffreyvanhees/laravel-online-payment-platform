<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class MetaData extends BaseData
{
    public function __construct(
        public PaginationData $pagination,
    ) {}
}
