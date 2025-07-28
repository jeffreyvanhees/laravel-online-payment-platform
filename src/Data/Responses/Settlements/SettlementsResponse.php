<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\PaginatedListResponse;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class SettlementsResponse extends PaginatedListResponse
{
    public function __construct(
        public string $object,
        public ?string $url,
        public bool $has_more,
        public int $total_item_count,
        public int $items_per_page,
        public int $current_page,
        public int $last_page,
        #[DataCollectionOf(SettlementData::class)]
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