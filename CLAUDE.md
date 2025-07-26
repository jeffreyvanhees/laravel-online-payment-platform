# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

You are tasked with creating a PHP SDK package for the Online Payment Platform using SaloonPHP, based on their API documentation and Postman collection. Follow these instructions carefully to complete the task.

First, review the API documentation:
https://docs.onlinepaymentplatform.com/   

Next, examine the Postman collection:
https://docs.onlinepaymentplatform.com/downloads/merchants-api.postman_collection.json
https://docs.onlinepaymentplatform.com/downloads/files-api.postman_collection.json
https://docs.onlinepaymentplatform.com/downloads/transactions-api.postman_collection.json
https://docs.onlinepaymentplatform.com/downloads/mandates-api.postman_collection.json
https://docs.onlinepaymentplatform.com/downloads/charges-api.postman_collection.json
https://docs.onlinepaymentplatform.com/downloads/withdrawals-api.postman_collection.json
https://docs.onlinepaymentplatform.com/downloads/dispute-api.postman_collection.json
https://docs.onlinepaymentplatform.com/downloads/partner-api.postman_collection.json

Also, review SaloonPHP documentation and source:
https://docs.saloon.dev
https://github.com/saloonphp/saloon

There should also be a good README.md for explaining how to use the package.

Now, follow these steps to create the SDK package:

1. Analyze the API documentation and Postman collection:
   - Identify the main API endpoints and their functionalities
   - Note the authentication method used
   - Understand the request and response structures for each endpoint

2. Create the SDK structure:
   - Set up a new PHP project using Composer
   - Install SaloonPHP as a dependency
   - Create a main SDK class that will serve as the entry point for the SDK

3. Implement API endpoints as methods:
   - For each identified endpoint, create a corresponding method in the SDK class
   - Use Request Resources/Groups like explained in https://docs.saloon.dev/digging-deeper/building-sdks#request-resources-groups for each resource 
   - Use SaloonPHP's features to handle HTTP requests and responses
   - Ensure that method names are descriptive and follow PHP naming conventions

4. Implement error handling and response parsing:
   - Create custom exception classes for API-specific errors
   - Parse API responses and return appropriate PHP objects or arrays
   - Handle rate limiting and other API-specific requirements

5. Add authentication mechanism:
   - Implement the authentication method specified in the API documentation
   - Use SaloonPHP's authentication features if applicable

6. Create tests for the SDK:
   - Write unit tests for each implemented method
   - Include integration tests that make actual API calls (use a sandbox environment if available)

7. Document the SDK:
   - Write clear and concise documentation for each method
   - Include usage examples and any necessary setup instructions
   - Document any dependencies or requirements for using the SDK

8. Package the SDK:
   - Ensure the project follows PSR-4 autoloading standards
   - Create a composer.json file with appropriate metadata and dependencies
   - Prepare the package for distribution via Packagist or as a standalone package

9. Data Value Objects (DTO's)
    - Create Data Transfer Objects (DTOs) for request and response data structures
    - Use SaloonPHP's DTO features to map API responses to PHP objects
    - Ensure that DTOs are well-defined and easy to use
    - Use spatie/laravel-data for DTOs, as it integrates well with SaloonPHP and provides a clean way to handle data structures.
    - Name DTO classes according to the resource they represent
    - Name requests and responses according to the action they perform (e.g., `CreateMerchantRequest`, `GetMerchantResponse`)
    
After completing these steps, provide a summary of the created SDK package, including:
- The main features implemented
- Any challenges encountered and how they were resolved
- Suggestions for future improvements or additional features

Present your summary within <summary> tags.

Remember to adhere to PHP best practices, follow SaloonPHP conventions, and ensure that the SDK is easy to use and well-documented.

## Development Commands

### Testing
- `composer test` - Run all tests using Pest
- `composer test-coverage` - Run tests with coverage report
- `composer record` - Record HTTP interactions for future replay (uses Lawman)
- `composer replay` - Run tests using recorded HTTP interactions

### Recording and Replaying HTTP Interactions
This project uses SaloonPHP's Lawman plugin for recording and replaying HTTP interactions in tests. This allows you to:

1. **Record Mode**: Make real API calls and record the responses for future use
   - Run `composer record` to execute tests that make real API calls
   - Recordings are stored in `tests/Fixtures` directory
   - Use `@group recording` on tests that should record interactions

2. **Replay Mode**: Use recorded responses instead of making real API calls
   - Run `composer replay` to execute tests using recorded responses
   - Use `@group replay` on tests that should use recorded data
   - Perfect for CI/CD environments where you don't want to make real API calls

### Environment Variables
- `OPP_API_KEY` - Production API key for testing
- `OPP_SANDBOX_API_KEY` - Sandbox API key for testing (recommended for development)

## Architecture

### Core Components

1. **OppConnector** - Main entry point that handles authentication and base URL configuration
2. **Resources** - Organized API endpoints (Merchants, Transactions, Charges, Mandates, etc.)
3. **Subresources** - Nested resources for better API organization (e.g., `->merchants()->contacts()->add()`)
4. **Requests** - Individual API request classes following SaloonPHP patterns
5. **Exceptions** - Custom exception classes for different error types

### Resource Structure
- `MerchantsResource` - Merchant CRUD operations with subresources:
  - `ContactsResource` - Merchant contact management
  - `AddressesResource` - Merchant address management
  - `BankAccountsResource` - Merchant bank account management
  - `SettlementsResource` - Merchant settlement retrieval
- `TransactionsResource` - Transaction operations
- `ChargesResource` - Charge management
- `MandatesResource` - Mandate operations with transaction support

### Authentication
Uses Bearer Token authentication with automatic header injection. Supports both sandbox and production environments.

### Error Handling
Custom exception hierarchy:
- `OppException` - Base exception with context support
- `AuthenticationException` - API key and auth errors
- `ValidationException` - Request validation errors
- `RateLimitException` - Rate limiting errors
- `ApiException` - General API errors