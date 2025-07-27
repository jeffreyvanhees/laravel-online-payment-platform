<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Files;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class CreateFileData extends BaseData
{
    public function __construct(
        public string $purpose,
        public string $file_path,
        public ?string $description = null,
    ) {}
}
