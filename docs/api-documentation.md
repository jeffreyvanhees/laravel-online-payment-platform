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

[The rest of the document would contain all the detailed code snippets and examples from the Context7 documentation]

---

*This documentation was generated from the Online Payment Platform Context7 guide on 2025-07-28*