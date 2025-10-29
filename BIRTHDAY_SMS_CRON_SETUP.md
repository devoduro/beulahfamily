# Birthday SMS Cron Job Setup Guide

## Overview
The system automatically sends birthday SMS messages to members celebrating their birthday each day. This guide covers setup, testing, and cPanel cron configuration.

---

## 🎂 How It Works

### Automatic Birthday SMS System

```
Daily at 8:00 AM
    ↓
Laravel Scheduler runs
    ↓
Birthday SMS Command executes
    ↓
Finds members with birthdays today
    ↓
Checks SMS credits available
    ↓
Sends personalized birthday SMS
    ↓
Deducts SMS credits
    ↓
Logs all activities
```

---

## 📋 Features

### ✅ **Automatic Detection**
- Finds all members with birthdays on current date
- Checks only active members
- Requires valid phone number
- Respects `receive_sms` preference

### ✅ **Personalized Messages**
- Uses SMS templates from database
- Variables: `{{first_name}}`, `{{last_name}}`, `{{age}}`, `{{church_name}}`
- Default template included
- Customizable via admin panel

### ✅ **Credit Management**
- Checks SMS credits before sending
- Deducts 1 credit per SMS
- Uses system/admin credits
- Prevents sending if insufficient credits

### ✅ **Logging & Tracking**
- Logs all birthday SMS sent
- Tracks successes and failures
- Records member details
- Audit trail for compliance

---

## 🧪 Testing

### Method 1: Quick Test Command

Create a test member and send birthday SMS:

```bash
php artisan sms:test-birthday-setup YOUR_PHONE --name=YourName
```

**Example:**
```bash
php artisan sms:test-birthday-setup 0241234567 --name=John
```

**This will:**
1. Create/update test member with birthday today
2. Run dry-run test (no SMS sent)
3. Ask if you want to send actual SMS
4. Optionally delete test member after

### Method 2: Manual Dry Run

Test without sending SMS:

```bash
php artisan sms:send-birthday --dry-run
```

**Output:**
```
Starting birthday SMS sender for 2025-10-29
Found 2 members with birthdays today:
- John Doe (0241234567)
- Mary Smith (0501234567)

Using template: Birthday Wishes
SMS Credits available: 100

Sending to John Doe (233241234567):
Message: Happy Birthday John! 🎉 May God bless you...
✓ Would send SMS (dry run)

--- Birthday SMS Summary ---
Total members: 2
Successful: 2
Failed: 0
```

### Method 3: Send Actual SMS

Send real birthday SMS:

```bash
php artisan sms:send-birthday
```

### Method 4: Check Scheduled Tasks

View all scheduled tasks:

```bash
php artisan schedule:list
```

Expected output:
```
0 8 * * * php artisan sms:send-birthday .... Next Due: 8 hours from now
```

---

## ⚙️ Laravel Scheduler Configuration

### File: `routes/console.php`

```php
<?php

use Illuminate\Support\Facades\Schedule;

// Schedule birthday SMS to run daily at 8:00 AM
Schedule::command('sms:send-birthday')->dailyAt('08:00');
```

### Schedule Options

You can customize the schedule:

```php
// Run daily at 8:00 AM (default)
Schedule::command('sms:send-birthday')->dailyAt('08:00');

// Run daily at 9:00 AM
Schedule::command('sms:send-birthday')->dailyAt('09:00');

// Run twice daily (8 AM and 6 PM)
Schedule::command('sms:send-birthday')->twiceDaily(8, 18);

// Run every day at midnight
Schedule::command('sms:send-birthday')->daily();

// Run on weekdays only
Schedule::command('sms:send-birthday')->dailyAt('08:00')->weekdays();
```

---

## 🖥️ cPanel Cron Job Setup

### Step 1: Access cPanel

1. Login to your cPanel account
2. Navigate to **"Advanced"** section
3. Click on **"Cron Jobs"**

### Step 2: Add Cron Job

**Frequency:** Every Minute (Laravel scheduler handles timing)

**Command:**
```bash
cd /home/USERNAME/public_html/beulahfamily && php artisan schedule:run >> /dev/null 2>&1
```

**Replace:**
- `USERNAME` with your cPanel username
- `/home/USERNAME/public_html/beulahfamily` with your actual project path

### Step 3: Common cPanel Paths

Find your project path:

```bash
# Via SSH
pwd

# Common paths:
/home/username/public_html/beulahfamily
/home/username/beulahfamily
/home/username/domains/yourdomain.com/public_html
```

### Step 4: Verify PHP Path

Check PHP CLI path:

```bash
which php
# Output: /usr/bin/php or /usr/local/bin/php
```

If different, update cron command:

```bash
cd /home/USERNAME/public_html/beulahfamily && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Step 5: Complete cPanel Cron Configuration

**Settings in cPanel:**

| Field | Value |
|-------|-------|
| **Minute** | * |
| **Hour** | * |
| **Day** | * |
| **Month** | * |
| **Weekday** | * |
| **Command** | `cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1` |

**Or use shortcut:**

| Field | Value |
|-------|-------|
| **Common Settings** | Every Minute (****) |
| **Command** | `cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1` |

### Step 6: Save and Verify

1. Click **"Add New Cron Job"**
2. Verify cron appears in list
3. Wait 1-2 minutes
4. Check logs: `storage/logs/laravel.log`

---

## 📊 Cron Job Monitoring

### Check if Cron is Running

**View cron logs:**
```bash
tail -f storage/logs/laravel.log | grep "Birthday SMS"
```

**Check last run:**
```bash
grep "Birthday SMS" storage/logs/laravel.log | tail -20
```

### Expected Log Entries

**When birthdays found:**
```
[2025-10-29 08:00:01] local.INFO: Birthday SMS sent {"member_id":123,"member_name":"John Doe","phone":"233241234567"}
```

**When no birthdays:**
```
[2025-10-29 08:00:01] local.INFO: No members with birthdays today.
```

**When errors occur:**
```
[2025-10-29 08:00:01] local.ERROR: Birthday SMS failed {"member_id":123,"error":"Insufficient credits"}
```

---

## 🔧 Troubleshooting

### Issue 1: Cron Not Running

**Check:**
```bash
# View cron jobs
crontab -l

# Check cron service (if SSH access)
service cron status
```

**Solution:**
- Verify cron command is correct
- Check PHP path is correct
- Ensure project path is absolute
- Check file permissions

### Issue 2: SMS Not Sending

**Check:**
1. **SMS Credits:**
   ```bash
   php artisan tinker
   >>> App\Models\SmsCredit::where('user_id', 1)->first()->credits
   ```

2. **Active Members:**
   ```bash
   php artisan tinker
   >>> App\Models\Member::whereRaw('DATE_FORMAT(date_of_birth, "%m-%d") = ?', [date('m-d')])->count()
   ```

3. **MNotify Configuration:**
   ```bash
   php artisan tinker
   >>> config('services.mnotify.api_key')
   >>> config('services.mnotify.sender_id')
   ```

### Issue 3: Wrong Time Zone

**Check timezone:**
```bash
php artisan tinker
>>> config('app.timezone')
```

**Update `.env`:**
```env
APP_TIMEZONE=Africa/Accra
```

**Clear config:**
```bash
php artisan config:clear
```

### Issue 4: Logs Not Showing

**Check log file:**
```bash
ls -la storage/logs/laravel.log
```

**Fix permissions:**
```bash
chmod 775 storage/logs
chmod 664 storage/logs/laravel.log
```

---

## 📝 SMS Template Management

### Default Template

```
Happy Birthday {{first_name}}! 🎉 May God bless you with joy, peace, and prosperity in your new year. We celebrate you today! - {{church_name}}
```

### Create Custom Template

**Via Admin Panel:**
1. Navigate to: `/sms/templates`
2. Click "Create Template"
3. Set category: **Birthday**
4. Add your message with variables
5. Activate template

**Via Tinker:**
```php
php artisan tinker

>>> App\Models\SmsTemplate::create([
    'name' => 'Birthday Wishes 2025',
    'description' => 'Birthday message for 2025',
    'message' => 'Happy {{age}}th Birthday {{first_name}}! 🎂 Wishing you a blessed year ahead. - {{church_name}}',
    'category' => 'birthday',
    'variables' => ['first_name', 'last_name', 'full_name', 'age', 'church_name'],
    'created_by' => 1,
    'is_active' => true
]);
```

### Available Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `{{first_name}}` | Member's first name | John |
| `{{last_name}}` | Member's last name | Doe |
| `{{full_name}}` | Full name | John Doe |
| `{{age}}` | Current age | 25 |
| `{{church_name}}` | Church name | Beulah Family |

---

## 💰 SMS Credits Management

### Check Credits

```bash
php artisan tinker
>>> $credit = App\Models\SmsCredit::where('user_id', 1)->first();
>>> echo "Available: " . $credit->credits;
```

### Add Credits

```bash
php artisan tinker
>>> $credit = App\Models\SmsCredit::where('user_id', 1)->first();
>>> $credit->addCredits(100, 'Monthly top-up');
>>> echo "New balance: " . $credit->credits;
```

### Credit Calculation

**Example:**
- 50 members with birthdays this month
- 1 SMS per member = 50 credits needed
- Recommended: Keep 100+ credits buffer

---

## 📅 Testing Schedule

### Test Specific Date

To test birthday SMS for a specific date, temporarily modify the command:

**Edit:** `app/Console/Commands/SendBirthdaySms.php`

```php
// Line 43: Change from
$today = Carbon::today();

// To (for testing)
$today = Carbon::parse('2025-12-25'); // Christmas
```

**Run test:**
```bash
php artisan sms:send-birthday --dry-run
```

**Remember to revert after testing!**

---

## 🎯 Best Practices

### 1. **Monitor Credits**
- Check SMS credits weekly
- Set up low balance alerts
- Top up before running out

### 2. **Test Regularly**
- Run dry-run monthly
- Verify template rendering
- Check member data accuracy

### 3. **Review Logs**
- Check logs after each run
- Monitor success/failure rates
- Investigate failures promptly

### 4. **Update Templates**
- Refresh messages seasonally
- A/B test different messages
- Keep messages under 160 characters

### 5. **Backup Schedule**
- Document cron configuration
- Keep backup of templates
- Save successful message examples

---

## 📊 Statistics & Reporting

### Birthday SMS Report

```bash
# Count birthday SMS sent this month
grep "Birthday SMS sent" storage/logs/laravel.log | grep "$(date +%Y-%m)" | wc -l

# View all birthday SMS today
grep "Birthday SMS sent" storage/logs/laravel.log | grep "$(date +%Y-%m-%d)"

# Check failures
grep "Birthday SMS failed" storage/logs/laravel.log | tail -20
```

### Monthly Summary

```php
php artisan tinker

>>> use Carbon\Carbon;
>>> $startOfMonth = Carbon::now()->startOfMonth();
>>> $endOfMonth = Carbon::now()->endOfMonth();
>>> 
>>> $birthdaysThisMonth = App\Models\Member::whereRaw(
    'MONTH(date_of_birth) = ?', 
    [Carbon::now()->month]
)->count();
>>> 
>>> echo "Birthdays this month: " . $birthdaysThisMonth;
```

---

## ✅ Verification Checklist

Before going live, verify:

- [ ] Laravel scheduler configured in `routes/console.php`
- [ ] cPanel cron job added and running
- [ ] SMS credits sufficient (100+ recommended)
- [ ] MNotify API configured correctly
- [ ] Birthday template active and tested
- [ ] Test member receives SMS successfully
- [ ] Logs showing successful execution
- [ ] Timezone set correctly
- [ ] Member phone numbers formatted correctly
- [ ] Dry-run test completed successfully

---

## 🚀 Quick Start Commands

```bash
# Test with dry run
php artisan sms:send-birthday --dry-run

# Send actual SMS
php artisan sms:send-birthday

# Create test member and test
php artisan sms:test-birthday-setup 0241234567 --name=TestUser

# Check scheduled tasks
php artisan schedule:list

# View logs
tail -f storage/logs/laravel.log | grep "Birthday"

# Check SMS credits
php artisan tinker --execute="echo App\Models\SmsCredit::where('user_id', 1)->first()->credits;"
```

---

## 📞 Support

For issues:
1. Check logs: `storage/logs/laravel.log`
2. Verify cron is running: `crontab -l`
3. Test manually: `php artisan sms:send-birthday --dry-run`
4. Check SMS credits and MNotify status
5. Review member data for accuracy

---

## Summary

The birthday SMS system is fully automated and will:
- ✅ Run daily at 8:00 AM via cPanel cron
- ✅ Find members with birthdays today
- ✅ Send personalized SMS messages
- ✅ Manage SMS credits automatically
- ✅ Log all activities for monitoring
- ✅ Handle errors gracefully

**cPanel Cron Command:**
```bash
cd /home/USERNAME/public_html/beulahfamily && php artisan schedule:run >> /dev/null 2>&1
```

**Schedule:** Every minute (Laravel handles timing)
