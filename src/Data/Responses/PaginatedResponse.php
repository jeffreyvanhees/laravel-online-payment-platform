<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\MetaData;
use Spatie\LaravelData\DataCollection;

abstract class PaginatedResponse extends BaseData
{
    public function __construct(
        public DataCollection $data,
        public MetaData $meta,
    ) {}
}
