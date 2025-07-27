<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Partners\GetConfigurationRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Partners\UpdateConfigurationRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing partner configuration
 *
 * Provides methods for retrieving and updating partner configuration settings.
 * Handles configuration for notification URLs and other partner-specific settings.
 */
class PartnersResource extends BaseResource
{
    /**
     * Get current partner configuration
     *
     * @return Response API response containing the current configuration
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function getConfiguration(): Response
    {
        return $this->connector->send(new GetConfigurationRequest);
    }

    /**
     * Update partner configuration
     *
     * @param  array  $data  Configuration data including notify_url and other settings
     * @return Response API response containing the updated configuration
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When configuration data is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function updateConfiguration(array $data): Response
    {
        return $this->connector->send(new UpdateConfigurationRequest($data));
    }
}
