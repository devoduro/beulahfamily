<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Member Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .alert-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .alert-box strong {
            color: #856404;
            display: block;
            margin-bottom: 5px;
        }
        .alert-box p {
            color: #856404;
            margin: 0;
            font-size: 14px;
        }
        .member-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .member-details h3 {
            margin: 0 0 20px;
            color: #667eea;
            font-size: 18px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #212529;
            flex-grow: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 14px 32px;
            margin: 8px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .btn-approve {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-view {
            background: #6c757d;
            color: white;
        }
        .btn-view:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            color: #0c5460;
            font-size: 14px;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .header {
                padding: 30px 20px;
            }
            .content {
                padding: 25px 20px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                margin-bottom: 5px;
            }
            .btn {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 New Member Registration</h1>
            <p>A new member has registered and is awaiting approval</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <strong>⚠️ Action Required</strong>
                <p>Please review and approve this member registration to grant them access to the system.</p>
            </div>
            
            <div class="member-details">
                <h3>Member Information</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value"><strong>{{ $member->first_name }} {{ $member->middle_name }} {{ $member->last_name }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $member->email }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $member->phone }}</span>
                </div>
                
                @if($member->whatsapp_phone)
                <div class="detail-row">
                    <span class="detail-label">WhatsApp:</span>
                    <span class="detail-value">{{ $member->whatsapp_phone }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Gender:</span>
                    <span class="detail-value">{{ ucfirst($member->gender) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date of Birth:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('F d, Y') }} ({{ \Carbon\Carbon::parse($member->date_of_birth)->age }} years old)</span>
                </div>
                
                @if($member->marital_status)
                <div class="detail-row">
                    <span class="detail-label">Marital Status:</span>
                    <span class="detail-value">{{ ucfirst($member->marital_status) }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Chapter:</span>
                    <span class="detail-value"><strong>{{ $member->chapter }}</strong></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Membership Type:</span>
                    <span class="detail-value">{{ ucfirst($member->membership_type) }}</span>
                </div>
                
                @if($member->occupation)
                <div class="detail-row">
                    <span class="detail-label">Occupation:</span>
                    <span class="detail-value">{{ $member->occupation }}</span>
                </div>
                @endif
                
                @if($member->address)
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $member->address }}@if($member->city), {{ $member->city }}@endif</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Registration Date:</span>
                    <span class="detail-value">{{ $member->created_at->format('F d, Y \a\t g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="status-badge status-pending">⏳ Pending Approval</span>
                    </span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('members.pending-approvals') }}" class="btn btn-approve">
                    ✅ Review & Approve
                </a>
                <a href="{{ route('members.show', $member->id) }}" class="btn btn-view">
                    👁️ View Full Profile
                </a>
            </div>
            
            <div class="info-box">
                <p>
                    <strong>📧 Member Notification:</strong> The member has been sent a welcome email with their login credentials. 
                    They will receive another notification once you approve their registration.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Beulah Family Management System</strong></p>
            <p>Building Lives, Transforming Communities</p>
            <p style="margin-top: 15px;">
                <a href="{{ route('dashboard') }}">Dashboard</a> | 
                <a href="{{ route('members.index') }}">Members</a> | 
                <a href="{{ route('members.pending-approvals') }}">Pending Approvals</a>
            </p>
            <p style="margin-top: 15px; color: #adb5bd;">
                This is an automated notification. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
