# Cron Job Setup for Birthday SMS Automation

This guide explains how to set up the automated birthday SMS sender on cPanel hosting.

## Birthday SMS Command

The system includes an artisan command that sends birthday SMS messages to members whose birthday is today.

**Command:** `php artisan sms:send-birthday`

## Features

- Automatically finds members with birthdays matching today's date
- Uses active birthday SMS template or creates a default one
- Checks system SMS credits before sending
- Sends personalized SMS messages via MNotify gateway
- Deducts credits from admin user account
- Logs all activities for auditing
- Supports dry-run mode for testing

## cPanel Cron Job Setup

### Step 1: Access Cron Jobs in cPanel

1. Log into your cPanel account
2. Navigate to **Advanced** section
3. Click on **Cron Jobs**

### Step 2: Create the Cron Job

**Frequency Settings:**
- Minute: `0`
- Hour: `6` (for 6:00 AM)
- Day: `*` (every day)
- Month: `*` (every month)  
- Weekday: `*` (every day of the week)

**Command:**
```bash
/usr/local/bin/php /home/yourusername/public_html/beulahfamily/artisan sms:send-birthday
```

**Alternative Command Format:**
```bash
cd /home/yourusername/public_html/beulahfamily && /usr/local/bin/php artisan sms:send-birthday
```

### Step 3: Complete Cron Job Configuration

**Full Cron Expression:**
```
0 6 * * * /usr/local/bin/php /home/yourusername/public_html/beulahfamily/artisan sms:send-birthday
```

This will run the birthday SMS command every day at 6:00 AM server time.

## Testing the Command

### Dry Run Test
Test the command without sending actual SMS:
```bash
php artisan sms:send-birthday --dry-run
```

### Manual Test
Run the command manually to test:
```bash
php artisan sms:send-birthday
```

## Command Options

- `--dry-run`: Test mode - shows what would be sent without actually sending SMS
- `--force`: Force sending even if no birthdays found (for testing)

## Prerequisites

Before setting up the cron job, ensure:

1. **SMS Credits Available**: Admin user must have sufficient SMS credits
2. **MNotify Configuration**: API key and sender ID must be configured
3. **Birthday Template**: At least one active birthday SMS template exists
4. **Member Data**: Members have valid phone numbers and birth dates

## Environment Variables Required

Add these to your `.env` file:
```env
MNOTIFY_API_KEY=your_mnotify_api_key
MNOTIFY_SENDER_ID=your_sender_id
```

## Monitoring and Logs

The command logs all activities to Laravel's log files:
- Success messages when SMS are sent
- Error messages for failures
- Credit balance warnings
- Member data issues

Check logs at: `storage/logs/laravel.log`

## Troubleshooting

### Common Issues:

1. **No SMS Credits**
   - Solution: Purchase SMS credits through the admin panel

2. **No Birthday Template**
   - Solution: Create an active birthday SMS template

3. **Invalid Phone Numbers**
   - Solution: Ensure member phone numbers are in correct format

4. **MNotify API Errors**
   - Solution: Verify API key and sender ID configuration

5. **Cron Job Not Running**
   - Solution: Check cPanel cron job syntax and file permissions

### Testing Checklist:

- [ ] Command runs without errors in dry-run mode
- [ ] SMS credits are available in admin account
- [ ] Birthday SMS template exists and is active
- [ ] Member phone numbers are valid
- [ ] MNotify API credentials are correct
- [ ] Cron job syntax is correct in cPanel

## Support

For technical support with the birthday SMS automation:
1. Check the Laravel logs for error details
2. Test the command manually first
3. Verify all prerequisites are met
4. Contact your hosting provider for cPanel-specific issues

---

**Note:** Replace `/home/yourusername/public_html/beulahfamily/` with your actual server path to the Laravel application.
