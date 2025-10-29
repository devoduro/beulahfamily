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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            color: #28a745;
            margin-top: 0;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        }
        .login-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            margin: 25px 0;
            border-radius: 8px;
            text-align: center;
        }
        .credentials-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
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
            <h1>🎉 Congratulations!</h1>
        </div>
        
        <div class="email-body">
            <h2>Your Registration Has Been Approved!</h2>
            
            <p>Dear {{ $member->first_name }} {{ $member->last_name }},</p>
            
            <p>We're delighted to inform you that your member registration has been approved! Welcome to the {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }} family!</p>
            
            <div class="success-box">
                <strong>✅ Your account is now active!</strong>
                <p>You can now login to access your member portal and enjoy all the benefits of membership.</p>
            </div>

            <div class="login-box">
                <h2 style="color: white; margin-top: 0; font-size: 24px;">🔐 Login Now to Get Started!</h2>
                <p style="font-size: 16px; margin: 15px 0;">Click the button below to access your member portal</p>
                <a href="{{ route('member.login') }}" class="button" style="display: inline-block; margin: 15px 0;">
                    🚀 Login to Your Member Portal
                </a>
                <p style="font-size: 14px; margin-top: 15px; opacity: 0.9;">
                    <strong>Login URL:</strong><br>
                    <a href="{{ route('member.login') }}" style="color: white; text-decoration: underline;">{{ route('member.login') }}</a>
                </p>
            </div>

            <div class="credentials-box">
                <h3 style="margin-top: 0; color: #856404;">📋 Your Login Credentials</h3>
                <p style="margin: 10px 0;"><strong>Member ID:</strong> {{ $member->member_id }}</p>
                <p style="margin: 10px 0;"><strong>Email:</strong> {{ $member->email }}</p>
                <p style="margin: 10px 0; font-size: 16px;"><strong>Password:</strong> <span style="background: white; padding: 5px 10px; border-radius: 4px; font-family: monospace; color: #d63384; font-size: 18px; font-weight: bold;">{{ $password }}</span></p>
                <p style="color: #dc3545; font-size: 14px; margin-top: 15px; background: #fff3cd; padding: 12px; border-radius: 4px; border: 1px solid #ffc107;">
                    <strong>⚠️ IMPORTANT - Keep this password safe!</strong><br>
                    You will be required to change this password when you login for the first time.
                </p>
            </div>
            
            <p><strong>🎯 What you can do in your member portal:</strong></p>
            <ul style="line-height: 1.8;">
                <li>✅ View and update your profile information</li>
                <li>📅 Access upcoming events and programs</li>
                <li>💰 Make donations securely online</li>
                <li>🙏 Join ministries and volunteer opportunities</li>
                <li>👥 Connect with Beulah family</li>
                <li>📖 Access exclusive member content</li>
            </ul>
            
            <p>If you have any questions or need assistance logging in, please feel free to contact us at {{ \App\Models\Setting::getValue('organization_email', 'general', 'info@church.org') }} or {{ \App\Models\Setting::getValue('organization_phone', 'general', 'N/A') }}.</p>
            
            <p>We look forward to your active participation in our church community!</p>
            
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
