<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\PaginatedListResponse;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class MerchantsResponse extends PaginatedListResponse
{
    public function __construct(
        public string $object,
        public ?string $url = null,
        public bool $has_more = false,
        public int $total_item_count = 0,
        public int $items_per_page = 10,
        public int $current_page = 1,
        public int $last_page = 1,
        #[DataCollectionOf(MerchantData::class)]
        public DataCollection $data,
    ) {
        parent::__construct(
            object: $object,
            url: $url,
            has_more: $has_more,
            total_item_count: $total_item_count,
            items_per_page: $items_per_page,
            current_page: $current_page,
            last_page: $last_page,
            data: $data
        );
    }
}
