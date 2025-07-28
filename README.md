# Laravel Online Payment Platform SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffreyvanhees/laravel-online-payment-platform.svg?style=flat-square)](https://packagist.org/packages/jeffreyvanhees/laravel-online-payment-platform)
[![Tests](https://img.shields.io/github/actions/workflow/status/jeffreyvanhees/laravel-online-payment-platform/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffreyvanhees/laravel-online-payment-platform/actions/workflows/run-tests.yml)
[![Coverage](https://img.shields.io/badge/Coverage-80.0%25-brightgreen?style=flat-square)](https://github.com/jeffreyvanhees/laravel-online-payment-platform/actions/workflows/coverage.yml)
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
- 📚 **Intuitive API** - Fluent interface: `OnlinePaymentPlatform::merchants()->ubos()->create()`
- 🔄 **Environment Support** - Seamless sandbox/production switching
- ⚡ **Exception Handling** - Detailed custom exceptions for all error scenarios
- 🔄 **Pagination Support** - Built-in pagination with SaloonPHP

## 📋 Requirements

- PHP 8.2 or higher
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

Configure your API credentials and URLs in your `.env` file:

```env
# API Configuration
OPP_API_KEY=your_production_api_key_here
OPP_SANDBOX_API_KEY=your_sandbox_api_key_here
OPP_SANDBOX=true

# URL Configuration (optional - set default URLs for webhooks/notifications)
OPP_NOTIFY_URL=https://yourapp.com/webhooks/opp
OPP_RETURN_URL=https://yourapp.com/payment/return

# Webhook Configuration (optional)
OPP_NOTIFY_SECRET=your_webhook_secret
```

## 📚 API Documentation

### 📋 API Endpoints Index

- **[Merchants](#merchants)** - Create consumer/business merchants, manage contacts, addresses, UBOs, and profiles
- **[Transactions](#transactions)** - Create payments, retrieve status, update transaction details  
- **[Refunds](#refunds)** - Process transaction refunds and list refund history
- **[Global Settlements](#global-settlements)** - Platform-wide settlement reporting and detailed rows
- **[Charges](#charges)** - Balance transfers between merchants and fee management
- **[Mandates](#mandates)** - SEPA Direct Debit mandates and recurring transactions
- **[Withdrawals](#withdrawals)** - Merchant payouts to bank accounts
- **[Disputes](#disputes)** - Handle transaction disputes and chargebacks
- **[Files](#files)** - Upload and manage documents for verification/evidence
- **[Partners](#partners)** - Partner configuration and settings management
- **[Pagination](#pagination)** - Handle paginated API responses

---

## 🎯 Usage

The package provides multiple ways to interact with the Online Payment Platform API:

### Using the Facade (Recommended)

The facade provides the cleanest and most Laravel-like API:

```php
<?php

use OnlinePaymentPlatform;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;

// Create a consumer merchant using DTO
$merchantData = new CreateConsumerMerchantData(
    type: 'consumer',
    country: 'NLD',
    emailaddress: 'john.doe@example.com',
    first_name: 'John',
    last_name: 'Doe',
);

$response = OnlinePaymentPlatform::merchants()->create($merchantData);

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

    public function createMerchant(CreateConsumerMerchantData $merchantData): string
    {
        $response = $this->opp->merchants()->create($merchantData);
        
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

use OnlinePaymentPlatform;
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

$merchantResponse = OnlinePaymentPlatform::merchants()->create($merchantData);
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

$transactionResponse = OnlinePaymentPlatform::transactions()->create($transactionData);
$transaction = $transactionResponse->dto();

echo "Payment URL: {$transaction->redirect_url}";
```

### Merchants

```php
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\{
    CreateConsumerMerchantData,
    CreateBusinessMerchantData
};

// Create consumer merchant
$consumerData = new CreateConsumerMerchantData(
    type: 'consumer',
    country: 'NLD',
    emailaddress: 'user@example.com',
    first_name: 'John',
    last_name: 'Doe',
    notify_url: 'https://yoursite.com/webhooks/opp',
);

$consumer = OnlinePaymentPlatform::merchants()->create($consumerData);

// Create business merchant
$businessData = new CreateBusinessMerchantData(
    type: 'business',
    country: 'NLD',
    emailaddress: 'business@example.com',
    coc_nr: '12345678',
    legal_name: 'Example B.V.',
    notify_url: 'https://yoursite.com/webhooks/opp',
);

$business = OnlinePaymentPlatform::merchants()->create($businessData);

// Retrieve and list merchants
$merchant = OnlinePaymentPlatform::merchants()->get('mer_123456789');
$merchants = OnlinePaymentPlatform::merchants()->list(['limit' => 50]);

// Add contacts and addresses
$contact = OnlinePaymentPlatform::merchants()->contacts('mer_123456789')->add([
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

$address = OnlinePaymentPlatform::merchants()->addresses('mer_123456789')->add([
    'type' => 'business',
    'address_line_1' => 'Main Street 123',
    'city' => 'Amsterdam',
    'zipcode' => '1000 AA',
    'country' => 'NLD',
]);

// Manage Ultimate Beneficial Owners (UBOs) for business merchants
$ubo = OnlinePaymentPlatform::merchants()->ubos('mer_123456789')->create([
    'name_first' => 'John',
    'name_last' => 'Doe', 
    'date_of_birth' => '1980-01-15',
    'country_of_residence' => 'NLD',
    'is_decision_maker' => true,
    'percentage_of_shares' => 25.5,
]);

// Create merchant profiles for different configurations
$profile = OnlinePaymentPlatform::merchants()->profiles('mer_123456789')->create([
    'name' => 'E-commerce Profile',
    'description' => 'Settings for online store',
    'webhook_url' => 'https://store.example.com/webhook',
    'return_url' => 'https://store.example.com/success',
    'is_default' => false,
]);
```

### Transactions

```php
// Create transactions
$transaction = OnlinePaymentPlatform::transactions()->create([
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
$transaction = OnlinePaymentPlatform::transactions()->get('tra_987654321');
$transactions = OnlinePaymentPlatform::transactions()->list(['limit' => 100]);

// Update transaction
$updated = OnlinePaymentPlatform::transactions()->update('tra_987654321', [
    'description' => 'Updated description',
]);
```

### Refunds

```php
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateRefundData;

// Create a refund for a transaction
$refundData = new CreateRefundData(
    amount: 1000, // €10.00 in cents
    payout_description: 'Refund for defective product',
    internal_reason: 'product_defect',
    metadata: ['reason' => 'customer_complaint']
);

$refund = OnlinePaymentPlatform::transactions()->refunds('tra_123456789')->create($refundData);

// List all refunds for a transaction
$refunds = OnlinePaymentPlatform::transactions()->refunds('tra_123456789')->list();

// Access refund data
if ($refund->successful()) {
    $refundData = $refund->dto();
    echo "Refund created: {$refundData->uid}";
    echo "Status: {$refundData->status}";
    echo "Amount: {$refundData->amount} cents";
}
```

### Global Settlements

```php
// List all platform settlements
$settlements = OnlinePaymentPlatform::settlements()->list([
    'status' => 'completed',
    'limit' => 50
]);

// Get detailed settlement specification rows
$settlementRows = OnlinePaymentPlatform::settlements()->specificationRows(
    settlementUid: 'set_123456789',
    specificationUid: 'spec_987654321'
);

// Access settlement data
foreach ($settlements->dto()->data as $settlement) {
    echo "Settlement: {$settlement->uid}";
    echo "Status: {$settlement->status}";
    echo "Total Amount: {$settlement->total_amount}";
    echo "Period: {$settlement->period_start} - {$settlement->period_end}";
}

// Access settlement row details
foreach ($settlementRows->dto()->data as $row) {
    echo "Type: {$row->type}";
    echo "Reference: {$row->reference}";
    echo "Amount: {$row->amount}";
    if ($row->amount_payable) {
        echo "Amount Payable: {$row->amount_payable}";
    }
}
```

### Charges

```php
// Create charges for balance transfers between merchants
$charge = OnlinePaymentPlatform::charges()->create([
    'type' => 'balance',
    'amount' => 1500, // €15.00 in cents
    'from_owner_uid' => 'mer_123456789',
    'to_owner_uid' => 'mer_987654321',
    'description' => 'Monthly platform fee',
    'metadata' => ['invoice_id' => 'INV-2024-001'],
]);

// Retrieve charge details
$charge = OnlinePaymentPlatform::charges()->get('cha_123456789');

// List charges with filters
$charges = OnlinePaymentPlatform::charges()->list([
    'from_owner_uid' => 'mer_123456789',
    'status' => 'completed',
    'limit' => 50,
]);
```

### Mandates

```php
// Create SEPA Direct Debit mandate
$mandate = OnlinePaymentPlatform::mandates()->create([
    'merchant_uid' => 'mer_123456789',
    'holder_name' => 'John Doe',
    'iban' => 'NL91ABNA0417164300',
    'bic' => 'ABNANL2A',
    'description' => 'Monthly subscription mandate',
    'reference' => 'SUBSCRIPTION-2024',
]);

// Retrieve mandate
$mandate = OnlinePaymentPlatform::mandates()->get('man_123456789');

// Create transaction using mandate
$transaction = OnlinePaymentPlatform::mandates()->transactions('man_123456789')->create([
    'amount' => 2500, // €25.00 in cents
    'description' => 'Monthly subscription payment',
]);

// Delete mandate
OnlinePaymentPlatform::mandates()->delete('man_123456789');
```

### Withdrawals

```php
// Create withdrawal to merchant's bank account
$withdrawal = OnlinePaymentPlatform::withdrawals()->create('mer_123456789', [
    'amount' => 50000, // €500.00 in cents
    'currency' => 'EUR',
    'bank_account_uid' => 'ban_123456789',
    'description' => 'Weekly payout',
    'reference' => 'PAYOUT-2024-W01',
]);

// Retrieve withdrawal status
$withdrawal = OnlinePaymentPlatform::withdrawals()->get('wit_123456789');

// List withdrawals for a merchant
$withdrawals = OnlinePaymentPlatform::withdrawals()->list([
    'merchant_uid' => 'mer_123456789',
    'status' => 'completed',
    'limit' => 25,
]);

// Cancel pending withdrawal
OnlinePaymentPlatform::withdrawals()->delete('wit_123456789');
```

### Disputes

```php
// Create dispute for a transaction
$dispute = OnlinePaymentPlatform::disputes()->create([
    'transaction_uid' => 'tra_123456789',
    'amount' => 1000, // €10.00 in cents
    'reason' => 'Product not received',
    'message' => 'Customer claims product was never delivered',
    'evidence' => [
        'tracking_number' => 'TRACK123456',
        'shipping_date' => '2024-01-15',
    ],
]);

// Retrieve dispute with transaction details
$dispute = OnlinePaymentPlatform::disputes()->get('dis_123456789', [
    'include' => 'transaction',
]);

// List all disputes
$disputes = OnlinePaymentPlatform::disputes()->list([
    'status' => 'pending',
    'created_after' => '2024-01-01',
]);
```

### Files

```php
// Create file upload token
$upload = OnlinePaymentPlatform::files()->createUpload([
    'filename' => 'invoice.pdf',
    'purpose' => 'dispute_evidence',
]);

// Upload the actual file
$file = OnlinePaymentPlatform::files()->upload(
    fileUid: $upload->dto()->uid,
    token: $upload->dto()->token,
    filePath: '/path/to/invoice.pdf',
    fileName: 'invoice.pdf'
);

// List uploaded files
$files = OnlinePaymentPlatform::files()->list([
    'purpose' => 'dispute_evidence',
    'created_after' => '2024-01-01',
]);
```

### Partners

```php
// Get partner configuration
$config = OnlinePaymentPlatform::partners()->getConfiguration();

// Update partner settings
$updated = OnlinePaymentPlatform::partners()->updateConfiguration([
    'webhook_url' => 'https://partner.example.com/webhooks',
    'notification_email' => 'notifications@partner.com',
    'settings' => [
        'auto_approve_merchants' => false,
        'require_vat_number' => true,
    ],
]);
```

### Pagination

When building API integrations, you may encounter scenarios where the server doesn't provide all results in a single response. Instead, it divides results into several pages. This strategy is called pagination, and integrating it into your application can be tedious and repetitive.

With this SDK, you can leverage SaloonPHP's powerful pagination plugin to reduce boilerplate code and iterate through every result across every page in one loop. **When you set a limit, it will automatically fetch as many pages as necessary to get all results.**

```php
// Get single page of results
$response = OnlinePaymentPlatform::merchants()->list(['limit' => 25]);
$merchants = $response->dto();

// Process single page
foreach ($merchants->data as $merchant) {
    echo "Merchant: {$merchant->uid} - {$merchant->emailaddress}\n";
}

// ✨ Magic: Iterate through ALL pages automatically
foreach (OnlinePaymentPlatform::merchants()->list(['limit' => 25])->paginate() as $response) {
    $merchants = $response->dto();
    
    foreach ($merchants->data as $merchant) {
        echo "Merchant: {$merchant->uid} - {$merchant->emailaddress}\n";
    }
    
    echo "Processed page with " . count($merchants->data) . " merchants\n";
}

// The paginate() method handles all the complexity:
// - Automatically fetches next pages
// - Handles different pagination strategies  
// - Stops when no more results
// - Memory efficient iteration
```

**Key Benefits:**
- **Automatic**: No manual page tracking or URL construction
- **Memory Efficient**: Processes one page at a time, not all at once
- **Error Resilient**: Handles API errors gracefully
- **Flexible**: Works with any limit size you specify

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
    $response = OnlinePaymentPlatform::merchants()->create($invalidData);
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

# Run tests with coverage report
composer test-coverage

# Generate HTML coverage report
composer test-coverage-html

# Generate Clover XML coverage report  
composer test-coverage-clover

# Record new HTTP interactions (requires real API credentials)
composer record

# Run tests using recorded interactions
composer replay
```

### Test Coverage

The package includes comprehensive tests covering all API endpoints with **80.0% code coverage**:

- ✅ **240 tests** covering all major endpoints and DTOs  
- ✅ **1053 assertions** ensuring functionality  
- ✅ **Merchant operations** - CRUD, contacts, addresses, bank accounts, settlements, UBOs, profiles
- ✅ **Transaction lifecycle** - create, retrieve, update, delete
- ✅ **Payment flows** - charges, mandates, withdrawals, disputes
- ✅ **File operations** - upload, retrieval, management  
- ✅ **Partner configuration** - settings management
- ✅ **Error handling** - graceful sandbox environment handling

Coverage reports are generated in multiple formats:
- **Terminal**: Real-time coverage during test runs
- **HTML**: Detailed browsable report in `coverage-report/`
- **XML**: Machine-readable format for CI/CD integration

## 📖 Advanced Usage

### Custom HTTP Client Configuration

> **Note**: The package automatically registers the connector in Laravel's service container using the configuration from `config/opp.php`. No manual service provider binding is needed.

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


## 🚀 Releases

This package uses automated versioning and releases:

- **Automatic**: Patch version bump on every push to `main` branch
- **Manual**: Use commit messages to control version bumps:
  - `[major]` in commit message → Major version bump (e.g., 1.0.0 → 2.0.0)
  - `[minor]` in commit message → Minor version bump (e.g., 1.0.0 → 1.1.0)
  - Default → Patch version bump (e.g., 1.0.0 → 1.0.1)
- **Skip Release**: Add `[skip release]` to commit message to skip version bump

### Manual Release Trigger

You can also manually trigger a release via GitHub Actions:
1. Go to Actions → Release workflow
2. Click "Run workflow"
3. Select the version bump type (patch/minor/major)

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute.

## 📝 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🙏 Credits

- Built with [SaloonPHP](https://docs.saloon.dev)
- DTOs powered by [Spatie Laravel Data](https://spatie.be/docs/laravel-data)
- Testing with [Pest PHP](https://pestphp.com)