# Laravel Online Payment Platform SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffreyvanhees/laravel-online-payment-platform.svg?style=flat-square)](https://packagist.org/packages/jeffreyvanhees/laravel-online-payment-platform)
[![Tests](https://img.shields.io/github/actions/workflow/status/jeffreyvanhees/laravel-online-payment-platform/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffreyvanhees/laravel-online-payment-platform/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffreyvanhees/laravel-online-payment-platform.svg?style=flat-square)](https://packagist.org/packages/jeffreyvanhees/laravel-online-payment-platform)

A modern Laravel package for integrating with the [Online Payment Platform](https://onlinepaymentplatform.com) API. Built with [SaloonPHP](https://docs.saloon.dev) and [Spatie Laravel Data](https://spatie.be/docs/laravel-data) for an excellent developer experience.

> [!WARNING]  
> This package is not affiliated with, endorsed by, or officially connected to Online Payment Platform B.V. It is an independent, community-driven implementation for integrating with their API.
> Also, this package is not intended for production use yet. It is still in development and may contain breaking changes.

## ✨ Features

- 🚀 **Laravel 11 & 12 Support** - Full support for the latest Laravel versions
- 🛡️ **Type Safety** - Fully typed DTOs using Spatie Laravel Data
- 🏗️ **Service Container** - Native Laravel service container integration
- 🎭 **Facade Support** - Clean, expressive API using Laravel facades
- 🔧 **SaloonPHP Foundation** - Built on the robust SaloonPHP HTTP client
- 🧪 **Comprehensive Testing** - HTTP recording/replay for reliable tests
- 📚 **Intuitive API** - Fluent interface: `Opp::merchants()->contacts()->add()`
- 🔄 **Environment Support** - Seamless sandbox/production switching
- ⚡ **Exception Handling** - Detailed custom exceptions for all error scenarios
- 🔄 **Pagination Support** - Built-in pagination with SaloonPHP

## 📋 Requirements

- PHP 8.1 or higher
- Laravel 11.0 or 12.0

## 🚀 Installation

Install the package via Composer:

```bash
composer require jeffreyvanhees/laravel-online-payment-platform
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=opp-config
```

Configure your API credentials in your `.env` file:

```env
OPP_API_KEY=your_production_api_key_here
OPP_SANDBOX_API_KEY=your_sandbox_api_key_here
OPP_SANDBOX=true
```

## 🎯 Usage

The package provides multiple ways to interact with the Online Payment Platform API:

### Using the Facade (Recommended)

The facade provides the cleanest and most Laravel-like API:

```php
<?php

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformFacade as Opp;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;

// Create a consumer merchant
$response = Opp::merchants()->create([
    'type' => 'consumer',
    'country' => 'NLD',
    'emailaddress' => 'john.doe@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'notify_url' => 'https://yoursite.com/webhooks/opp',
]);

if ($response->successful()) {
    $merchant = $response->dto();
    echo "Created merchant: {$merchant->uid}";
}
```

### Using Dependency Injection

Inject the connector directly into your classes:

```php
<?php

namespace App\Services;

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;

class PaymentService
{
    public function __construct(
        private OnlinePaymentPlatformConnector $opp
    ) {}

    public function createMerchant(array $data): string
    {
        $response = $this->opp->merchants()->create($data);
        
        if (!$response->successful()) {
            throw new \Exception('Failed to create merchant');
        }

        return $response->dto()->uid;
    }
}
```

### Using Type-Safe DTOs

For maximum type safety and IDE support, use the provided Data Transfer Objects:

```php
<?php

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformFacade as Opp;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateTransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ProductData;

// Create merchant using DTO
$merchantData = new CreateConsumerMerchantData(
    type: 'consumer',
    country: 'NLD',
    emailaddress: 'jane.doe@example.com',
    first_name: 'Jane',
    last_name: 'Doe',
    notify_url: 'https://yoursite.com/webhooks/opp'
);

$merchantResponse = Opp::merchants()->create($merchantData);
$merchant = $merchantResponse->dto();

// Create transaction with products
$transactionData = new CreateTransactionData(
    merchant_uid: $merchant->uid,
    total_price: 2500, // €25.00 in cents
    return_url: 'https://yoursite.com/payment/return',
    notify_url: 'https://yoursite.com/webhooks/opp',
    products: ProductData::collect([
        [
            'name' => 'Premium Subscription',
            'quantity' => 1,
            'price' => 2500,
        ],
    ])
);

$transactionResponse = Opp::transactions()->create($transactionData);
$transaction = $transactionResponse->dto();

echo "Payment URL: {$transaction->redirect_url}";
```

## 📚 API Documentation

### Merchants

```php
// Create merchants
$consumer = Opp::merchants()->create([
    'type' => 'consumer',
    'country' => 'NLD',
    'emailaddress' => 'user@example.com',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'notify_url' => 'https://yoursite.com/webhooks/opp',
]);

$business = Opp::merchants()->create([
    'type' => 'business',
    'country' => 'NLD',
    'emailaddress' => 'business@example.com',
    'coc_nr' => '12345678',
    'legal_name' => 'Example B.V.',
    'notify_url' => 'https://yoursite.com/webhooks/opp',
]);

// Retrieve and list merchants
$merchant = Opp::merchants()->get('mer_123456789');
$merchants = Opp::merchants()->list(['limit' => 50]);

// Add contacts and addresses
$contact = Opp::merchants()->contacts('mer_123456789')->add([
    'type' => 'representative',
    'gender' => 'm',
    'title' => 'mr',
    'name' => [
        'first' => 'John',
        'last' => 'Smith',
        'initials' => 'J.S.',
        'names_given' => 'John',
    ],
    'emailaddresses' => [
        ['emailaddress' => 'john@example.com']
    ],
    'phonenumbers' => [
        ['phonenumber' => '+31612345678']
    ],
]);

$address = Opp::merchants()->addresses('mer_123456789')->add([
    'type' => 'business',
    'address_line_1' => 'Main Street 123',
    'city' => 'Amsterdam',
    'zipcode' => '1000 AA',
    'country' => 'NLD',
]);
```

### Transactions

```php
// Create transactions
$transaction = Opp::transactions()->create([
    'merchant_uid' => 'mer_123456789',
    'total_price' => 1000, // €10.00 in cents
    'products' => [
        [
            'name' => 'Product Name',
            'quantity' => 1,
            'price' => 1000,
        ],
    ],
    'return_url' => 'https://yoursite.com/payment/return',
    'notify_url' => 'https://yoursite.com/webhooks/opp',
]);

// Retrieve and list transactions
$transaction = Opp::transactions()->get('tra_987654321');
$transactions = Opp::transactions()->list(['limit' => 100]);

// Update transaction
$updated = Opp::transactions()->update('tra_987654321', [
    'description' => 'Updated description',
]);
```

### Pagination

The package supports automatic pagination through SaloonPHP:

```php
// Get paginated results
$paginator = Opp::merchants()->list(['limit' => 25]);

// Iterate through all pages
foreach ($paginator->paginate() as $response) {
    $merchants = $response->dto();
    
    foreach ($merchants->data as $merchant) {
        echo "Merchant: {$merchant->uid} - {$merchant->emailaddress}\n";
    }
}
```

## ⚙️ Configuration

The configuration file (`config/opp.php`) allows you to customize various aspects:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    */
    'api_key' => env('OPP_API_KEY'),
    'sandbox_api_key' => env('OPP_SANDBOX_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    */
    'sandbox' => env('OPP_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | HTTP Configuration
    |--------------------------------------------------------------------------
    */
    'timeout' => env('OPP_TIMEOUT', 30),
    'retry' => [
        'times' => env('OPP_RETRY_TIMES', 3),
        'sleep' => env('OPP_RETRY_SLEEP', 1000),
    ],
];
```

## 🚨 Error Handling

The package provides detailed exception handling:

```php
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\{
    OppException,
    AuthenticationException,
    ValidationException,
    RateLimitException,
    ApiException
};

try {
    $response = Opp::merchants()->create($invalidData);
} catch (ValidationException $e) {
    // Handle validation errors
    $errors = $e->getValidationErrors();
    foreach ($errors as $field => $messages) {
        echo "{$field}: " . implode(', ', $messages);
    }
} catch (AuthenticationException $e) {
    // Handle authentication issues
    echo "Authentication failed: " . $e->getMessage();
} catch (RateLimitException $e) {
    // Handle rate limiting
    echo "Rate limit exceeded. Retry after: " . $e->getRetryAfter();
} catch (OppException $e) {
    // Handle general API errors
    echo "API Error: " . $e->getMessage();
}
```

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Record new HTTP interactions (requires real API credentials)
composer record

# Run tests using recorded interactions
composer replay
```

## 📖 Advanced Usage

### Service Provider Registration

You can bind custom configurations in your `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OnlinePaymentPlatformConnector::class, function ($app) {
            return new OnlinePaymentPlatformConnector(
                apiKey: config('opp.api_key'),
                sandbox: config('opp.sandbox')
            );
        });
    }
}
```

### Custom HTTP Client Configuration

```php
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

$connector = new OnlinePaymentPlatformConnector(
    apiKey: 'your-api-key',
    sandbox: true
);

// Add custom middleware
$connector->middleware()->onRequest(function ($request) {
    $request->headers()->add('Custom-Header', 'value');
    return $request;
});

// Add retry logic
$connector->middleware()->onResponse(function ($response) {
    if ($response->status() === 429) {
        sleep(1);
        return $response->throw(); // Retry
    }
    return $response;
});
```


## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute.

## 📝 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🙏 Credits

- Built with [SaloonPHP](https://docs.saloon.dev)
- DTOs powered by [Spatie Laravel Data](https://spatie.be/docs/laravel-data)
- Testing with [Pest PHP](https://pestphp.com)