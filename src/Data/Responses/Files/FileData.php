<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Files;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class FileData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $purpose,
        public string $filename,
        public int $size,
        public string $mime_type,
        public string $url,
        public ?string $description = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}
}
