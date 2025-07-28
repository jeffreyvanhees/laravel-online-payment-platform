# Online Payment Platform API Documentation

This document contains the complete API documentation for the Online Payment Platform, retrieved from Context7.

## API Endpoints Summary

Based on the documentation, the Online Payment Platform provides the following main API endpoints:

### 1. Merchants API
- **POST /v1/merchants** - Create business merchant
- **GET /v1/merchants/{merchant_uid}** - Get merchant details
- **GET /v1/merchants** - List merchants

### 2. Merchant Bank Accounts API
- **POST /v1/merchants/{merchant_uid}/bank_accounts** - Create bank account
- **GET /v1/merchants/{merchant_uid}/bank_accounts** - List merchant bank accounts

### 3. Transactions API
- **POST /v1/transactions** - Create transaction
- **GET /v1/transactions/{transaction_uid}** - Get transaction details
- **GET /v1/transactions** - List transactions

### 4. Refunds API
- **POST /v1/transactions/{transaction_uid}/refunds** - Create refund
- **GET /v1/transactions/{transaction_uid}/refunds** - List transaction refunds

### 5. Mandates API
- **POST /v1/mandates** - Create mandate
- **GET /v1/mandates/{mandate_uid}** - Get mandate details

### 6. Settlements API
- **GET /v1/settlements** - List settlements
- **GET /v1/merchants/{merchant_uid}/settlements** - Get merchant settlements
- **GET /v1/settlements/{settlement_uid}/specifications/{specification_uid}/rows** - Get settlement specification rows

### 7. Files API
- **POST https://files-sandbox.onlinebetaalplatform.nl/v1/uploads** - Create file upload link
- **POST https://files-sandbox.onlinebetaalplatform.nl/v1/uploads/{file_uid}** - Upload file

### 8. Partners API (Configuration)
- **GET /v1/partners/configuration** - Get partner configuration
- **POST /v1/partners/configuration** - Update partner configuration

## Key Concepts

### Authentication
The API uses API key authentication. All requests should include authentication headers.

### Base URLs
- **Sandbox**: `https://api-sandbox.onlinebetaalplatform.nl`
- **Production**: `https://api.onlinebetaalplatform.nl`
- **Files Sandbox**: `https://files-sandbox.onlinebetaalplatform.nl`
- **Files Production**: `https://files.onlinebetaalplatform.nl`

### Common Parameters
- `merchant_uid`: Unique identifier for merchants
- `transaction_uid`: Unique identifier for transactions
- `mandate_uid`: Unique identifier for mandates
- `return_url`: URL to redirect users after completion
- `notify_url`: Webhook URL for status notifications

### Status Flows
- **Merchants**: `pending` → `live` (with compliance verification)
- **Transactions**: `created` → `planned/reserved` → `completed`
- **Mandates**: `created` → `authorized` → `active`
- **Bank Accounts**: `new` → `pending` → `verified`

### Compliance Requirements
Merchants must complete various compliance requirements before receiving payouts:
- Bank account verification
- Contact verification (phone, email)
- Identity verification
- Source of funds verification
- Organization structure verification

## Complete API Reference

# Online Payment Platform API Documentation

## Introduction

This document describes how to communicate with the Online Payment Platform (OPP) REST API. You can create merchants, bank accounts, transactions, mandates and more using this API.

**Base URLs:**
- Sandbox: `https://api-sandbox.onlinebetaalplatform.nl`
- Production: `https://api.onlinebetaalplatform.nl`

## Authentication

All API requests require Bearer Token authentication:

```bash
Authorization: Bearer {{api_key}}
```

## Environments

### Sandbox Environment
The OPP Sandbox is a virtual testing environment that mimics the live OPP production environment.

**IP Whitelist Required:**
- 18.193.11.50
- 18.159.20.179

### Production Environment
**IP Whitelist Required:**
- 18.197.215.227
- 3.120.97.52
- 3.121.39.192

## Response Codes

| Code | Message | Description |
|------|---------|-------------|
| 200 | OK | Success |
| 400 | Bad Request | Missing parameter(s) |
| 401 | Unauthorized | Invalid or revoked API key |
| 404 | Not Found | Resource doesn't exist |
| 409 | Conflict | Conflict due to concurrent request |
| 410 | Gone | Resource doesn't exist anymore |
| 50X | Server Errors | Temporary problem on our side |

## API Status Check

Check if the API is operational:

```bash
GET /status
```

Response:
```json
{
  "status": "online",
  "date": 1611321273
}
```

## Pagination

When retrieving lists, use pagination parameters:

| Parameter | Description |
|-----------|-------------|
| `page` | The current page number |
| `perpage` | Items per page (1-100) |

Example: `?page=2&perpage=10`

## Filtering

### Basic Filter
```
?filter[FILTER]=VALUE
```

### Extended Filter
```
?filter[KEY][name]=FILTER
&filter[KEY][operand]=OPERATOR
&filter[KEY][value]=VALUE
```

**Supported Operators:**
- `lt`, `lte`, `gt`, `gte` - Comparison operators
- `in`, `notin` - Set membership
- `null`, `notnull` - Null checks
- `eq`, `notequals` - Equality
- `between` - Range queries

## Ordering

Order results:
- Ascending: `?order[]=VALUE`
- Descending: `?order[]=-VALUE`

## Expanding Objects

Include related objects in responses:
```
?expand[]=object_name
```

## Metadata

Add custom key-value data to objects:
```json
{
  "metadata": {
    "external_id": "12345",
    "custom_field": "value"
  }
}
```

## Currencies and Minor Units

Amounts must be submitted in minor units (cents):
- EUR 1.00 = 100 (minor units)
- GBP 1.00 = 100 (minor units)

**Supported Currencies:**
- EUR (Euro) - 2 decimals
- GBP (British Pound) - 2 decimals

## Merchants API

### Create Merchant

**Endpoint:** `POST /v1/merchants`

**Required Fields:**
- `country` - Country code
- `emailaddress` - Email address
- `notify_url` - Webhook URL

**For Business Merchants:**
- `type` - Must be "business"
- `coc_nr` - Chamber of Commerce number

```json
{
  "country": "nld",
  "emailaddress": "email@domain.com",
  "phone": "0612345678",
  "notify_url": "https://platform.com/notify",
  "return_url": "https://platform.com/return"
}
```

### Merchant Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Merchant unique identifier |
| `object` | string | Value: "merchant" |
| `status` | string | new, pending, live, terminated, suspended, blocked, deleted |
| `compliance` | object | Compliance requirements and status |
| `type` | string | consumer or business |
| `name` | string | Merchant name |
| `phone` | string | Phone number |
| `country` | string | Country code |
| `addresses` | array | Address objects |
| `contacts` | array | Contact objects |
| `profiles` | array | Profile objects |
| `payment_methods` | array | Available payment methods |

### Retrieve Merchants

```bash
GET /v1/merchants
GET /v1/merchants/{merchant_uid}
```

**Available Filters:**
- status
- type
- legal_name
- compliance_status
- compliance_level
- emailaddress
- created

### Update Merchant

```bash
POST /v1/merchants/{merchant_uid}
```

**Updatable Fields:**
- status
- emailaddress
- is_pep
- notify_url
- return_url

## Bank Accounts API

### Create Bank Account

**Endpoint:** `POST /v1/merchants/{merchant_uid}/bank_accounts`

```json
{
  "return_url": "https://platform.com/return",
  "notify_url": "https://platform.com/notify",
  "is_default": true
}
```

### Bank Account Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Bank account unique identifier |
| `status` | string | new, pending, approved, disapproved |
| `account` | object | Account details (IBAN, name) |
| `bank` | object | Bank details (BIC) |
| `verification_url` | string | URL for verification |
| `disapprovals` | array | Disapproval reasons if rejected |

## Contacts API

### Create Contact

**Endpoint:** `POST /v1/merchants/{merchant_uid}/contacts`

```json
{
  "type": "representative",
  "title": "mr",
  "gender": "m",
  "name": {
    "initials": "J",
    "first": "John",
    "last": "Doe"
  },
  "birthdate": "1980-01-01",
  "emailaddresses": [
    {"emailaddress": "john@example.com"}
  ],
  "phonenumbers": [
    {"phonenumber": "0123456789"}
  ]
}
```

### Contact Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Contact unique identifier |
| `status` | string | unverified, pending, verified |
| `type` | string | representative, technical, financial |
| `verification_url` | string | URL for identity verification |
| `disapprovals` | array | Disapproval reasons if rejected |

## Profiles API

### Create Profile

**Endpoint:** `POST /v1/merchants/{merchant_uid}/profiles`

```json
{
  "name": "Profile Name",
  "url": "https://www.example.com",
  "po_number": "Payout description",
  "bank_account_uid": "{{bank_account_uid}}"
}
```

### Profile Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Profile unique identifier |
| `status` | string | new, pending, awaiting, live, terminated, suspended |
| `name` | string | Profile name |
| `url` | string | Associated URL |
| `bank_account` | array | Linked bank account |
| `virtual_ibans` | array | Virtual IBANs |

## Transactions API

### Create Transaction

**Endpoint:** `POST /v1/transactions`

**Required Fields:**
- `merchant_uid` - Merchant identifier
- `products` - Array of product objects
- `total_price` - Total amount in cents
- `return_url` - Return URL
- `notify_url` - Notification URL

```json
{
  "merchant_uid": "{{merchant_uid}}",
  "products": [
    {
      "name": "Product Name",
      "price": 2500,
      "quantity": 1
    }
  ],
  "total_price": 2500,
  "payment_method": "ideal",
  "return_url": "https://platform.com/return",
  "notify_url": "https://platform.com/notify"
}
```

### Transaction Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Transaction unique identifier |
| `status` | string | created, pending, completed, failed, etc. |
| `payment_method` | string | Payment method used |
| `amount` | integer | Amount in cents |
| `redirect_url` | string | URL to redirect customer |
| `order` | object | Order details |
| `escrow` | object | Escrow information |
| `fees` | object | Fee breakdown |
| `refunds` | array | Refund objects |

### Payment Methods

| Method | Description | Expiration |
|--------|-------------|------------|
| `ideal` | iDEAL payments | 15 minutes |
| `bcmc` | Bancontact | 15 minutes |
| `sepa` | SEPA bank transfer | 7 days |
| `pi-single` | Payment Initiation | Varies |
| `paypal-ppcp` | PayPal | 15 minutes |
| `creditcard` | Credit card | 15 minutes |
| `apple-pay` | Apple Pay | 15 minutes |
| `google-pay` | Google Pay | 15 minutes |
| `mybank` | MyBank | 15 minutes |
| `paysafecard` | PaySafeCard | 15 minutes |

### Transaction Status Flow

1. `created` - Transaction is created
2. `pending` - User opened redirect URL
3. `planned` - Money reserved but not claimed
4. `completed` - Successfully completed
5. `reserved` - Completed but in escrow
6. `cancelled` - Manually cancelled
7. `failed` - Payment failed
8. `expired` - Expiration time passed
9. `refunded` - Refund initiated
10. `chargeback` - Full refund from escrow

## Multi Transactions API

### Create Multi Transaction

**Endpoint:** `POST /v1/multi_transactions`

Combine multiple transactions into a single payment:

```json
{
  "payment_method": "ideal",
  "total_price": 5000,
  "currency": "EUR",
  "transactions": [
    {
      "merchant_uid": "{{merchant_uid}}",
      "total_price": 2500,
      "products": [
        {
          "name": "Product A",
          "price": 2500,
          "quantity": 1
        }
      ]
    },
    {
      "merchant_uid": "{{merchant_uid}}",
      "total_price": 2500,
      "products": [
        {
          "name": "Product B",
          "price": 2500,
          "quantity": 1
        }
      ]
    }
  ],
  "return_url": "https://platform.com/return",
  "notify_url": "https://platform.com/notify"
}
```

## Refunds API

### Create Refund

**Endpoint:** `POST /v1/transactions/{transaction_uid}/refunds`

```json
{
  "amount": 1000,
  "currency": "EUR",
  "message": "Customer requested refund",
  "payout_description": "Refund for order #123"
}
```

### Refund Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Refund unique identifier |
| `amount` | integer | Refund amount in cents |
| `status` | string | created, pending, completed |
| `message` | string | Message to customer |

## Mandates API

### Create Mandate

**Endpoint:** `POST /v1/mandates`

```json
{
  "merchant_uid": "{{merchant_uid}}",
  "mandate_method": "payment",
  "mandate_type": "consumer",
  "mandate_repeat": "subscription",
  "mandate_amount": 100,
  "products": [
    {
      "name": "Service Subscription",
      "price": 100,
      "quantity": 1
    }
  ],
  "return_url": "https://platform.com/return",
  "notify_url": "https://platform.com/notify"
}
```

### Mandate Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Mandate unique identifier |
| `token` | string | Token for creating transactions |
| `status` | string | created, pending, completed, revoked |
| `mandate_method` | string | form, payment, emandate, import |
| `mandate_type` | string | consumer, business |
| `mandate_repeat` | string | once, subscription, continued |

### Create Mandate Transaction

**Endpoint:** `POST /v1/mandates/{mandate_uid}/transactions`

```json
{
  "merchant_uid": "{{merchant_uid}}",
  "token": "{{mandate_token}}",
  "reference": "ORDER-123",
  "total_price": 2500,
  "notify_url": "https://platform.com/notify"
}
```

## Withdrawals API

### Create Withdrawal

**Endpoint:** `POST /v1/merchants/{merchant_uid}/withdrawals`

```json
{
  "amount": 10000,
  "currency": "EUR",
  "description": "Monthly withdrawal",
  "notify_url": "https://platform.com/notify"
}
```

### Withdrawal Object

| Field | Type | Description |
|-------|------|-------------|
| `uid` | string | Withdrawal unique identifier |
| `status` | string | created, pending, completed, failed |
| `amount` | integer | Amount in cents |
| `receiver_details` | object | Bank account details |

## Payment References API

### Create Payment Reference

**Endpoint:** `POST /v1/payment_references`

```json
{
  "merchant_uid": "{{merchant_uid}}",
  "code": "REF001",
  "currency": "EUR",
  "notify_url": "https://platform.com/notify"
}
```

## Charges API

### Create Charge (Internal Transfer)

**Endpoint:** `POST /v1/charges`

```json
{
  "type": "balance",
  "amount": 1000,
  "currency": "EUR",
  "description": "Internal transfer",
  "from_owner_uid": "{{from_merchant_uid}}",
  "to_owner_uid": "{{to_merchant_uid}}"
}
```

## Virtual IBANs API

### Create Virtual IBAN

**Endpoint:** `POST /v1/merchants/{merchant_uid}/profiles/{profile_uid}/virtual_ibans`

```json
{
  "account_holder": "Company Name",
  "notify_url": "https://platform.com/notify",
  "iban_country": "NL",
  "currency": "EUR"
}
```

## Disputes API

### Create Dispute

**Endpoint:** `POST /v1/disputes`

```json
{
  "reference": "{{transaction_uid}}",
  "reason": "complaint",
  "message": "Product not received",
  "contact": {
    "name_first": "John",
    "name_last": "Doe",
    "emailaddress": "john@example.com"
  },
  "notify_url": "https://platform.com/notify"
}
```

## Files API

**Base URL:**
- Sandbox: `https://files-sandbox.onlinebetaalplatform.nl/v1`
- Production: `https://files.onlinebetaalplatform.nl/v1`

### Create File Upload Link

**Endpoint:** `POST /v1/uploads`

```json
{
  "purpose": "bank_account_bank_statement",
  "merchant_uid": "{{merchant_uid}}",
  "object_uid": "{{bank_account_uid}}"
}
```

## Webhooks/Notifications

OPP sends webhook notifications for status changes. Configure webhook URLs in your requests.

### Notification Structure

```json
{
  "uid": "{{notification_uid}}",
  "type": "transaction.status.changed",
  "created": 1554199810,
  "object_uid": "{{transaction_uid}}",
  "object_type": "transaction",
  "object_url": "https://api-sandbox.onlinebetaalplatform.nl/v1/transactions/{{transaction_uid}}",
  "verification_hash": "{{hash}}"
}
```

### Common Notification Types

- `transaction.status.changed`
- `merchant.status.changed`
- `mandate.status.changed`
- `bank_account.status.changed`
- `contact.status.changed`
- `withdrawal.status.changed`
- `dispute.status.changed`

## Error Handling

All errors return JSON with error details:

```json
{
  "error": {
    "code": 400,
    "message": "Missing required merchant_uid"
  }
}
```

## Rate Limiting

- OPP requires sequential requests to prevent overloading
- HTTP 409 Conflict returned when rate limited
- Retry requests after receiving 409 responses

## Security Considerations

- Keep API keys secure
- Use HTTPS for all requests
- Validate webhook signatures
- Whitelist OPP IP addresses
- API is system-to-system only (not for frontend use)

## Sandbox Testing

### Transaction Status Simulation

Use specific amounts to trigger different statuses:

| Amount | Status |
|--------|--------|
| 101 | created |
| 201 | pending |
| 301 | expired |
| 401 | cancelled |
| 501 | completed |
| 701 | failed |
| 801 | refunded |
| 901 | reserved (escrow) |

### Credit Card Testing

Test card numbers:
- `4330 2649 3634 4675` - Success
- `4111 1133 3333 3333` - Failure

### Bancontact Testing

- `5017670000000000` - Success
- `5017670000001404` - Failure

---

*This documentation was generated from the Online Payment Platform Context7 guide on 2025-07-28*