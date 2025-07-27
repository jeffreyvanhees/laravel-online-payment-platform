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
        public string $url,
        public bool $has_more,
        public int $total_item_count,
        public int $items_per_page,
        public int $current_page,
        public int $last_page,
        #[DataCollectionOf(MerchantData::class)]
        public DataCollection $data,
    ) {
        parent::__construct(
            $object,
            $url,
            $has_more,
            $total_item_count,
            $items_per_page,
            $current_page,
            $last_page,
            $data
        );
    }
}
