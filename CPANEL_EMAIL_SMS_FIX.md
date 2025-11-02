# cPanel Email & SMS Configuration Fix

## 🔴 CRITICAL ISSUES FOUND

### Issue 1: Email Configuration ❌
**Current Setting:**
```env
MAIL_MAILER=log
```

**Problem:** Emails are being logged to file instead of being sent!

**Fix Required:** Change to SMTP configuration

---

### Issue 2: Invalid Line in ENV File ❌
**Line 52:**
```
qcba wjup zjvt pcuk
```

**Problem:** This is invalid and will break environment loading!

**Fix Required:** Remove this line completely

---

### Issue 3: Queue Configuration ⚠️
**Current Setting:**
```env
QUEUE_CONNECTION=database
```

**Problem:** Queue jobs (including emails/SMS) need to be processed manually

**Fix Required:** Set up queue worker or change to sync

---

## ✅ COMPLETE FIX

### Step 1: Fix Email Configuration

Replace the email section (lines 52-60) with:

```env
# Remove the invalid line: qcba wjup zjvt pcuk

# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=hicliqsgh@gmail.com
MAIL_PASSWORD=your_app_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hicliqsgh@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:** Replace `your_app_password_here` with actual Gmail App Password

---

### Step 2: Fix Queue Connection

Change from database to sync for immediate processing:

```env
# Change this line:
QUEUE_CONNECTION=database

# To this:
QUEUE_CONNECTION=sync
```

**OR** if you want to use database queue, set up a queue worker (see below)

---

### Step 3: Verify MNotify Configuration

Your MNotify settings look correct:
```env
MNOTIFY_API_KEY=03e26c8f-26a3-4af7-844c-29bab9243cd6
MNOTIFY_SENDER_ID=SLCECoE
```

✅ These are fine!

---

## 📝 CORRECTED ENV FILE

Here's what your email/queue section should look like:

```env
# Email Configuration (SMTP - Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=hicliqsgh@gmail.com
MAIL_PASSWORD=qcba wjup zjvt pcuk
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hicliqsgh@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Queue Configuration (Sync for immediate processing)
QUEUE_CONNECTION=sync

# SMS Configuration (Already correct)
MNOTIFY_API_KEY=03e26c8f-26a3-4af7-844c-29bab9243cd6
MNOTIFY_SENDER_ID=SLCECoE
```

---

## 🔧 Implementation Steps

### On Your Local Machine:

1. **Edit the env file:**
```bash
nano /Applications/XAMPP/xamppfiles/htdocs/beulahfamily/env
```

2. **Make these changes:**
   - Remove line 52: `qcba wjup zjvt pcuk`
   - Change `MAIL_MAILER=log` to `MAIL_MAILER=smtp`
   - Add proper SMTP configuration
   - Change `QUEUE_CONNECTION=database` to `QUEUE_CONNECTION=sync`

3. **Save the file**

### On cPanel Server:

1. **Upload the corrected env file to cPanel**

2. **Rename it to .env:**
```bash
# Via SSH or cPanel Terminal
cd /home/username/public_html/beulahfamily
mv env .env
```

3. **Clear Laravel cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

4. **Set proper permissions:**
```bash
chmod 644 .env
```

---

## 🎯 Gmail App Password Setup

**The line `qcba wjup zjvt pcuk` appears to be a Gmail App Password!**

### If this is your Gmail App Password:

1. **Keep it as the MAIL_PASSWORD:**
```env
MAIL_PASSWORD=qcba wjup zjvt pcuk
```

2. **Remove it from line 52** (the invalid standalone line)

### If you need a new Gmail App Password:

1. Go to: https://myaccount.google.com/security
2. Enable 2-Step Verification (if not enabled)
3. Go to: https://myaccount.google.com/apppasswords
4. Create new app password for "Mail"
5. Copy the 16-character password
6. Use it in MAIL_PASSWORD

---

## 🔄 Queue Worker Setup (Alternative)

If you want to use database queue instead of sync:

### Option A: Supervisor (Recommended for Production)

1. **Create supervisor config:**
```bash
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

2. **Add configuration:**
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/username/public_html/beulahfamily/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=username
numprocs=1
redirect_stderr=true
stdout_logfile=/home/username/public_html/beulahfamily/storage/logs/worker.log
```

3. **Start supervisor:**
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Option B: Cron Job (Simple Alternative)

Add to cPanel cron jobs:
```bash
* * * * * cd /home/username/public_html/beulahfamily && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

---

## 🧪 Testing After Fix

### Test Email:

```bash
php artisan tinker

>>> Mail::raw('Test email from cPanel', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

### Test SMS:

```bash
php artisan sms:test-registration 0241234567 --name=Test
```

### Test Member Registration:

1. Register a new member
2. Check if welcome email received
3. Check if welcome SMS received
4. Check logs: `tail -f storage/logs/laravel.log`

---

## 📊 Verification Checklist

After making changes, verify:

- [ ] Invalid line removed from env file
- [ ] MAIL_MAILER changed from `log` to `smtp`
- [ ] SMTP credentials configured correctly
- [ ] QUEUE_CONNECTION set to `sync` or worker running
- [ ] Config cache cleared
- [ ] Test email sent successfully
- [ ] Test SMS sent successfully
- [ ] Member registration sends email & SMS

---

## 🔍 Common Errors & Solutions

### Error: "Connection refused"

**Cause:** Wrong SMTP host/port

**Fix:**
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

### Error: "Authentication failed"

**Cause:** Wrong password or 2FA not enabled

**Fix:**
1. Enable 2-Step Verification on Gmail
2. Generate new App Password
3. Use App Password (not regular password)

### Error: "Queue connection not found"

**Cause:** Config cache not cleared

**Fix:**
```bash
php artisan config:clear
php artisan cache:clear
```

### Error: "SMS credits not working"

**Cause:** Queue not processing

**Fix:**
```env
QUEUE_CONNECTION=sync
```
Then clear config cache.

---

## 📝 Quick Fix Summary

**3 Critical Changes Needed:**

1. **Remove invalid line 52:**
   ```
   DELETE: qcba wjup zjvt pcuk
   ```

2. **Fix email configuration:**
   ```env
   CHANGE: MAIL_MAILER=log
   TO: MAIL_MAILER=smtp
   
   ADD: MAIL_HOST=smtp.gmail.com
   ADD: MAIL_PORT=587
   ADD: MAIL_USERNAME=hicliqsgh@gmail.com
   ADD: MAIL_PASSWORD=qcba wjup zjvt pcuk
   ADD: MAIL_ENCRYPTION=tls
   ```

3. **Fix queue processing:**
   ```env
   CHANGE: QUEUE_CONNECTION=database
   TO: QUEUE_CONNECTION=sync
   ```

Then:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🎯 Expected Result

After fixing:

✅ **Emails will:**
- Send via Gmail SMTP
- Arrive in recipient inbox
- Include proper sender name
- Work for registration, approval, notifications

✅ **SMS will:**
- Send immediately via MNotify
- Deduct credits properly
- Work for registration, approval, birthday

✅ **Queue will:**
- Process jobs immediately (sync mode)
- No manual intervention needed
- No stuck jobs

---

## 🚨 URGENT ACTION REQUIRED

**Priority 1:** Remove the invalid line 52
**Priority 2:** Change MAIL_MAILER from log to smtp
**Priority 3:** Change QUEUE_CONNECTION to sync

**These 3 changes will fix all email/SMS issues!**
