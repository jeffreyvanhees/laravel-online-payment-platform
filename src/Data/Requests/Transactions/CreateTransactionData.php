<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ProductData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class CreateTransactionData extends BaseData
{
    public function __construct(
        public string $merchant_uid,
        public int $total_price, // in cents
        public string $return_url,
        public string $notify_url,
        #[DataCollectionOf(ProductData::class)]
        public DataCollection|array $products,
        public ?string $payment_method = null,
        public ?int $partner_fee = null,
        public ?EscrowData $escrow = null,
        public ?BuyerData $buyer = null,
        public ?string $description = null,
        public ?string $reference = null,
        public ?array $metadata = null,
    ) {}
}
