<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ProductData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\BuyerData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\EscrowData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class TransactionData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public string $merchant_uid,
        public int $amount, // API returns 'amount', not 'total_price'
        public string $currency,
        public ?string $redirect_url = null, // API returns 'redirect_url', not 'payment_url'
        public ?string $return_url = null,
        public ?string $notify_url = null,
        public ?string $payment_method = null,
        public ?string $payment_flow = null,
        public ?array $payment_details = null,
        public ?string $channel = null,
        public ?array $channel_data = null,
        public ?bool $has_checkout = null,
        public ?string $buyer_uid = null,
        public ?string $profile_uid = null,
        public ?bool $livemode = null,
        public ?string $object = null,
        public ?int $created = null,
        public ?int $updated = null,
        public ?int $completed = null,
        public ?array $metadata = null,
        public ?array $statuses = null,
        public ?array $order = null,
        public ?EscrowData $escrow = null,
        public ?array $fees = null,
        public ?array $refunds = null,
    ) {
    }
}