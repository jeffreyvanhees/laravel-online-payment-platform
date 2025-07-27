<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\CreateMerchantProfileRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantProfileRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantProfilesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing merchant profiles
 *
 * Profiles define different configurations and settings for merchants,
 * allowing for customized behavior across different business scenarios.
 */
class ProfilesResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Create a new profile for the merchant
     *
     * @param  array  $data  Profile data including name, configuration, etc.
     * @return Response API response containing the created profile data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(array $data): Response
    {
        return $this->connector->send(new CreateMerchantProfileRequest($this->merchantUid, $data));
    }

    /**
     * Retrieve a specific profile by UID
     *
     * @param  string  $profileUid  The unique identifier of the profile
     * @return Response API response containing the profile data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When profile is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $profileUid): Response
    {
        return $this->connector->send(new GetMerchantProfileRequest($this->merchantUid, $profileUid));
    }

    /**
     * List all profiles for the merchant
     *
     * @param  array  $params  Optional query parameters for filtering
     * @return Response API response containing a list of profiles
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetMerchantProfilesRequest($this->merchantUid, $params));
    }
}
