# cPanel Cron Job Quick Setup Guide

## ⚡ Quick Setup (5 Minutes)

### Step 1: Login to cPanel
- Go to your hosting cPanel
- Login with your credentials

### Step 2: Find Cron Jobs
- Scroll to **"Advanced"** section
- Click **"Cron Jobs"**

### Step 3: Add Cron Job

**Copy this command** (replace USERNAME and path):

```bash
cd /home/USERNAME/public_html/beulahfamily && php artisan schedule:run >> /dev/null 2>&1
```

**Settings:**

| Field | Value |
|-------|-------|
| Common Settings | **Every Minute (*****)** |
| Command | Paste the command above |

### Step 4: Click "Add New Cron Job"

---

## 🔍 Find Your Project Path

### Method 1: Via cPanel File Manager
1. Open **File Manager** in cPanel
2. Navigate to your project folder
3. Look at the path in the address bar
4. Example: `/home/username/public_html/beulahfamily`

### Method 2: Via SSH
```bash
cd beulahfamily
pwd
```

### Common Paths:
```
/home/username/public_html/beulahfamily
/home/username/beulahfamily
/home/username/domains/yourdomain.com/public_html
/home/username/htdocs/beulahfamily
```

---

## 📋 Complete Examples

### Example 1: Standard Shared Hosting
```bash
cd /home/ghanabeulah/public_html/beulahfamily && php artisan schedule:run >> /dev/null 2>&1
```

### Example 2: Subdomain
```bash
cd /home/username/domains/church.yourdomain.com/public_html && php artisan schedule:run >> /dev/null 2>&1
```

### Example 3: Custom PHP Path
```bash
cd /home/username/public_html/beulahfamily && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ Verification

### Check if Cron is Running

**Wait 2-3 minutes after adding, then check logs via SSH or File Manager:**

```bash
# Navigate to your project
cd /home/username/public_html/beulahfamily

# Check logs
tail -50 storage/logs/laravel.log
```

**Look for:**
```
[2025-10-29 08:00:01] local.INFO: Starting birthday SMS sender
```

---

## 🎂 Birthday SMS Schedule

**When it runs:** Daily at 8:00 AM (Ghana Time)

**What it does:**
1. Finds members with birthdays today
2. Sends personalized SMS
3. Deducts SMS credits
4. Logs all activities

**Message example:**
```
Happy Birthday John! 🎉 May God bless you with joy, peace, 
and prosperity in your new year. We celebrate you today! 
- Beulah Family
```

---

## 🧪 Test Before Going Live

### Test 1: Dry Run (No SMS Sent)
```bash
php artisan sms:send-birthday --dry-run
```

### Test 2: Check Schedule
```bash
php artisan schedule:list
```

Expected output:
```
0 8 * * * php artisan sms:send-birthday  Next Due: X hours from now
```

### Test 3: Manual Run (Sends Real SMS)
```bash
php artisan sms:send-birthday
```

---

## 🔧 Troubleshooting

### Cron Not Running?

**Check 1: Verify cron is added**
- Login to cPanel
- Go to Cron Jobs
- See if your cron is listed

**Check 2: Check PHP path**
```bash
which php
```

If output is `/usr/local/bin/php`, update your cron:
```bash
cd /path/to/project && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

**Check 3: Check permissions**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**Check 4: View cron logs**

Some cPanel accounts have cron logs at:
```
/var/log/cron
```

Or check Laravel logs:
```bash
tail -100 storage/logs/laravel.log
```

---

## 💰 SMS Credits

**Check credits:**
```bash
php artisan tinker
>>> App\Models\SmsCredit::where('user_id', 1)->first()->credits
```

**Add credits:**
```bash
php artisan tinker
>>> $credit = App\Models\SmsCredit::where('user_id', 1)->first();
>>> $credit->addCredits(100, 'Monthly top-up');
```

**Recommended:** Keep 100+ credits available

---

## 📞 Need Help?

**Common Issues:**

1. **"Command not found"**
   - Check PHP path with `which php`
   - Update cron command with full PHP path

2. **"Permission denied"**
   - Fix permissions: `chmod -R 775 storage bootstrap/cache`

3. **"No SMS sent"**
   - Check SMS credits
   - Verify MNotify API key in `.env`
   - Check member phone numbers

4. **"Cron not running"**
   - Verify cron is added in cPanel
   - Wait 2-3 minutes for first run
   - Check logs for errors

---

## ✅ Final Checklist

Before going live:

- [ ] cPanel cron job added
- [ ] Cron set to run every minute
- [ ] Project path is correct
- [ ] PHP path is correct (if custom)
- [ ] SMS credits added (100+)
- [ ] Dry-run test successful
- [ ] Birthday template active
- [ ] Logs showing cron execution
- [ ] Test member received SMS

---

## 🎯 Quick Commands

```bash
# Test dry run
php artisan sms:send-birthday --dry-run

# Send actual SMS
php artisan sms:send-birthday

# Check schedule
php artisan schedule:list

# View logs
tail -50 storage/logs/laravel.log

# Check credits
php artisan tinker --execute="echo App\Models\SmsCredit::where('user_id', 1)->first()->credits;"
```

---

## 📝 cPanel Cron Summary

**Frequency:** Every minute  
**Command:** `cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1`  
**Birthday SMS:** Runs daily at 8:00 AM  
**Credits:** Uses system/admin SMS credits  
**Logs:** `storage/logs/laravel.log`

---

## ✨ Done!

Your birthday SMS system is now automated and will run daily at 8:00 AM via cPanel cron job! 🎉
