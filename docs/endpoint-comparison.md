# Online Payment Platform SDK - Endpoint Implementation Analysis

## Overview
This document compares the documented API endpoints from the Online Payment Platform with the current PHP SDK implementation to identify gaps and areas for improvement.

## Documented API Endpoints (from Context7)

### Core API Endpoints
1. **Merchants API**
   - ✅ `POST /v1/merchants` - Create business merchant
   - ✅ `GET /v1/merchants/{merchant_uid}` - Get merchant details  
   - ✅ `GET /v1/merchants` - List merchants

2. **Merchant Bank Accounts API**
   - ✅ `POST /v1/merchants/{merchant_uid}/bank_accounts` - Create bank account
   - ✅ `GET /v1/merchants/{merchant_uid}/bank_accounts` - List merchant bank accounts

3. **Transactions API**
   - ✅ `POST /v1/transactions` - Create transaction
   - ✅ `GET /v1/transactions/{transaction_uid}` - Get transaction details
   - ✅ `GET /v1/transactions` - List transactions
   - ✅ `PUT /v1/transactions/{transaction_uid}` - Update transaction
   - ✅ `DELETE /v1/transactions/{transaction_uid}` - Delete transaction

4. **Refunds API** 
   - ❌ `POST /v1/transactions/{transaction_uid}/refunds` - Create refund
   - ❌ `GET /v1/transactions/{transaction_uid}/refunds` - List transaction refunds

5. **Mandates API**
   - ✅ `POST /v1/mandates` - Create mandate
   - ✅ `GET /v1/mandates/{mandate_uid}` - Get mandate details
   - ✅ `GET /v1/mandates` - List mandates
   - ✅ `DELETE /v1/mandates/{mandate_uid}` - Delete mandate
   - ✅ `POST /v1/mandates/{mandate_uid}/transactions` - Create mandate transaction

6. **Settlements API**
   - ❌ `GET /v1/settlements` - List all settlements
   - ✅ `GET /v1/merchants/{merchant_uid}/settlements` - Get merchant settlements
   - ❌ `GET /v1/settlements/{settlement_uid}/specifications/{specification_uid}/rows` - Get settlement specification rows

7. **Files API**
   - ✅ `POST https://files-sandbox.onlinebetaalplatform.nl/v1/uploads` - Create file upload link
   - ✅ `POST https://files-sandbox.onlinebetaalplatform.nl/v1/uploads/{file_uid}` - Upload file
   - ✅ `GET /v1/files` - List files

8. **Partners API (Configuration)**
   - ✅ `GET /v1/partners/configuration` - Get partner configuration  
   - ✅ `POST /v1/partners/configuration` - Update partner configuration

## Missing Endpoints

### 1. Refunds API (High Priority)
The refunds API is completely missing from the current implementation. According to the documentation, refunds are crucial for:
- Creating refunds for completed transactions
- Retrieving refund history for transactions
- Managing chargebacks and dispute resolution

**Required Implementation:**

#### 1.1 Create Refund Endpoint
- **Endpoint**: `POST /v1/transactions/{transaction_uid}/refunds`
- **Request Class**: `CreateRefundRequest`
- **Request DTO**: `CreateRefundData`
- **Response DTO**: `RefundData`
- **Resource Method**: `TransactionsResource::refunds(string $transactionUid)->create(CreateRefundData $data)`

**Request Structure:**
```php
class CreateRefundData extends BaseData
{
    public function __construct(
        public int $amount, // in cents
        public ?string $payout_description = null,
        public ?string $internal_reason = null,
        public ?array $metadata = null,
    ) {}
}
```

**Response Structure:**
```php
class RefundData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public int $amount,
        public ?string $payout_description = null,
        public ?string $internal_reason = null,
        public ?int $created = null,
        public ?int $updated = null,
        public ?int $paid = null,
        public ?array $fees = null,
        public ?array $metadata = null,
    ) {}
}
```

#### 1.2 List Transaction Refunds Endpoint
- **Endpoint**: `GET /v1/transactions/{transaction_uid}/refunds`
- **Request Class**: `GetTransactionRefundsRequest`
- **Response**: Paginated list of `RefundData`
- **Resource Method**: `TransactionsResource::refunds(string $transactionUid)->list(array $params = [])`

#### 1.3 Sub-Resource Implementation
```php
class RefundsResource extends BaseResource
{
    public function __construct(
        Connector $connector,
        protected string $transactionUid
    ) {
        parent::__construct($connector);
    }

    public function create(CreateRefundData|array $data): Response
    {
        return $this->connector->send(new CreateRefundRequest($this->transactionUid, $data));
    }

    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetTransactionRefundsRequest($this->transactionUid, $params));
    }
}
```

#### 1.4 Required Tests
- `tests/Feature/RefundsTest.php`
- `tests/Unit/Requests/RefundsTest.php`
- `tests/Unit/Data/RefundDtosTest.php`
- `tests/Unit/Resources/RefundsResourceTest.php`

### 2. Global Settlements API (Medium Priority) 
The SDK only implements merchant-specific settlements but is missing:

#### 2.1 List All Settlements Endpoint
- **Endpoint**: `GET /v1/settlements`
- **Request Class**: `GetSettlementsRequest`
- **Response DTO**: `PaginatedListResponse<SettlementData>`
- **Resource Method**: `SettlementsResource::list(array $params = [])`

**Query Parameters:**
- `filter[status]` - Filter by status (e.g., 'current', 'paid')
- `expand[]` - Expand related data (e.g., 'specifications')
- `order[]` - Ordering (e.g., '-period' for descending by period)

#### 2.2 Settlement Specification Rows Endpoint
- **Endpoint**: `GET /v1/settlements/{settlement_uid}/specifications/{specification_uid}/rows`
- **Request Class**: `GetSettlementSpecificationRowsRequest`
- **Response DTO**: `SettlementRowData[]`
- **Resource Method**: `SettlementsResource::specificationRows(string $settlementUid, string $specificationUid, array $params = [])`

**Response Structure:**
```php
class SettlementRowData extends BaseData
{
    public function __construct(
        public string $type, // 'transaction', 'refund', 'chargeback', 'mandate'
        public string $reference, // UID of related object
        public int $total_partner_fee,
        public int $amount, // gross amount
        public int $amount_payable, // net amount after fees
        public ?array $metadata = null,
    ) {}
}
```

#### 2.3 New Settlements Resource
```php
class SettlementsResource extends BaseResource
{
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetSettlementsRequest($params));
    }

    public function specificationRows(string $settlementUid, string $specificationUid, array $params = []): Response
    {
        return $this->connector->send(new GetSettlementSpecificationRowsRequest($settlementUid, $specificationUid, $params));
    }
}
```

#### 2.4 Required Tests
- `tests/Feature/SettlementsTest.php`
- `tests/Unit/Requests/SettlementsTest.php`
- `tests/Unit/Data/SettlementRowDtosTest.php`
- `tests/Unit/Resources/SettlementsResourceTest.php`

## Implementation Quality Assessment

### ✅ Strengths
1. **Proper SaloonPHP Structure**: Follows SaloonPHP best practices with connectors, resources, and requests
2. **Type-Safe DTOs**: Uses spatie/laravel-data for request/response DTOs
3. **Resource Organization**: Well-organized resource classes grouping related endpoints
4. **Error Handling**: Custom exception classes for different error types
5. **Comprehensive Testing**: Good test coverage with proper mocking
6. **Authentication**: Proper token-based authentication implementation
7. **Pagination**: Implements HasPagination interface correctly

### 🔄 Areas for Improvement

#### 1. Missing Refunds Implementation
**Current State**: No refunds functionality
**Required Action**: Implement complete refunds API

#### 2. Incomplete Settlements API
**Current State**: Only merchant settlements implemented  
**Required Action**: Add global settlements and specification rows endpoints

#### 3. Mandate DTO Consistency
**Current State**: `CreateMandateRequest` uses array instead of DTO
**Required Action**: Create `CreateMandateData` DTO for consistency

**Implementation Required:**
```php
class CreateMandateData extends BaseData
{
    public function __construct(
        public string $merchant_uid,
        public string $mandate_method, // 'emandate', 'payment', 'form', 'import'
        public string $mandate_type, // 'consumer', 'business'
        public string $mandate_repeat, // 'subscription'
        public int $mandate_amount, // in cents
        #[DataCollectionOf(ProductData::class)]
        public DataCollection|array $products,
        public int $total_price, // in cents
        public string $return_url,
        public string $notify_url,
        public ?string $issuer = null, // for 'emandate'
        public ?string $payment_method = null, // for 'payment'
        public ?string $bank_iban = null, // for 'import'
        public ?string $bank_bic = null, // for 'import'
        public ?string $bank_name = null, // for 'import'
        public ?array $metadata = null,
    ) {}
}

class MandateData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $status,
        public string $mandate_method,
        public string $mandate_type,
        public string $mandate_repeat,
        public int $amount,
        public ?string $redirect_url = null,
        public ?string $return_url = null,
        public ?string $notify_url = null,
        public ?int $created = null,
        public ?int $updated = null,
        public ?int $completed = null,
        public ?int $expired = null,
        public ?int $revoked = null,
        public ?array $customer = null,
        public ?array $order = null,
        public ?array $metadata = null,
        public ?array $statuses = null,
    ) {}
}
```

**Required Tests:**
- `tests/Unit/Data/MandateDtosTest.php`
- Update `tests/Feature/MandatesTest.php` for DTO usage
- Update `tests/Unit/Requests/MandatesTest.php`

#### 4. File Upload DTO
**Current State**: `CreateFileUploadRequest` uses array
**Required Action**: Create `CreateFileData` DTO for type safety

**Implementation Required:**
```php
class CreateFileData extends BaseData
{
    public function __construct(
        public string $purpose, // 'organization_structure', 'coc_extract', 'bank_account_bank_statement', etc.
        public string $merchant_uid,
        public string $object_uid,
        public ?array $metadata = null,
    ) {}
}

class FileData extends BaseData
{
    public function __construct(
        public string $uid,
        public string $purpose,
        public string $merchant_uid,
        public string $object_uid,
        public string $token,
        public string $url,
        public int $created,
        public int $updated,
        public int $expired,
        public ?array $metadata = null,
    ) {}
}
```

**Required Tests:**
- `tests/Unit/Data/FileDtosTest.php`
- Update `tests/Feature/FilesTest.php` for DTO usage
- Update `tests/Unit/Requests/FilesTest.php`

## Complete Testing Strategy

### Current Testing Status
- **Coverage**: 70.2% (excellent baseline)
- **Testing Framework**: Pest PHP with proper mocking
- **Mock Strategy**: Uses Saloon's MockClient for API simulation
- **Test Structure**: Feature tests + Unit tests + Integration tests

### Required Test Implementation Plan

#### 1. Refunds API Tests
```php
// tests/Feature/RefundsTest.php
class RefundsTest extends TestCase
{
    public function test_can_create_refund_for_transaction()
    public function test_can_list_refunds_for_transaction()
    public function test_refund_creation_validates_amount()
    public function test_refund_creation_handles_api_errors()
    public function test_refund_resource_methods_work_correctly()
}

// tests/Unit/Requests/RefundsTest.php
class RefundsRequestTest extends TestCase
{
    public function test_create_refund_request_structure()
    public function test_get_transaction_refunds_request_structure()
    public function test_requests_use_correct_endpoints()
    public function test_requests_handle_dto_conversion()
}

// tests/Unit/Data/RefundDtosTest.php
class RefundDtosTest extends TestCase
{
    public function test_create_refund_data_structure()
    public function test_refund_data_response_structure()
    public function test_dto_null_filtering_works()
    public function test_dto_array_conversion()
}
```

#### 2. Settlements API Tests
```php
// tests/Feature/SettlementsTest.php
class SettlementsTest extends TestCase
{
    public function test_can_list_all_settlements()
    public function test_can_get_settlement_specification_rows()
    public function test_settlements_support_filtering()
    public function test_settlements_support_expansion()
    public function test_settlements_support_ordering()
}

// tests/Unit/Data/SettlementRowDtosTest.php
class SettlementRowDtosTest extends TestCase
{
    public function test_settlement_row_data_structure()
    public function test_settlement_row_types_validation()
    public function test_financial_amount_handling()
}
```

#### 3. Enhanced DTO Tests
```php
// tests/Unit/Data/MandateDtosTest.php
class MandateDtosTest extends TestCase
{
    public function test_create_mandate_data_with_emandate_method()
    public function test_create_mandate_data_with_payment_method()
    public function test_create_mandate_data_with_import_method()
    public function test_mandate_data_response_mapping()
    public function test_mandate_product_collection_handling()
}

// tests/Unit/Data/FileDtosTest.php
class FileDtosTest extends TestCase
{
    public function test_create_file_data_structure()
    public function test_file_data_response_structure()
    public function test_file_purpose_validation()
    public function test_file_upload_token_handling()
}
```

#### 4. Integration Tests
```php
// tests/Integration/FullWorkflowTest.php
class FullWorkflowTest extends TestCase
{
    public function test_complete_transaction_and_refund_workflow()
    public function test_merchant_onboarding_to_settlement_workflow()
    public function test_mandate_creation_and_transaction_workflow()
    public function test_file_upload_workflow()
}
```

#### 5. Mock Response Fixtures
Create fixture files for all new endpoints:
- `tests/Fixtures/Refunds/create_refund_response.json`
- `tests/Fixtures/Refunds/list_refunds_response.json`
- `tests/Fixtures/Settlements/list_settlements_response.json`
- `tests/Fixtures/Settlements/specification_rows_response.json`
- `tests/Fixtures/Mandates/create_mandate_response.json`
- `tests/Fixtures/Files/create_file_upload_response.json`

### Test Coverage Goals
- **Target Coverage**: 85%+ (increase from current 70.2%)
- **Critical Path Coverage**: 100% for payment flows (transactions, refunds, mandates)
- **Error Handling Coverage**: 100% for all exception scenarios
- **DTO Coverage**: 100% for all data transformations

### Performance Testing
```php
// tests/Performance/ApiPerformanceTest.php
class ApiPerformanceTest extends TestCase
{
    public function test_concurrent_refund_creation_performance()
    public function test_large_settlement_data_processing()
    public function test_bulk_transaction_listing_performance()
    public function test_memory_usage_with_large_responses()
}
```

## Recommendations

### Immediate Actions (High Priority)
1. **Implement Refunds API** - Critical missing functionality
   - Add all request/response classes
   - Implement RefundsResource sub-resource
   - Create comprehensive test suite
   - Add fixture files for mocking

2. **Add missing DTOs** for mandates and file uploads
   - Convert array-based requests to DTO-based
   - Maintain backward compatibility
   - Add comprehensive DTO tests
   - Update existing tests

3. **Complete Settlements API** for full financial reporting
   - Add global settlements endpoint
   - Add specification rows endpoint  
   - Create proper response DTOs
   - Add financial calculation tests

### Future Enhancements (Medium Priority)  
1. **Enhanced Testing**
   - Add performance tests for high-volume scenarios
   - Add end-to-end integration tests
   - Add property-based testing for DTOs
   - Add contract testing for API compatibility

2. **Developer Experience**
   - Add pagination support to list endpoints where applicable
   - Implement response caching for frequently accessed data
   - Add batch operations for high-volume scenarios
   - Enhance error handling with more specific exception types

3. **Advanced Features**
   - Add webhook validation helpers
   - Implement retry logic for failed requests
   - Add request/response logging capabilities
   - Create debugging utilities for development

### Best Practices Compliance
The current implementation demonstrates excellent adherence to:
- SaloonPHP conventions and patterns
- PHP coding standards (PSR-4, strict typing)
- Proper separation of concerns
- Comprehensive testing methodology
- Type safety with spatie/laravel-data

## Implementation Priority Matrix

### Critical Priority (Must Implement)
| Feature | Business Impact | Technical Effort | Timeline |
|---------|----------------|------------------|----------|
| **Refunds API** | **Critical** - Required for payment processing | **Medium** - 3-4 classes + tests | **1-2 days** |
| Sub-resource integration | High - Clean API design | Low - Extend existing pattern | 0.5 days |
| Comprehensive refund tests | High - Production reliability | Medium - Full test suite | 1 day |

### High Priority (Should Implement)
| Feature | Business Impact | Technical Effort | Timeline |
|---------|----------------|------------------|----------|
| **Mandate DTOs** | Medium - Type safety improvement | **Low** - Convert existing arrays | **0.5 days** |
| **File Upload DTOs** | Medium - Consistency improvement | **Low** - Convert existing arrays | **0.5 days** |
| Enhanced DTO tests | Medium - Code quality | Low - Follow existing patterns | 0.5 days |

### Medium Priority (Nice to Have)
| Feature | Business Impact | Technical Effort | Timeline |
|---------|----------------|------------------|----------|
| **Global Settlements API** | Medium - Enhanced reporting | **Medium** - 2-3 new classes | **1-2 days** |
| Settlement specification rows | Low - Detailed financial data | Low - Additional endpoint | 0.5 days |
| Performance testing | Low - Production optimization | Medium - New test framework | 1-2 days |

### Total Implementation Estimate: **4-7 days**

## File Structure for New Implementation

```
src/
├── Data/
│   ├── Requests/
│   │   ├── Mandates/
│   │   │   └── CreateMandateData.php ✨ NEW
│   │   ├── Files/
│   │   │   └── CreateFileData.php ✨ NEW
│   │   ├── Transactions/
│   │   │   └── CreateRefundData.php ✨ NEW
│   │   └── Settlements/
│   │       └── GetSettlementsData.php ✨ NEW
│   └── Responses/
│       ├── Transactions/
│       │   └── RefundData.php ✨ NEW
│       ├── Mandates/
│       │   └── MandateData.php ✨ ENHANCED
│       ├── Files/
│       │   └── FileData.php ✨ ENHANCED
│       └── Settlements/
│           └── SettlementRowData.php ✨ NEW
├── Requests/
│   ├── Transactions/
│   │   ├── CreateRefundRequest.php ✨ NEW
│   │   └── GetTransactionRefundsRequest.php ✨ NEW
│   └── Settlements/
│       ├── GetSettlementsRequest.php ✨ NEW
│       └── GetSettlementSpecificationRowsRequest.php ✨ NEW
├── Resources/
│   ├── Transactions/
│   │   └── RefundsResource.php ✨ NEW
│   └── SettlementsResource.php ✨ NEW (global)
└── tests/
    ├── Feature/
    │   ├── RefundsTest.php ✨ NEW
    │   └── SettlementsTest.php ✨ NEW
    ├── Unit/
    │   ├── Data/
    │   │   ├── RefundDtosTest.php ✨ NEW
    │   │   ├── MandateDtosTest.php ✨ NEW
    │   │   ├── FileDtosTest.php ✨ NEW
    │   │   └── SettlementRowDtosTest.php ✨ NEW
    │   ├── Requests/
    │   │   ├── RefundsTest.php ✨ NEW
    │   │   └── SettlementsTest.php ✨ NEW
    │   └── Resources/
    │       ├── RefundsResourceTest.php ✨ NEW
    │       └── SettlementsResourceTest.php ✨ NEW
    └── Fixtures/
        ├── Refunds/ ✨ NEW
        └── Settlements/ ✨ NEW
```

## Implementation Checklist

### Phase 1: Critical Refunds API (1-2 days)
- [ ] Create `CreateRefundData` DTO class
- [ ] Create `RefundData` response DTO class  
- [ ] Create `CreateRefundRequest` class
- [ ] Create `GetTransactionRefundsRequest` class
- [ ] Create `RefundsResource` sub-resource class
- [ ] Update `TransactionsResource` with `refunds()` method
- [ ] Create comprehensive test suite
- [ ] Add fixture files for mocking
- [ ] Update documentation

### Phase 2: DTO Consistency (1 day)
- [ ] Create `CreateMandateData` DTO class
- [ ] Create enhanced `MandateData` response DTO
- [ ] Update `CreateMandateRequest` to use DTO
- [ ] Create `CreateFileData` DTO class  
- [ ] Create enhanced `FileData` response DTO
- [ ] Update `CreateFileUploadRequest` to use DTO
- [ ] Add DTO-specific tests
- [ ] Maintain backward compatibility

### Phase 3: Global Settlements (1-2 days)
- [ ] Create `SettlementsResource` for global endpoints
- [ ] Create `GetSettlementsRequest` class
- [ ] Create `GetSettlementSpecificationRowsRequest` class
- [ ] Create `SettlementRowData` DTO class
- [ ] Update connector with `settlements()` method
- [ ] Add comprehensive tests
- [ ] Add fixture files

### Phase 4: Enhanced Testing (1-2 days)
- [ ] Add integration workflow tests
- [ ] Add performance testing framework
- [ ] Increase test coverage to 85%+
- [ ] Add error handling tests
- [ ] Add contract testing setup

## Success Metrics

### Functional Completeness
- ✅ **100% API endpoint coverage** (vs current 85%)
- ✅ **100% DTO consistency** across all requests
- ✅ **Complete payment workflow support** (transactions + refunds)

### Code Quality
- ✅ **85%+ test coverage** (vs current 70.2%)
- ✅ **100% type safety** with Laravel Data DTOs
- ✅ **Zero breaking changes** to existing API

### Developer Experience  
- ✅ **Comprehensive documentation** for all endpoints
- ✅ **Complete fixture files** for easy testing
- ✅ **Performance benchmarks** for production usage

## Conclusion

The current SDK implementation covers approximately 85% of the documented API endpoints and follows excellent development practices. With the detailed implementation plan above, the main gaps can be addressed:

1. **Complete Refunds API** (critical for payment processing) - **1-2 days**
2. **DTO consistency** across all endpoints - **1 day**  
3. **Global Settlements API** (important for reporting) - **1-2 days**

**Total effort: 4-7 days to achieve 100% API coverage**

The SDK already demonstrates industry-leading code quality and architecture. These enhancements will complete the feature set while maintaining the same high standards, resulting in a production-ready, enterprise-grade PHP SDK for the Online Payment Platform.

---

*Comprehensive analysis and implementation plan completed on 2025-07-28*