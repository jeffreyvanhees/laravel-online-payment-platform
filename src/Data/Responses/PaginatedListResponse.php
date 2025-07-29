<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use Spatie\LaravelData\DataCollection;

class PaginatedListResponse extends BaseData
{
    public function __construct(
        public string $object,
        public ?string $url,
        public bool $has_more,
        public int $total_item_count,
        public int $items_per_page,
        public int $current_page,
        public int $last_page,
        public DataCollection $data,
    ) {}
}
