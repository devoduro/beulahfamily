<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .email-body h2 {
            color: #667eea;
            margin-top: 0;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credentials {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .credentials p {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Welcome to {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}</h1>
        </div>
        
        <div class="email-body">
            <h2>Registration Received!</h2>
            
            <p>Dear {{ $member->first_name }} {{ $member->last_name }},</p>
            
            <p>Thank you for registering as a member of {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}. We're excited to have you join Beulah family!</p>
            
            <div class="info-box">
                <strong>⏳ Your Registration Status: Pending Approval</strong>
                <p>Your registration has been submitted successfully and is currently pending review by our administrators. You will receive another email once your account has been approved.</p>
            </div>
            
            <div class="credentials">
                <strong>🔐 Your Login Credentials:</strong>
                <p><strong>Member ID:</strong> {{ $member->member_id }}</p>
                <p><strong>Email:</strong> {{ $member->email }}</p>
                <p><strong>Default Password:</strong> {{ $password }}</p>
                <p style="color: #dc3545; margin-top: 10px;">
                    <strong>⚠️ Important:</strong> Keep this password safe. You will be required to change it on your first login.
                </p>
            </div>
            
            <p><strong>What happens next?</strong></p>
            <ul>
                <li>Our administrators will review your registration</li>
                <li>You'll receive an email notification once approved</li>
                <li>After approval, you can login with your credentials</li>
                <li>You'll be prompted to change your password on first login</li>
            </ul>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
            
            <p>God bless you!</p>
            
            <p><strong>{{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }} Team</strong></p>
        </div>
        
        <div class="email-footer">
            <p>© {{ date('Y') }} {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}. All rights reserved.</p>
            <p>{{ \App\Models\Setting::getValue('organization_email', 'general', 'info@church.org') }}</p>
        </div>
    </div>
</body>
</html>
