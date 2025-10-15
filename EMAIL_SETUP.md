# Email Configuration Guide for Announcement Notifications

This guide will help you set up email notifications for church announcements.

## 📧 Email Service Options

You can use any of the following email services:

### 1. **Gmail (Recommended for Testing)**

Add these to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:** You need to use an App Password, not your regular Gmail password:
1. Go to your Google Account settings
2. Enable 2-Step Verification
3. Go to Security > 2-Step Verification > App passwords
4. Generate a new app password
5. Use that password in MAIL_PASSWORD

### 2. **Mailtrap (Recommended for Development/Testing)**

Free service for testing emails without sending to real addresses:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Sign up at: https://mailtrap.io

### 3. **SendGrid (Recommended for Production)**

Professional email service with good deliverability:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourchurch.org
MAIL_FROM_NAME="${APP_NAME}"
```

Sign up at: https://sendgrid.com

### 4. **Mailgun**

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-mailgun-domain
MAILGUN_SECRET=your-mailgun-secret
MAIL_FROM_ADDRESS=noreply@yourchurch.org
MAIL_FROM_NAME="${APP_NAME}"
```

## 🚀 Testing Email Configuration

After setting up your `.env` file, test if emails are working:

### Option 1: Laravel Tinker
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email from Church Management System', function($message) {
    $message->to('test@example.com')->subject('Test Email');
});
```

### Option 2: Create a Test Announcement
1. Log in as admin
2. Go to: http://127.0.0.1:8001/announcements/create
3. Fill in the form
4. Check "Send email notification"
5. Click "Publish Announcement"

## 📝 How Email Notifications Work

### When are emails sent?
- Only when you check "Send email notification" checkbox
- Only when the announcement status is "published"
- Emails are sent in the background (won't slow down the page)

### Who receives emails?
Emails are sent to members who meet ALL of these criteria:
- ✅ Active members (`is_active = true`)
- ✅ Opted in to receive newsletters (`receive_newsletter = true`)
- ✅ Have a valid email address
- ✅ Match the target audience (if specified)

### Target Audience Filtering

**All Members:** Everyone who meets the above criteria

**Youth:** Members aged 13-25 years

**Adults:** Members aged 26+ years

**Children:** Members aged 0-12 years

## 📊 Email Template

The email includes:
- **Priority badge** (for urgent/high priority announcements)
- **Announcement title**
- **Category and publish date**
- **Target audience** (if specified)
- **Image** (if uploaded)
- **Full announcement content**
- **"View Full Announcement" button** (links to website)
- **Church name and branding**

## 🔧 Troubleshooting

### Emails not sending?

1. **Check your .env file**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Check Laravel logs**
   ```
   storage/logs/laravel.log
   ```

3. **Verify member settings**
   - Make sure members have `receive_newsletter = true`
   - Check that email addresses are valid

4. **Test with Mailtrap first**
   - Use Mailtrap to test without sending real emails
   - This helps isolate configuration issues

### Common Issues

**"Connection refused"**
- Check MAIL_HOST and MAIL_PORT
- Verify firewall isn't blocking SMTP

**"Authentication failed"**
- Double-check MAIL_USERNAME and MAIL_PASSWORD
- For Gmail, use App Password, not regular password

**"Failed to connect to host"**
- Check MAIL_ENCRYPTION (tls or ssl)
- Try different MAIL_PORT (587 or 465)

## 📮 Queue Configuration (Optional)

For better performance with many recipients, use queue workers:

1. Update `.env`:
```env
QUEUE_CONNECTION=database
```

2. Create queue table:
```bash
php artisan queue:table
php artisan migrate
```

3. Run queue worker:
```bash
php artisan queue:work
```

This sends emails in the background without delaying the announcement creation.

## 🎯 Production Recommendations

1. **Use a professional email service** (SendGrid, Mailgun, SES)
2. **Set up queue workers** for better performance
3. **Use your church's domain** for MAIL_FROM_ADDRESS
4. **Monitor email deliverability**
5. **Keep email logs** for troubleshooting

## 📞 Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify email configuration in `.env`
3. Test with Mailtrap first
4. Contact your hosting provider for SMTP support
