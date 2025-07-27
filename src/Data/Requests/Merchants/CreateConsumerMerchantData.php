<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\AddressData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class CreateConsumerMerchantData extends BaseData
{
    public function __construct(
        public string $type,
        public string $country,
        public string $emailaddress,
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $return_url = null,
        public ?string $notify_url = null,
        public ?string $phone_number = null,
        public ?string $language = null,
        public ?string $reference = null,
        #[DataCollectionOf(AddressData::class)]
        public ?DataCollection $addresses = null,
        public ?array $metadata = null,
    ) {}
}
