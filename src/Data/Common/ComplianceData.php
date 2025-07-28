<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data\Common;

use JeffreyVanHees\OnlinePaymentPlatform\Data\BaseData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class ComplianceData extends BaseData
{
    public function __construct(
        public int $level,
        public string $status,
        public string $overview_url,
        #[DataCollectionOf(ComplianceRequirementData::class)]
        public DataCollection $requirements,
    ) {}
}