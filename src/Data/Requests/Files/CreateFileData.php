<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Files;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateFileData extends BaseData
{
    public function __construct(
        public string $purpose, // 'organization_structure', 'coc_extract', 'bank_account_bank_statement', etc.
        public string $merchant_uid,
        public string $object_uid,
        public ?array $metadata = null,
    ) {}
}
