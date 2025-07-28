# Spatie Laravel Data Implementation Guide

## Overview

Laravel Data is a powerful PHP package by Spatie for creating rich data objects in Laravel applications. It allows you to create data transfer objects (DTOs) with a single definition that can be used across your entire application.

## Key Features

- **Single Definition**: Describe your data once and use everywhere
- **Automatic Validation**: Built-in validation with attribute-based rules
- **Type Safety**: Strongly typed data objects with PHP 8+ features
- **API Integration**: Perfect for API requests/responses and external integrations
- **Transformation**: Automatic data transformation and serialization
- **TypeScript Generation**: Generate TypeScript definitions automatically
- **Laravel Integration**: Seamless integration with Laravel ecosystem

## Basic Usage

### Simple Data Object
```php
<?php

use Spatie\LaravelData\Data;

class SongData extends Data
{
    public function __construct(
        public string $title,
        public string $artist,
    ) {}
}
```

### With Validation
```php
<?php

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Min;

class CreateUserData extends Data
{
    public function __construct(
        #[Required]
        public string $name,
        
        #[Required, Email]  
        public string $email,
        
        #[Required, Min(8)]
        public string $password,
    ) {}
}
```

## API Integration Patterns

### Request DTOs
```php
<?php

class CreateTransactionData extends Data
{
    public function __construct(
        public string $merchant_uid,
        public int $total_price,
        public string $return_url,
        public string $notify_url,
        #[DataCollectionOf(ProductData::class)]
        public DataCollection $products,
        public ?string $payment_method = null,
        public ?array $metadata = null,
    ) {}
}
```

### Response DTOs  
```php
<?php

class TransactionData extends Data
{
    public function __construct(
        public string $uid,
        public string $status,
        public string $merchant_uid,
        public int $amount,
        public string $currency,
        public ?string $redirect_url = null,
        public ?array $metadata = null,
    ) {}
}
```

### Using with Saloon Requests
```php
<?php

use Saloon\Http\Request;
use Saloon\Http\Response;

class CreateTransactionRequest extends Request
{
    public function __construct(
        protected CreateTransactionData|array $data
    ) {}
    
    protected function defaultBody(): array
    {
        if ($this->data instanceof CreateTransactionData) {
            return $this->data->toArray();
        }
        
        return $this->data;
    }
    
    public function createDtoFromResponse(Response $response): TransactionData
    {
        return TransactionData::from($response->json());
    }
}
```

## Advanced Features

### Data Collections
```php
<?php

use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class OrderData extends Data
{
    public function __construct(
        public string $id,
        #[DataCollectionOf(ProductData::class)]
        public DataCollection $products,
    ) {}
}
```

### Nested Data Objects
```php
<?php

class CustomerData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public AddressData $address,
        public ?BankAccountData $bank_account = null,
    ) {}
}
```

### Transformations
```php
<?php

class UserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public Carbon $created_at,
    ) {}
    
    public static function fromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            created_at: $user->created_at,
        );
    }
}
```

## Best Practices for API SDKs

### 1. Consistent Naming
- Request DTOs: `Create{Resource}Data`, `Update{Resource}Data`
- Response DTOs: `{Resource}Data`
- Common data: `{Name}Data` (e.g., `AddressData`, `ProductData`)

### 2. Null Value Handling
```php
<?php

abstract class BaseData extends Data
{
    /**
     * Override toArray to filter out null values for API requests
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        return $this->filterNullValues($data);
    }
    
    private function filterNullValues(array $data): array
    {
        return array_filter($data, fn($value) => $value !== null);
    }
}
```

### 3. Validation Integration
```php
<?php

use Spatie\LaravelData\Attributes\Validation\Rule;

class CreateMerchantData extends BaseData
{
    public function __construct(
        #[Rule('required|string|max:255')]
        public string $type,
        
        #[Rule('required|string|max:8')]
        public string $coc_nr,
        
        #[Rule('required|string|in:nld,bel,deu')]
        public string $country,
        
        #[Rule('required|email|unique:merchants')]
        public string $emailaddress,
    ) {}
}
```

### 4. Type Safety with Enums
```php
<?php

enum MerchantType: string
{
    case CONSUMER = 'consumer';
    case BUSINESS = 'business';
}

class CreateMerchantData extends BaseData
{
    public function __construct(
        public MerchantType $type,
        public string $emailaddress,
        // ...
    ) {}
}
```

## Integration with Online Payment Platform SDK

The current OPP SDK implementation demonstrates excellent usage of Laravel Data:

### Proper BaseData Implementation
✅ Custom `BaseData` class with null filtering
✅ Consistent inheritance pattern
✅ Proper `toArray()` override

### Type-Safe Request DTOs
✅ `CreateTransactionData` with proper typing
✅ `CreateBusinessMerchantData` and `CreateConsumerMerchantData`
✅ Collection support with `#[DataCollectionOf]`

### Response DTOs
✅ `TransactionData`, `MerchantData` response objects
✅ Proper null handling for optional fields
✅ Integration with Saloon's `createDtoFromResponse()`

### Areas for Improvement
- Add DTOs for remaining array-based requests (`CreateMandateRequest`, `CreateFileUploadRequest`)
- Implement validation attributes where applicable
- Consider enum usage for status fields and constants

## Benefits for SDK Development

1. **Type Safety**: Catch errors at compile time
2. **API Documentation**: Self-documenting data structures
3. **Validation**: Built-in request validation
4. **Consistency**: Uniform data handling across the SDK
5. **Developer Experience**: IntelliSense and autocompletion
6. **Maintainability**: Easy to update and extend

## Conclusion

Laravel Data provides the perfect foundation for building type-safe, maintainable API SDKs. The Online Payment Platform SDK demonstrates excellent implementation of these patterns, making it a production-ready, developer-friendly package.

---

*Based on Spatie Laravel Data v4 documentation*
*Implementation guide for Online Payment Platform PHP SDK*