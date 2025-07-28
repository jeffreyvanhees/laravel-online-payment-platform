<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform;

use JeffreyVanHees\OnlinePaymentPlatform\Resources\ChargesResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\DisputesResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\FilesResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\MandatesResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\MerchantsResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\PartnersResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\SettlementsResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\TransactionsResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\WithdrawalsResource;
use Saloon\Contracts\Authenticator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Main connector class for the Online Payment Platform API
 *
 * This class serves as the entry point for all API interactions with the Online Payment Platform.
 * It handles authentication, base URL configuration, and provides access to all resource endpoints.
 */
class OnlinePaymentPlatformConnector extends Connector implements HasBody, HasPagination
{
    use HasJsonBody;

    /**
     * Initialize the OPP API connector
     *
     * @param  string  $apiKey  The API key for authentication
     * @param  bool  $sandbox  Whether to use sandbox environment (default: true)
     */
    public function __construct(
        protected string $apiKey,
        protected bool $sandbox = true
    ) {}

    /**
     * Resolve the base URL for API requests
     *
     * @return string The base URL for API requests
     */
    public function resolveBaseUrl(): string
    {
        return $this->sandbox
            ? 'https://api-sandbox.onlinebetaalplatform.nl/v1'
            : 'https://api.onlinebetaalplatform.nl/v1';
    }

    /**
     * Configure default authentication for requests
     *
     * @return Authenticator|null The authenticator instance
     */
    protected function defaultAuth(): ?Authenticator
    {
        return new TokenAuthenticator($this->apiKey);
    }

    /**
     * Configure default headers for requests
     *
     * @return array<string, string> Default headers
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Configure default body for all requests
     */
    protected function defaultBody(): array
    {
        $body = [];

        // Add notify_url if configured (only in Laravel environment)
        try {
            if (function_exists('config') && config('opp.urls.notify')) {
                $body['notify_url'] = $this->getNotifyUrl();
            }
        } catch (\Exception $e) {
            // Ignore config errors in non-Laravel environments
        }

        // Add return_url if configured (only in Laravel environment)
        try {
            if (function_exists('config') && config('opp.urls.return')) {
                $body['return_url'] = $this->getReturnUrl();
            }
        } catch (\Exception $e) {
            // Ignore config errors in non-Laravel environments
        }

        return $body;
    }

    /**
     * Get the merchants resource for merchant-related operations
     *
     * @return MerchantsResource Merchants resource instance
     */
    public function merchants(): MerchantsResource
    {
        return new MerchantsResource($this);
    }

    /**
     * Get the transactions resource for transaction-related operations
     *
     * @return TransactionsResource Transactions resource instance
     */
    public function transactions(): TransactionsResource
    {
        return new TransactionsResource($this);
    }

    /**
     * Get the charges resource for charge-related operations
     *
     * @return ChargesResource Charges resource instance
     */
    public function charges(): ChargesResource
    {
        return new ChargesResource($this);
    }

    /**
     * Get the mandates resource for mandate-related operations
     *
     * @return MandatesResource Mandates resource instance
     */
    public function mandates(): MandatesResource
    {
        return new MandatesResource($this);
    }

    /**
     * Get the withdrawals resource for withdrawal-related operations
     *
     * @return WithdrawalsResource Withdrawals resource instance
     */
    public function withdrawals(): WithdrawalsResource
    {
        return new WithdrawalsResource($this);
    }

    /**
     * Get the disputes resource for dispute-related operations
     *
     * @return DisputesResource Disputes resource instance
     */
    public function disputes(): DisputesResource
    {
        return new DisputesResource($this);
    }

    /**
     * Get the files resource for file-related operations
     *
     * @return FilesResource Files resource instance
     */
    public function files(): FilesResource
    {
        return new FilesResource($this);
    }

    /**
     * Get the partners resource for partner-related operations
     *
     * @return PartnersResource Partners resource instance
     */
    public function partners(): PartnersResource
    {
        return new PartnersResource($this);
    }

    /**
     * Get the settlements resource for settlement-related operations
     *
     * @return SettlementsResource Settlements resource instance
     */
    public function settlements(): SettlementsResource
    {
        return new SettlementsResource($this);
    }

    /**
     * Create a paginated iterator for API responses
     *
     * @param  Request  $request  The request to paginate
     * @return PagedPaginator Paginated iterator
     */
    public function paginate(Request $request): PagedPaginator
    {
        return new class(connector: $this, request: $request) extends PagedPaginator
        {
            protected ?int $perPageLimit = 100;

            protected function isLastPage(Response $response): bool
            {
                // Use Online Payment Platform's pagination format
                $hasMore = $response->json('has_more', false);

                return ! $hasMore;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json('data', []);
            }

            protected function applyPagination(Request $request): Request
            {
                // Use OPP's pagination parameters
                $request->query()->add('page', $this->currentPage);

                if (isset($this->perPageLimit)) {
                    $request->query()->add('perpage', $this->perPageLimit);
                }

                return $request;
            }
        };
    }

    /**
     * Get the configured notify URL
     *
     * @return string|null The configured notify URL
     */
    public function getNotifyUrl(): ?string
    {
        try {
            return function_exists('config') ? config('opp.urls.notify') : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the configured return URL
     *
     * @return string|null The configured return URL
     */
    public function getReturnUrl(): ?string
    {
        try {
            return function_exists('config') ? config('opp.urls.return') : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
