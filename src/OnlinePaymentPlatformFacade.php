<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\MerchantsResource merchants()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\TransactionsResource transactions()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\ChargesResource charges()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\MandatesResource mandates()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\WithdrawalsResource withdrawals()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\DisputesResource disputes()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\FilesResource files()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\PartnersResource partners()
 * @method static \JeffreyVanHees\OnlinePaymentPlatform\Resources\SettlementsResource settlements()
 *
 * @see \JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector
 */
class OnlinePaymentPlatformFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return OnlinePaymentPlatformConnector::class;
    }
}
