<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\AddressData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\BankAccountData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ContactData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class MerchantData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $type,
        public string $status,
        public ?string $country,
        public ?string $emailaddress = null,
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $company_name = null,
        public ?string $coc_nr = null,
        public ?string $vat_nr = null,
        public ?string $legal_name = null,
        public ?array $trading_names = null,
        public ?string $phone_number = null,
        public ?string $phone = null,
        public ?string $language = null,
        public ?string $reference = null,
        public ?string $notify_url = null,
        public ?string $return_url = null,
        public ?array $metadata = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        #[DataCollectionOf(AddressData::class)]
        public ?DataCollection $addresses = null,
        #[DataCollectionOf(ContactData::class)]
        public ?DataCollection $contacts = null,
        #[DataCollectionOf(BankAccountData::class)]
        public ?DataCollection $bank_accounts = null,
        #[DataCollectionOf(UBOData::class)]
        public ?DataCollection $ubos = null,
    ) {}
}
