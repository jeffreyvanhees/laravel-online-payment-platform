<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Data;

use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

abstract class BaseData extends Data
{
    use WireableData;

    /**
     * Override toArray to filter out null values for API requests
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        // Filter out null values recursively
        return $this->filterNullValues($data);
    }

    /**
     * Recursively filter null values from array
     */
    private function filterNullValues(array $data): array
    {
        $filtered = [];

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                $filteredValue = $this->filterNullValues($value);
                if (! empty($filteredValue)) {
                    $filtered[$key] = $filteredValue;
                }
            } else {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }
}
