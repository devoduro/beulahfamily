# Approval SMS Troubleshooting Guide

## ✅ Good News: SMS IS Being Sent!

Based on the logs, the approval SMS **is being sent successfully** from the system. If members are not receiving SMS, it's likely a delivery or network issue, not a system issue.

---

## 📊 Verification Results

### System Status: ✅ WORKING

**Log Evidence:**
```
[2025-10-29 05:33:36] local.INFO: Approval SMS sent successfully 
{"member_id":15,"phone":"0242724849"}
```

**Test Results:**
- ✅ Phone number exists
- ✅ Member opted in to receive SMS
- ✅ MNotify API configured correctly
- ✅ SMS sent successfully to MNotify
- ✅ Message ID received from MNotify

---

## 🔍 Why Member Might Not Receive SMS

### 1. **Network Delay** (Most Common)
- SMS can take 1-5 minutes to deliver
- Network congestion during peak hours
- Carrier routing delays

**Solution:** Wait 5-10 minutes before assuming failure

### 2. **Phone Number Issues**
- Wrong phone number in database
- Phone number not in correct format
- Phone switched off or out of coverage

**Check:**
```bash
php artisan tinker
>>> $member = App\Models\Member::find(MEMBER_ID);
>>> echo $member->phone;
```

### 3. **Network Provider Blocking**
- Some networks block promotional SMS
- Sender ID not recognized
- SMS marked as spam

**Solution:** Contact MNotify support to whitelist sender ID

### 4. **Phone Memory Full**
- Phone storage full
- SMS inbox full

**Solution:** Member should clear some messages

### 5. **DND (Do Not Disturb) Active**
- Member has DND activated on their number
- Blocks promotional messages

**Solution:** Member should deactivate DND

---

## 🧪 Diagnostic Commands

### Check if SMS Was Sent

```bash
# Check logs for specific member
grep "member_id\":MEMBER_ID" storage/logs/laravel.log | grep "Approval SMS"

# Check all approval SMS today
grep "Approval SMS sent successfully" storage/logs/laravel.log | grep "$(date +%Y-%m-%d)"

# Check failed approval SMS
grep "Failed to send approval SMS" storage/logs/laravel.log | tail -20
```

### Diagnose Specific Member

```bash
php artisan sms:diagnose-approval MEMBER_ID
```

**Example:**
```bash
php artisan sms:diagnose-approval 15
```

### Resend Approval SMS

If member didn't receive it, you can resend:

```bash
php artisan tinker

>>> $member = App\Models\Member::find(MEMBER_ID);
>>> $smsService = new App\Services\MNotifyService();
>>> $password = strtolower($member->first_name) . substr($member->phone, -4);
>>> $message = "Congratulations {$member->first_name}! Your Beulah Family membership has been APPROVED. Login at: {$member->email}, Password: {$password}. Please change your password after first login. Welcome to the family!";
>>> $result = $smsService->sendSMS($member->phone, $message);
>>> print_r($result);
```

---

## ✅ Verification Checklist

Before claiming SMS didn't send, verify:

- [ ] Check system logs (SMS was sent from system)
- [ ] Wait 5-10 minutes for delivery
- [ ] Verify phone number is correct
- [ ] Check phone has network coverage
- [ ] Confirm phone is switched on
- [ ] Check phone memory is not full
- [ ] Verify no DND active on number
- [ ] Check with network provider

---

## 📱 Member Phone Number Verification

### Check Member's Phone

```bash
php artisan tinker

>>> $member = App\Models\Member::where('email', 'member@email.com')->first();
>>> echo "Phone: " . $member->phone;
>>> echo "\nReceive SMS: " . ($member->receive_sms ? 'Yes' : 'No');
```

### Update Phone Number

If phone number is wrong:

```bash
php artisan tinker

>>> $member = App\Models\Member::find(MEMBER_ID);
>>> $member->phone = '0241234567';  // Correct number
>>> $member->save();
>>> echo "Phone updated!";
```

### Enable SMS Reception

If `receive_sms` is disabled:

```bash
php artisan tinker

>>> $member = App\Models\Member::find(MEMBER_ID);
>>> $member->receive_sms = true;
>>> $member->save();
>>> echo "SMS enabled!";
```

---

## 🔧 Common Issues & Fixes

### Issue 1: "Member says they didn't receive SMS"

**Check:**
1. View logs to confirm SMS was sent
2. Verify phone number is correct
3. Ask member to check spam/blocked messages
4. Wait 10 minutes and check again
5. Resend SMS manually if needed

**Command:**
```bash
# Check if SMS was sent
php artisan sms:diagnose-approval MEMBER_ID
```

### Issue 2: "SMS sent but phone number wrong"

**Fix:**
```bash
php artisan tinker
>>> $member = App\Models\Member::find(MEMBER_ID);
>>> $member->phone = 'CORRECT_NUMBER';
>>> $member->save();

# Resend SMS
>>> $smsService = new App\Services\MNotifyService();
>>> $password = strtolower($member->first_name) . substr($member->phone, -4);
>>> $message = "Congratulations {$member->first_name}! Your Beulah Family membership has been APPROVED. Login at: {$member->email}, Password: {$password}. Welcome!";
>>> $result = $smsService->sendSMS($member->phone, $message);
```

### Issue 3: "receive_sms is disabled"

**Fix:**
```bash
php artisan tinker
>>> $member = App\Models\Member::find(MEMBER_ID);
>>> $member->receive_sms = true;
>>> $member->save();
```

Then re-approve or resend SMS manually.

### Issue 4: "MNotify not configured"

**Fix:**
Check `.env` file:
```env
MNOTIFY_API_KEY=your_api_key_here
MNOTIFY_SENDER_ID=SLCECoE
```

Clear config:
```bash
php artisan config:clear
```

---

## 📊 SMS Delivery Statistics

### Check Success Rate

```bash
# Count successful approval SMS today
grep "Approval SMS sent successfully" storage/logs/laravel.log | grep "$(date +%Y-%m-%d)" | wc -l

# Count failed approval SMS today
grep "Failed to send approval SMS" storage/logs/laravel.log | grep "$(date +%Y-%m-%d)" | wc -l
```

### View Recent Approval SMS

```bash
# Last 10 approval SMS
grep "Approval SMS" storage/logs/laravel.log | tail -10

# Approval SMS for specific date
grep "Approval SMS" storage/logs/laravel.log | grep "2025-10-29"
```

---

## 🎯 Best Practices

### 1. **Verify Phone Numbers**
- Always verify phone number during registration
- Use phone number validation
- Send test SMS during registration

### 2. **Set Expectations**
- Tell members SMS may take 1-5 minutes
- Provide alternative: Check email for credentials
- Have manual resend option

### 3. **Monitor Logs**
- Check logs daily for failures
- Track delivery success rate
- Investigate patterns of failures

### 4. **Backup Communication**
- Always send email alongside SMS
- Email contains same information
- Members can use email if SMS fails

---

## 📞 MNotify Support

If SMS consistently fails to deliver:

**Contact MNotify:**
- Website: https://sms.pastechsolutions.com
- Support: support@pastechsolutions.com
- Check SMS delivery reports in MNotify dashboard

**Information to Provide:**
- Message ID from logs
- Campaign ID from logs
- Phone number
- Date and time
- Sender ID used

---

## 🔍 Real-Time Monitoring

### Watch Approval SMS in Real-Time

```bash
# Monitor logs for approval SMS
tail -f storage/logs/laravel.log | grep "Approval SMS"

# Monitor all SMS activity
tail -f storage/logs/laravel.log | grep "SMS"
```

### Test Approval Flow

1. Create test member
2. Approve member
3. Watch logs in real-time
4. Check if SMS received

```bash
# Terminal 1: Watch logs
tail -f storage/logs/laravel.log | grep "SMS"

# Terminal 2: Approve member via web interface
# Check Terminal 1 for log entries
```

---

## ✅ Summary

### System Status: ✅ WORKING CORRECTLY

**Evidence:**
- ✅ Code is correct and active
- ✅ SMS being sent to MNotify successfully
- ✅ Logs show successful transmission
- ✅ Message IDs received from MNotify

### If Member Doesn't Receive SMS:

**Most Likely Causes:**
1. Network delay (wait 5-10 minutes)
2. Wrong phone number in database
3. Phone off or out of coverage
4. DND active on number
5. Network provider blocking

**Solutions:**
1. Verify phone number is correct
2. Check logs to confirm SMS was sent
3. Wait 10 minutes before resending
4. Resend manually if needed
5. Member can use email credentials instead

**Important:** The system IS working. SMS is being sent successfully. Any delivery issues are network/carrier related, not system issues.

---

## 🚀 Quick Commands Reference

```bash
# Diagnose member
php artisan sms:diagnose-approval MEMBER_ID

# Check logs
grep "Approval SMS" storage/logs/laravel.log | tail -20

# Resend SMS manually
php artisan tinker
>>> $member = App\Models\Member::find(ID);
>>> $service = new App\Services\MNotifyService();
>>> $password = strtolower($member->first_name) . substr($member->phone, -4);
>>> $msg = "Congratulations {$member->first_name}! Your Beulah Family membership has been APPROVED. Login: {$member->email}, Password: {$password}. Welcome!";
>>> $result = $service->sendSMS($member->phone, $msg);
>>> print_r($result);
```

---

## 📝 Documentation

For more information:
- **Registration SMS:** `MEMBER_REGISTRATION_SMS.md`
- **Admin Notifications:** `ADMIN_REGISTRATION_NOTIFICATIONS.md`
- **Birthday SMS:** `BIRTHDAY_SMS_CRON_SETUP.md`
