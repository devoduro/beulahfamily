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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            color: #dc3545;
            margin-top: 0;
        }
        .info-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .contact-box {
            background: #d1ecf1;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
            <h1>Registration Status Update</h1>
        </div>
        
        <div class="email-body">
            <h2>Member Registration Status</h2>
            
            <p>Dear {{ $member->first_name }} {{ $member->last_name }},</p>
            
            <p>Thank you for your interest in joining {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}.</p>
            
            <div class="info-box">
                <p>After careful review, we regret to inform you that we are unable to approve your member registration at this time.</p>
                
                @if($reason)
                <p><strong>Reason:</strong></p>
                <p>{{ $reason }}</p>
                @endif
            </div>
            
            <div class="contact-box">
                <strong>📞 Need More Information?</strong>
                <p>If you have questions about this decision or would like to discuss your application further, please don't hesitate to contact our church office.</p>
                <p>We're here to help and would be happy to address any concerns you may have.</p>
            </div>
            
            <p><strong>Contact Information:</strong></p>
            <ul>
                <li><strong>Email:</strong> {{ \App\Models\Setting::getValue('organization_email', 'general', 'info@church.org') }}</li>
                <li><strong>Phone:</strong> {{ \App\Models\Setting::getValue('organization_phone', 'general', 'N/A') }}</li>
            </ul>
            
            <p>We appreciate your understanding and encourage you to reach out if you need clarification or wish to reapply in the future.</p>
            
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
