<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;

class ComplianceRequirementData extends BaseData
{
    public function __construct(
        public int $created,
        public int $updated,
        public string $type,
        public string $status,
        public ?string $object_type,
        public ?string $object_uid,
        public ?string $object_url,
        public ?string $object_redirect_url,
    ) {}
}
