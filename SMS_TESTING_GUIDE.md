# SMS System Testing Guide

## Overview
This guide provides step-by-step instructions to test all SMS system features including credit management, template creation, and birthday automation.

## Prerequisites

### 1. Environment Setup
Ensure these environment variables are configured in your `.env` file:
```env
# Paystack Configuration (Test Mode)
PAYSTACK_PUBLIC_KEY=pk_test_xxx
PAYSTACK_SECRET_KEY=sk_test_xxx
PAYSTACK_MERCHANT_EMAIL=your@email.com

# MNotify SMS Gateway
MNOTIFY_API_KEY=your_mnotify_api_key
MNOTIFY_SENDER_ID=your_sender_id
```

### 2. Database Setup
Run the following commands to ensure all tables and data are ready:
```bash
php artisan migrate
php artisan db:seed --class=SmsPricingSeeder
php artisan db:seed --class=SmsTemplateSeeder
php artisan db:seed --class=SmsCreditSeeder
```

## Testing Modules

### Module 1: SMS Templates Management

#### Access the SMS Templates
1. Login as admin user
2. Navigate to **SMS Templates** in the sidebar menu
3. You should see the SMS Templates dashboard

#### Test Template Creation
1. Click **"Create Template"** button
2. Fill in the form:
   - **Name**: "Test Welcome Message"
   - **Category**: "Welcome"
   - **Description**: "Test template for new members"
   - **Message**: "Welcome {{first_name}} to {{church_name}}! We're excited to have you."
3. Use the quick insert buttons to add variables
4. Check the live preview updates as you type
5. Click **"Create Template"**
6. Verify the template appears in the listing

#### Test Template Editing
1. Click **"Edit"** on any template
2. Modify the message content
3. Add/remove variables
4. Check the preview updates
5. Save changes
6. Verify changes are reflected

#### Test Template Actions
1. **View Template**: Click on a template name to see details
2. **Toggle Status**: Use the activate/deactivate button
3. **Duplicate**: Create a copy of an existing template
4. **Delete**: Remove a test template

### Module 2: SMS Credits Management

#### Access SMS Credits Dashboard
1. Navigate to **SMS Credits** in the sidebar menu
2. You should see:
   - Current credit balance (500 from seeder)
   - Available pricing packages
   - Recent transactions

#### Test Credit Purchase Flow
1. Click **"Buy Credits"** or **"Purchase"**
2. Select a pricing package (e.g., "Basic Pack")
3. Choose a payment method:
   - Mobile Money
   - Bank Transfer
   - Card Payment
4. Click **"Proceed to Payment"**
5. **Note**: In test mode, use Paystack test cards:
   - Success: `4084084084084081`
   - Decline: `4084084084084081` (with wrong CVV)

#### Test Transaction History
1. Navigate to **SMS Credits > Transactions**
2. Test filtering by:
   - Transaction type (Purchase, Usage, etc.)
   - Status (Completed, Pending, Failed)
   - Date range
3. Test export functionality (CSV/Excel)

### Module 3: Birthday SMS Automation

#### Test Birthday SMS Command
1. **Create Test Member with Today's Birthday**:
```bash
php artisan tinker
```
```php
use App\Models\Member;
use Carbon\Carbon;

Member::create([
    'first_name' => 'John',
    'last_name' => 'TestBirthday',
    'email' => 'john.test@example.com',
    'phone' => '+233241234567',
    'date_of_birth' => Carbon::today()->format('Y-m-d'),
    'gender' => 'male',
    'chapter' => 'ACCRA',
    'membership_status' => 'active',
    'membership_type' => 'member',
    'is_active' => true
]);
```

2. **Test Dry Run Mode**:
```bash
php artisan sms:send-birthday --dry-run
```
Expected output:
- Shows members with birthdays today
- Displays the birthday message that would be sent
- Shows available SMS credits
- No actual SMS sent

3. **Test Actual Sending** (Optional - uses real SMS credits):
```bash
php artisan sms:send-birthday
```

#### Test Birthday Template
1. Go to **SMS Templates**
2. Find the "Birthday Wishes" template
3. Edit the message to customize birthday greetings
4. Test with dry-run command to see changes

### Module 4: Integration Testing

#### Test Complete SMS Workflow
1. **Purchase SMS Credits**:
   - Buy credits through the purchase flow
   - Verify transaction is recorded
   - Check credit balance updates

2. **Create Custom Template**:
   - Create a birthday template with custom message
   - Include variables like {{first_name}}, {{age}}, {{church_name}}
   - Activate the template

3. **Test Birthday Automation**:
   - Create member with today's birthday
   - Run birthday SMS command
   - Verify SMS is sent and credits deducted

#### Test Error Scenarios
1. **Insufficient Credits**:
   - Set admin credits to 0
   - Run birthday SMS command
   - Verify error message about insufficient credits

2. **Invalid Phone Numbers**:
   - Create member with invalid phone
   - Run birthday SMS command
   - Check error logging

3. **No Birthday Template**:
   - Deactivate all birthday templates
   - Run birthday SMS command
   - Verify default template creation

## User Interface Testing

### Navigation Testing
1. **Menu Access**: Verify all SMS menu items are accessible
2. **Responsive Design**: Test on mobile and desktop
3. **Active States**: Check menu highlighting works correctly

### Form Validation Testing
1. **Template Forms**:
   - Try submitting empty required fields
   - Test character limits
   - Verify validation messages

2. **Purchase Forms**:
   - Test without selecting package
   - Test without payment method
   - Verify error handling

### Interactive Features Testing
1. **Live Preview**: Template message preview updates
2. **Variable Insertion**: Quick insert buttons work
3. **Status Toggles**: Template activation/deactivation
4. **Filtering**: Search and filter functionality

## Performance Testing

### Load Testing
1. **Create Multiple Templates**: Test with 50+ templates
2. **Large Transaction History**: Test pagination and filtering
3. **Bulk Member Processing**: Test birthday command with many members

### Response Time Testing
1. **Page Load Times**: All SMS pages should load under 2 seconds
2. **AJAX Requests**: Template previews and status changes
3. **Payment Processing**: Paystack integration response times

## Security Testing

### Authentication Testing
1. **Access Control**: Non-admin users cannot access admin features
2. **Route Protection**: Direct URL access is properly restricted
3. **CSRF Protection**: Forms include CSRF tokens

### Data Validation Testing
1. **SQL Injection**: Test form inputs with malicious data
2. **XSS Prevention**: Test template content with scripts
3. **Input Sanitization**: Verify all user inputs are cleaned

## Cron Job Testing

### Manual Cron Setup
1. **Test Command Execution**:
```bash
# Test the exact cron command
/usr/local/bin/php /path/to/beulahfamily/artisan sms:send-birthday
```

2. **Verify Logging**:
   - Check `storage/logs/laravel.log` for birthday SMS logs
   - Verify success and error messages are recorded

3. **Test Scheduling**:
   - Set up cron job for testing (every minute for quick testing)
   - Monitor execution and logs
   - Reset to daily 6 AM schedule

## Troubleshooting Common Issues

### SMS Credits Issues
- **Credits not updating**: Check transaction logs and Paystack webhooks
- **Payment failures**: Verify Paystack credentials and test mode settings
- **Balance discrepancies**: Check transaction history for all credit movements

### Template Issues
- **Variables not replacing**: Check template syntax and variable names
- **Preview not updating**: Verify JavaScript is enabled and no console errors
- **Template not saving**: Check validation errors and required fields

### Birthday SMS Issues
- **No members found**: Verify member birth dates and active status
- **SMS not sending**: Check MNotify credentials and phone number format
- **Credit deduction errors**: Verify admin user has sufficient credits

### General Issues
- **Menu not showing**: Clear browser cache and check user permissions
- **Pages not loading**: Check routes are properly registered
- **Database errors**: Verify migrations are run and tables exist

## Success Criteria

### ✅ SMS Templates
- [ ] Can create, edit, and delete templates
- [ ] Variable substitution works correctly
- [ ] Live preview functions properly
- [ ] Status management works
- [ ] Template duplication works

### ✅ SMS Credits
- [ ] Credit purchase flow completes successfully
- [ ] Paystack integration processes payments
- [ ] Transaction history displays correctly
- [ ] Balance tracking is accurate
- [ ] Export functionality works

### ✅ Birthday Automation
- [ ] Command finds members with birthdays
- [ ] SMS messages are personalized correctly
- [ ] Credits are deducted properly
- [ ] Logging captures all activities
- [ ] Dry-run mode works for testing

### ✅ User Interface
- [ ] All pages load without errors
- [ ] Navigation works correctly
- [ ] Forms validate properly
- [ ] Responsive design functions on all devices
- [ ] Interactive features work smoothly

### ✅ Integration
- [ ] End-to-end workflow completes successfully
- [ ] Error handling works properly
- [ ] Security measures are effective
- [ ] Performance is acceptable
- [ ] Cron job executes correctly

## Quick Test Checklist

For rapid testing, follow this condensed checklist:

1. **Login** as admin user
2. **Navigate** to SMS Templates → Create a test template
3. **Navigate** to SMS Credits → Check balance and purchase flow
4. **Create** test member with today's birthday
5. **Run** `php artisan sms:send-birthday --dry-run`
6. **Verify** all components work together

If all steps complete successfully, the SMS system is fully functional and ready for production use.
