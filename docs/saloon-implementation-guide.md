# Saloon PHP SDK Implementation Guide

This document contains implementation guidelines for building PHP SDKs using SaloonPHP, based on the official Saloon documentation.

## SDK Structure Best Practices

### 1. Connector Class (Main Entry Point)
The connector serves as the root of your SDK and should:
- Extend `Saloon\Http\Connector`
- Define the base URL via `resolveBaseUrl()`
- Configure authentication via `defaultAuth()`
- Set default headers via `defaultHeaders()`
- Provide resource methods for organized API access

### 2. Resource Classes (Request Groups)
Resources help organize related API endpoints:
- Extend `Saloon\Http\BaseResource`
- Group related requests (e.g., all merchant operations)
- Provide convenient methods for common operations
- Access connector instance via `$this->connector`

### 3. Request Classes
Individual API endpoints should:
- Extend `Saloon\Http\Request`
- Define HTTP method via `$method` property
- Implement `resolveEndpoint()` method
- Use proper naming conventions (e.g., `CreateTransactionRequest`)

### 4. Data Transfer Objects (DTOs)
For type-safe API responses:
- Use `spatie/laravel-data` for DTOs
- Implement `createDtoFromResponse()` on requests
- Name DTOs according to the resource they represent
- Map API responses to structured PHP objects

## Implementation Patterns

### Resource Organization
```php
class OnlinePaymentPlatformConnector extends Connector
{
    public function merchants(): MerchantsResource
    {
        return new MerchantsResource($this);
    }

    public function transactions(): TransactionsResource
    {
        return new TransactionsResource($this);
    }
}
```

### Request Implementation
```php
class CreateTransactionRequest extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/transactions';
    }

    public function createDtoFromResponse(Response $response): TransactionData
    {
        return TransactionData::from($response->json());
    }
}
```

### Testing Best Practices
- Use `MockClient` for testing
- Create fixtures for recorded responses
- Prevent stray requests in tests
- Use proper assertions for sent requests

### Pagination Support
- Implement `HasPagination` interface on connector
- Extend `PagedPaginator` for custom pagination
- Override pagination methods as needed

### Authentication
- Use built-in authenticators when possible
- Implement custom authenticators for specific needs
- Configure authentication in connector's `defaultAuth()`

## Code Quality Guidelines

1. **Type Safety**: Use strict types and proper type hints
2. **Error Handling**: Implement custom exceptions for API errors
3. **Documentation**: Document all public methods and classes
4. **Testing**: Write comprehensive tests for all functionality
5. **Standards**: Follow PSR standards and PHP best practices

## File Structure
```
src/
├── OnlinePaymentPlatformConnector.php
├── Resources/
│   ├── MerchantsResource.php
│   ├── TransactionsResource.php
│   └── ...
├── Requests/
│   ├── Merchants/
│   ├── Transactions/
│   └── ...
├── Data/
│   ├── Requests/
│   ├── Responses/
│   └── Common/
└── Exceptions/
```

This structure follows SaloonPHP best practices and provides a clean, maintainable SDK architecture.

---

*This guide was compiled from the official Saloon documentation on 2025-07-28*