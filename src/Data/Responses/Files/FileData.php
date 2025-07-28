<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Files;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class FileData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $purpose,
        public string $merchant_uid,
        public string $object_uid,
        public string $token,
        public string $url,
        public int $created,
        public int $updated,
        public int $expired,
        public ?array $metadata = null,
    ) {}
}
