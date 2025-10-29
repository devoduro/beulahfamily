# SMS Credit System & Birthday Automation Documentation

## Overview

The Beulah Family management system now includes a comprehensive SMS credit system with automated birthday messaging functionality. This system allows users to purchase SMS credits via Paystack and automatically sends birthday wishes to members daily.

## Features Implemented

### 1. SMS Credit Management
- **Credit Purchase System**: Users can buy SMS credits through multiple packages
- **Paystack Integration**: Secure payment processing with mobile money, bank transfer, and card options
- **Transaction History**: Complete audit trail of all credit purchases and usage
- **Balance Tracking**: Real-time credit balance monitoring
- **Admin Credit Management**: Administrators can manually add credits to user accounts

### 2. SMS Template System
- **Template Creation**: Create reusable SMS templates with dynamic variables
- **Category Management**: Organize templates by categories (birthday, events, announcements, etc.)
- **Variable Substitution**: Support for dynamic content like {{first_name}}, {{church_name}}, etc.
- **Template Preview**: Live preview of messages with sample data
- **Status Management**: Activate/deactivate templates as needed

### 3. Birthday SMS Automation
- **Daily Automation**: Automatically sends birthday SMS at 6 AM daily via cron job
- **Member Detection**: Finds all members with birthdays matching current date
- **Template Integration**: Uses active birthday templates or creates default messages
- **Credit Validation**: Checks available SMS credits before sending
- **Logging & Monitoring**: Comprehensive logging of all SMS activities
- **Dry Run Support**: Test mode for validation without sending actual SMS

## Database Structure

### Tables Created
1. **sms_credits**: Stores user SMS credit balances
2. **sms_credit_transactions**: Logs all credit-related transactions
3. **sms_pricing**: Defines SMS credit packages and pricing
4. **sms_templates**: Stores reusable SMS message templates

### Key Relationships
- Users have SMS credits (one-to-one)
- Users have multiple credit transactions (one-to-many)
- Templates belong to users (many-to-one)
- Transactions reference pricing plans (many-to-one)

## Controllers Implemented

### SmsCreditController
- `index()`: SMS credit dashboard with balance and recent transactions
- `purchase()`: Credit purchase page with pricing plans
- `initialize()`: Initialize Paystack payment for credit purchase
- `verify()`: Verify and process completed payments
- `transactions()`: Complete transaction history with filtering
- `balance()`: API endpoint for current credit balance
- `addCredits()`: Admin function to manually add credits

### SmsTemplateController
- `index()`: Template listing with search and filtering
- `create()`: Template creation form
- `store()`: Save new templates
- `show()`: View template details and preview
- `edit()`: Template editing form
- `update()`: Update existing templates
- `destroy()`: Delete templates
- `toggleStatus()`: Activate/deactivate templates
- `duplicate()`: Create copies of existing templates
- `preview()`: Generate template previews with sample data

## Models & Features

### SmsCredit Model
- `addCredits()`: Add credits to user account
- `deductCredits()`: Remove credits for SMS usage
- `hasCredits()`: Check if sufficient credits available
- `getOrCreateForUser()`: Get or create credit record for user

### SmsCreditTransaction Model
- Tracks all credit movements (purchase, usage, refunds, bonuses)
- Includes Paystack payment references
- Supports transaction status tracking
- Provides scopes for filtering by type and status

### SmsPricing Model
- Defines credit packages with pricing and bonus credits
- Calculates total credits including bonuses
- Provides formatted price display
- Supports active/inactive package management

### SmsTemplate Model
- `renderMessage()`: Replace template variables with actual values
- `getTemplateVariables()`: Extract variables from template content
- `incrementUsage()`: Track template usage statistics
- Category-based organization and filtering

## Views Created

### SMS Credits
- `index.blade.php`: Credit dashboard with balance, packages, and recent transactions
- `purchase.blade.php`: Credit purchase page with package selection and payment
- `transactions.blade.php`: Complete transaction history with filtering and export

### SMS Templates
- `index.blade.php`: Template listing with search, filtering, and bulk actions
- `create.blade.php`: Template creation form with variable insertion tools
- `edit.blade.php`: Template editing with preview functionality
- `show.blade.php`: Template details view with usage statistics

## Console Commands

### SendBirthdaySms Command
```bash
php artisan sms:send-birthday [--dry-run]
```

**Features:**
- Finds members with birthdays matching current date
- Uses active birthday SMS template
- Validates SMS credit availability
- Sends personalized birthday messages
- Deducts credits from admin account
- Logs all activities for monitoring
- Supports dry-run mode for testing

**Usage:**
- `--dry-run`: Test mode without sending actual SMS
- Runs automatically via cron job at 6 AM daily

## Routes Added

### SMS Templates
- `GET /sms/templates` - Template listing
- `GET /sms/templates/create` - Create template form
- `POST /sms/templates` - Store new template
- `GET /sms/templates/{template}` - View template
- `GET /sms/templates/{template}/edit` - Edit template form
- `PUT /sms/templates/{template}` - Update template
- `DELETE /sms/templates/{template}` - Delete template
- `POST /sms/templates/{template}/toggle-status` - Toggle active status
- `POST /sms/templates/{template}/duplicate` - Duplicate template
- `POST /sms/templates/{template}/preview` - Generate preview

### SMS Credits
- `GET /sms/credits` - Credit dashboard
- `GET /sms/credits/purchase` - Purchase page
- `POST /sms/credits/initialize` - Initialize payment
- `GET /sms/credits/verify` - Verify payment
- `GET /sms/credits/transactions` - Transaction history
- `GET /sms/credits/balance` - Current balance API
- `POST /sms/credits/add-credits` - Manual credit addition (admin)

## Seeders Created

### SmsPricingSeeder
Populates initial SMS credit packages:
- Starter Pack: 100 credits for GHS 10.00
- Basic Pack: 250 credits for GHS 22.50 (10% bonus)
- Standard Pack: 500 credits for GHS 40.00 (15% bonus)
- Premium Pack: 1000 credits for GHS 70.00 (20% bonus)
- Enterprise Pack: 2500 credits for GHS 150.00 (25% bonus)
- Unlimited Monthly: 10000 credits for GHS 300.00

### SmsTemplateSeeder
Creates default SMS templates:
- Birthday Wishes
- Welcome New Member
- Sunday Service Reminder
- Event Announcement
- Donation Thank You
- Emergency Alert

### SmsCreditSeeder
Adds initial 500 SMS credits to admin account for system operations.

## Environment Variables Required

```env
# Paystack Configuration
PAYSTACK_PUBLIC_KEY=pk_test_xxx
PAYSTACK_SECRET_KEY=sk_test_xxx
PAYSTACK_MERCHANT_EMAIL=your@email.com

# MNotify SMS Gateway
MNOTIFY_API_KEY=your_mnotify_api_key
MNOTIFY_SENDER_ID=your_sender_id
```

## Cron Job Setup

Add to cPanel cron jobs for daily birthday SMS at 6 AM:
```bash
0 6 * * * /usr/local/bin/php /path/to/beulahfamily/artisan sms:send-birthday
```

## Testing Completed

1. ✅ **SMS Credit System**: Credit purchase, transaction logging, balance tracking
2. ✅ **Template Management**: Create, edit, preview, and manage SMS templates
3. ✅ **Birthday Automation**: Daily birthday SMS detection and sending
4. ✅ **Payment Integration**: Paystack payment processing for credit purchases
5. ✅ **Database Integration**: All migrations, models, and relationships working
6. ✅ **User Interface**: Complete responsive UI for all SMS features
7. ✅ **Command Testing**: Birthday SMS command working with dry-run support

## System Status

The SMS credit system and birthday automation are fully operational and ready for production use. All components have been tested and verified working correctly.

### Key Benefits
- **Automated Birthday Wishes**: Never miss a member's birthday
- **Cost-Effective SMS**: Bulk credit purchases with bonus credits
- **Template Reusability**: Create once, use multiple times
- **Complete Audit Trail**: Track all SMS activities and expenses
- **Secure Payments**: Paystack integration with multiple payment methods
- **Easy Management**: User-friendly interfaces for all operations

The system is now ready for deployment and daily use in the church management workflow.
