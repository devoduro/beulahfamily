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
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
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
            color: #ffc107;
            margin-top: 0;
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
        .warning-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
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
            <h1>🔐 Password Reset</h1>
        </div>
        
        <div class="email-body">
            <h2>Your Password Has Been Reset</h2>
            
            <p>Dear {{ $member->first_name }} {{ $member->last_name }},</p>
            
            <p>Your password has been reset by an administrator at {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}.</p>
            
            <div class="credentials">
                <strong>🔐 Your New Login Credentials:</strong>
                <p><strong>Member ID:</strong> {{ $member->member_id }}</p>
                <p><strong>Email:</strong> {{ $member->email }}</p>
                <p><strong>New Password:</strong> {{ $password }}</p>
            </div>
            
            <div class="warning-box">
                <strong>⚠️ Important Security Notice:</strong>
                <p>For your security, you will be required to change this password when you next login. Please choose a strong, unique password that you haven't used before.</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('member.login') }}" class="button">Login Now</a>
            </div>
            
            <p><strong>Password Security Tips:</strong></p>
            <ul>
                <li>Use a combination of uppercase and lowercase letters</li>
                <li>Include numbers and special characters</li>
                <li>Make it at least 8 characters long</li>
                <li>Don't use easily guessable information</li>
                <li>Never share your password with anyone</li>
            </ul>
            
            <p><strong>Did not request a password reset?</strong></p>
            <p>If you did not request this password reset or believe this was done in error, please contact our church office immediately at {{ \App\Models\Setting::getValue('organization_email', 'general', 'info@church.org') }}</p>
            
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
