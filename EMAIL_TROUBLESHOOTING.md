# 🔧 Email Troubleshooting Guide

## ⚠️ Problem: Emails Not Being Sent

You're seeing this log message:
```
[2025-10-13 23:11:11] local.INFO: Announcement emails sent {"announcement_id":2,"recipients_count":9}
```

But members aren't receiving emails. Here's how to fix it:

---

## 🔍 Step 1: Verify Your Current Email Configuration

Run this command to check your email settings:

```bash
php artisan email:test your-email@example.com
```

This will:
- Show your current email configuration
- Attempt to send a test email
- Display any errors if it fails

---

## 🚨 Common Issues & Solutions

### Issue 1: Gmail Authentication Error

**Symptoms:**
- Error: "Authentication failed"
- Error: "Username and Password not accepted"

**Solution:**
You need to use an **App Password**, not your regular Gmail password.

**How to get Gmail App Password:**
1. Go to your Google Account: https://myaccount.google.com
2. Click **Security** in the left menu
3. Enable **2-Step Verification** (if not already enabled)
4. Go back to Security, find **App passwords**
5. Generate a new app password for "Mail"
6. Copy the 16-character password
7. Update your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx    # The 16-character app password (remove spaces)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Beulah Family"
```

8. Run: `php artisan config:clear`
9. Test: `php artisan email:test your-email@gmail.com`

---

### Issue 2: Connection Refused / Timeout

**Symptoms:**
- Error: "Connection refused"
- Error: "Connection timed out"

**Solution:**
Your firewall or hosting provider is blocking SMTP ports.

**Try these ports:**
- Port 587 (TLS) - Most common
- Port 465 (SSL) - Alternative
- Port 2525 - Some providers

Update `.env`:
```env
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```
OR
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

---

### Issue 3: Using Mailtrap for Testing

**Best solution for development/testing!**

Mailtrap catches all emails in a test inbox without sending to real addresses.

**Setup:**
1. Sign up free at: https://mailtrap.io
2. Go to **Inbox Settings**
3. Copy your credentials
4. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@beulahfamily.org
MAIL_FROM_NAME="Beulah Family"
```

5. Run: `php artisan config:clear`
6. Create an announcement with email notification
7. Check Mailtrap inbox to see the email!

---

### Issue 4: No Error Messages

**If you see "emails sent" but no errors:**

This means emails are being "sent" but silently failing.

**Check detailed logs:**
```bash
tail -50 storage/logs/laravel.log
```

Look for:
- "Email sent successfully to: email@example.com"
- "Failed to send announcement email"

If you see neither, your SMTP configuration might be wrong.

---

## 📋 Quick Checklist

Before sending announcement emails, verify:

- [ ] MAIL_MAILER is set to `smtp` (not `log`)
- [ ] MAIL_HOST is correct (e.g., smtp.gmail.com)
- [ ] MAIL_PORT is correct (587 or 465)
- [ ] MAIL_USERNAME is set (your email address)
- [ ] MAIL_PASSWORD is correct (App Password for Gmail)
- [ ] MAIL_ENCRYPTION is set (tls or ssl)
- [ ] MAIL_FROM_ADDRESS is set
- [ ] Ran `php artisan config:clear` after changing `.env`
- [ ] Test email works: `php artisan email:test your@email.com`
- [ ] Members have `receive_newsletter = true` in database
- [ ] Members have valid email addresses

---

## 🧪 Testing Email Functionality

### Test 1: Send Simple Test Email
```bash
php artisan email:test your-email@example.com
```

### Test 2: Check Member Settings
```bash
php artisan tinker
```
Then run:
```php
// Check how many members opted in for newsletters
$count = App\Models\Member::where('receive_newsletter', true)
    ->where('is_active', true)
    ->whereNotNull('email')
    ->count();
echo "Members who will receive emails: $count\n";

// List first 5 members who will receive emails
$members = App\Models\Member::where('receive_newsletter', true)
    ->where('is_active', true)
    ->whereNotNull('email')
    ->take(5)
    ->get(['first_name', 'last_name', 'email']);
$members->each(function($m) {
    echo "{$m->first_name} {$m->last_name} - {$m->email}\n";
});
```

### Test 3: Send Test Announcement
1. Create a test announcement
2. Check "Send email notification"
3. Select target audience
4. Click "Publish"
5. Check logs: `tail -100 storage/logs/laravel.log`
6. Look for:
   - "Email sent successfully to: ..."
   - "Announcement email sending completed"
   - Check success/failed counts

---

## 🔒 Security Notes

**NEVER commit your .env file to git!**

Your email password is sensitive. Keep it secure:
- `.env` file is in `.gitignore` by default
- Use App Passwords (not your main password)
- Use environment variables in production
- Consider using professional email services for production

---

## 🌟 Recommended Email Services

### For Testing/Development:
1. **Mailtrap** - Free, catches all emails in test inbox
2. **Gmail** - Good for testing, but use App Password

### For Production:
1. **SendGrid** - 100 emails/day free
2. **Mailgun** - Professional, reliable
3. **Amazon SES** - Cheap, scalable
4. **Postmark** - Great deliverability

---

## 📞 Still Having Issues?

1. **Check Laravel logs:**
   ```bash
   tail -100 storage/logs/laravel.log
   ```

2. **Run email test:**
   ```bash
   php artisan email:test your@email.com
   ```

3. **Verify SMTP credentials** are correct

4. **Try Mailtrap** first to isolate configuration issues

5. **Check member settings** in database (receive_newsletter field)

6. **Contact your hosting provider** if using shared hosting (they may block SMTP)

---

## ✅ Success Indicators

When emails are working correctly, you'll see in logs:

```
[2025-10-13 23:11:11] local.INFO: Email sent successfully to: member1@example.com
[2025-10-13 23:11:12] local.INFO: Email sent successfully to: member2@example.com
[2025-10-13 23:11:13] local.INFO: Email sent successfully to: member3@example.com
...
[2025-10-13 23:11:15] local.INFO: Announcement email sending completed {
    "announcement_id":2,
    "total_recipients":9,
    "successful_sends":9,
    "failed_sends":0
}
```

And members will receive beautiful HTML emails in their inbox!
