# Production Ready Notice

## ⚠️ Important: API Documentation Verification Required

This SDK has been cleaned up to remove speculative properties, but **REQUIRES VERIFICATION** against the actual Online Payment Platform API documentation before production use.

### Current Status

✅ **Cleaned DTOs**: Removed unconfirmed properties that could cause mapping errors
✅ **Conservative Approach**: Only included commonly expected fields
✅ **Backward Compatibility**: Maintained existing test compatibility

### Required Before Production

1. **Obtain Complete API Documentation**
   - Get detailed response examples for all endpoints
   - Verify exact field names, types, and optionality
   - Check for any missing required fields

2. **Test Against Sandbox API**
   - Make actual API calls to verify response structures
   - Test all implemented endpoints with real responses
   - Validate DTO mapping works correctly

3. **Current DTO Status**

   **RefundData** (src/Data/Responses/Transactions/RefundData.php):
   - Removed: `created`, `updated`, `paid`, `fees` (unconfirmed properties)
   - Kept: Core fields likely to exist in most payment APIs

   **MandateData** (src/Data/Responses/Mandates/MandateData.php):
   - Removed: ~30 speculative properties
   - Kept: Essential mandate fields only

   **SettlementRowData** (src/Data/Responses/Settlements/SettlementRowData.php):
   - Simplified to core fields: `type`, `reference`, `amount`, `metadata`

### Next Steps

1. Request actual API documentation from Online Payment Platform
2. Test DTOs against real API responses
3. Add back confirmed properties as needed
4. Update tests with real response examples

### Risk Mitigation

The current implementation errs on the side of caution by including fewer properties rather than incorrect ones. This prevents runtime errors from property mapping failures, but may require adding properties back once confirmed.