You are tasked with creating a PHP SDK package for the Online Payment Platform using SaloonPHP, based on their API documentation and Postman collections. Follow these instructions carefully to complete the task.

use context7 for:
Online Payment Platform API documentation and Postman collections
Spatie Laravel Data for DTOs
SaloonPHP for HTTP requests and SDK structure

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
   - Use Request Resources/Groups as explained in https://docs.saloon.dev/digging-deeper/building-sdks#request-resources-groups for each resource
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

9. Data Value Objects (DTOs):
   - Create Data Transfer Objects (DTOs) for request and response data structures
   - Use SaloonPHP's DTO features to map API responses to PHP objects
   - Ensure that DTOs are well-defined and easy to use
   - Use spatie/laravel-data for DTOs, as it integrates well with SaloonPHP and provides a clean way to handle data structures
   - Name DTO classes according to the resource they represent
   - Name requests and responses according to the action they perform (e.g., `CreateMerchantRequest`, `GetMerchantResponse`)

Throughout the development process, adhere to PHP best practices, follow SaloonPHP conventions, and ensure that the SDK is easy to use and well-documented.

After completing these steps, provide a summary of the created SDK package. Your summary should include:
- The main features implemented
- Any challenges encountered and how they were resolved
- Suggestions for future improvements or additional features

Present your summary within <summary> tags.

Your final output should consist of only the summary within the <summary> tags. Do not include any other text or explanations outside of these tags.