<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Mandates;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ProductData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class CreateMandateData extends BaseData
{
    public function __construct(
        public string $merchant_uid,
        public string $mandate_method, // 'emandate', 'payment', 'form', 'import'
        public string $mandate_type, // 'consumer', 'business'
        public string $mandate_repeat, // 'subscription'
        public int $mandate_amount, // in cents
        #[DataCollectionOf(ProductData::class)]
        public DataCollection|array $products,
        public int $total_price, // in cents
        public string $return_url,
        public string $notify_url,
        public ?string $issuer = null, // for 'emandate'
        public ?string $payment_method = null, // for 'payment'
        public ?string $bank_iban = null, // for 'import'
        public ?string $bank_bic = null, // for 'import'
        public ?string $bank_name = null, // for 'import'
        public ?string $description = null,
        public ?string $reference = null,
        public ?array $metadata = null,
    ) {}
}
