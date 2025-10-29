# SMS Registration Testing Guide

## Prerequisites

### 1. **MNotify Configuration**
Ensure your `.env` file has the following configured:

```env
MNOTIFY_API_KEY=your_api_key_here
MNOTIFY_SENDER_ID=your_sender_id_here
```

**To get these credentials:**
1. Visit: https://sms.pastechsolutions.com
2. Sign up or login to your MNotify account
3. Navigate to API Settings
4. Copy your API Key and Sender ID

### 2. **SMS Credits**
- Ensure you have sufficient SMS credits in your MNotify account
- Each SMS costs approximately ₵0.05 - ₵0.10
- Check balance at: https://sms.pastechsolutions.com/dashboard

## Testing Methods

### Method 1: Command Line Test (Recommended)

This method tests the SMS service without creating a real member account.

**Command:**
```bash
php artisan sms:test-registration {phone_number} --name={first_name}
```

**Examples:**
```bash
# Test with default name "John"
php artisan sms:test-registration 0241234567

# Test with custom name
php artisan sms:test-registration 0241234567 --name=Mary

# Test with international format
php artisan sms:test-registration +233241234567 --name=David
```

**Expected Output:**
```
Testing Registration SMS...

📱 SMS Configuration:
API Key: ✓ Configured
Sender ID: BeulahFam
Phone: 0241234567

📤 Sending SMS...
Message: Welcome to Beulah Family, John! Your registration is pending approval...
Length: 180 characters

✅ SMS sent successfully!

Response Details:
Message ID: MSG123456789
Cost: ₵0.08

🎉 Registration SMS test completed successfully!
```

### Method 2: Full Registration Test

This method tests the complete registration flow including database creation.

**Steps:**

1. **Clear any test data:**
```bash
# Optional: Delete test members
php artisan tinker
>>> App\Models\Member::where('email', 'test@example.com')->forceDelete();
>>> exit
```

2. **Navigate to registration page:**
```
http://127.0.0.1:8000/member/register
```

3. **Fill out the form with test data:**
- First Name: `Test`
- Last Name: `User`
- Email: `test@example.com`
- Phone: `YOUR_PHONE_NUMBER` (use your actual phone)
- Date of Birth: Any valid date
- Gender: Select any
- Chapter: Select any
- Membership Type: Select any

4. **Submit the form**

5. **Check for SMS on your phone**

6. **Check application logs:**
```bash
tail -f storage/logs/laravel.log
```

Look for entries like:
```
[2025-10-29 04:38:00] local.INFO: Registration SMS sent successfully {"member_id":123,"phone":"0241234567"}
```

### Method 3: Test Approval SMS

After registration, test the approval SMS:

1. **Login as admin:**
```
http://127.0.0.1:8000/login
```

2. **Navigate to pending approvals:**
```
http://127.0.0.1:8000/members/pending-approvals
```

3. **Click "Approve" on the test member**

4. **Check for approval SMS on phone**

5. **Check logs:**
```bash
tail -f storage/logs/laravel.log
```

Look for:
```
[2025-10-29 04:38:00] local.INFO: Approval SMS sent successfully {"member_id":123,"phone":"0241234567"}
```

## Troubleshooting

### Issue 1: "API Key is not configured"

**Solution:**
```bash
# Check if .env has the keys
cat .env | grep MNOTIFY

# If missing, add them:
echo "MNOTIFY_API_KEY=your_key_here" >> .env
echo "MNOTIFY_SENDER_ID=your_sender_id" >> .env

# Clear config cache
php artisan config:clear
```

### Issue 2: "SMS sending failed"

**Common Causes:**
1. **Insufficient credits** - Top up your MNotify account
2. **Invalid phone format** - Use format: `0241234567` or `+233241234567`
3. **Invalid API key** - Verify credentials in MNotify dashboard
4. **Sender ID not approved** - Ensure sender ID is approved by MNotify

**Check API Response:**
```bash
php artisan sms:test-registration 0241234567
```

The command will show the exact error from the API.

### Issue 3: "No SMS received"

**Checklist:**
- ✓ Check phone number is correct
- ✓ Check phone has network coverage
- ✓ Check SMS credits in MNotify account
- ✓ Check logs for "SMS sent successfully"
- ✓ Wait 1-2 minutes (delivery can be delayed)
- ✓ Check spam/blocked messages on phone

### Issue 4: "Member has receive_sms disabled"

**Solution:**
```bash
php artisan tinker
>>> $member = App\Models\Member::where('email', 'test@example.com')->first();
>>> $member->receive_sms = true;
>>> $member->save();
>>> exit
```

## Verification Checklist

After testing, verify:

- [ ] SMS received on phone
- [ ] Message content is correct
- [ ] Login credentials are included
- [ ] No errors in logs
- [ ] SMS cost deducted from MNotify account
- [ ] Member can login with credentials from SMS

## Phone Number Formats

The system accepts multiple formats:

| Format | Example | Status |
|--------|---------|--------|
| Local | `0241234567` | ✓ Supported |
| International | `+233241234567` | ✓ Supported |
| With spaces | `024 123 4567` | ✓ Supported |
| With dashes | `024-123-4567` | ✓ Supported |

The `MNotifyService` automatically formats the number correctly.

## Cost Estimation

| Action | SMS Count | Estimated Cost |
|--------|-----------|----------------|
| Registration | 1 SMS | ₵0.05 - ₵0.10 |
| Approval | 1 SMS | ₵0.05 - ₵0.10 |
| **Total per member** | **2 SMS** | **₵0.10 - ₵0.20** |

**Example:**
- 100 new members = 200 SMS = ₵10 - ₵20
- 500 new members = 1000 SMS = ₵50 - ₵100

## Testing Best Practices

1. **Use test phone numbers first** - Don't spam real members
2. **Check logs after each test** - Verify success/failure
3. **Monitor SMS credits** - Ensure sufficient balance
4. **Test different scenarios:**
   - Valid phone number
   - Invalid phone number
   - Member with `receive_sms = false`
   - Member without phone number
   - Long names (check character limit)

## Sample Test Data

Use these for testing:

```php
// Test Member 1
First Name: John
Last Name: Doe
Email: john.test@example.com
Phone: 0241234567

// Test Member 2
First Name: Mary
Last Name: Smith
Email: mary.test@example.com
Phone: 0501234567

// Test Member 3
First Name: David
Last Name: Johnson
Email: david.test@example.com
Phone: +233241234567
```

## Logs Location

All SMS activity is logged in:
```
storage/logs/laravel.log
```

**View logs:**
```bash
# View last 50 lines
tail -n 50 storage/logs/laravel.log

# Follow logs in real-time
tail -f storage/logs/laravel.log

# Search for SMS logs
grep "SMS" storage/logs/laravel.log

# Search for specific phone number
grep "0241234567" storage/logs/laravel.log
```

## Quick Test Script

Save this as `test-sms.sh`:

```bash
#!/bin/bash

echo "🧪 Testing SMS Registration..."
echo ""

# Test with your phone number
PHONE="0241234567"  # Change this to your phone

echo "Testing with phone: $PHONE"
php artisan sms:test-registration $PHONE --name=TestUser

echo ""
echo "Check your phone for SMS!"
echo "Check logs: tail -f storage/logs/laravel.log"
```

Make it executable:
```bash
chmod +x test-sms.sh
./test-sms.sh
```

## Support

If you encounter issues:

1. **Check MNotify Status:** https://sms.pastechsolutions.com/status
2. **Review Documentation:** https://sms.pastechsolutions.com/docs
3. **Contact Support:** support@pastechsolutions.com
4. **Check Application Logs:** `storage/logs/laravel.log`

## Success Indicators

✅ **SMS sent successfully if:**
- Command shows "✅ SMS sent successfully!"
- Log shows "Registration SMS sent successfully"
- SMS received on phone within 1-2 minutes
- MNotify dashboard shows message sent
- SMS credits deducted from account

❌ **SMS failed if:**
- Command shows "❌ SMS sending failed!"
- Log shows "Failed to send registration SMS"
- No SMS received after 5 minutes
- Error message in logs
- No credit deduction from account
