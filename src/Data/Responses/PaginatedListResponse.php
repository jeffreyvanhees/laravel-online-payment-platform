<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use Spatie\LaravelData\DataCollection;

class PaginatedListResponse extends BaseData
{
    public function __construct(
        public string $object,
        public ?string $url = null,
        public bool $has_more = false,
        public int $total_item_count = 0,
        public int $items_per_page = 10,
        public int $current_page = 1,
        public int $last_page = 1,
        public DataCollection $data,
    ) {}
}
