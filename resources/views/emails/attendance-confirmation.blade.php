<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .email-header .icon {
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 20px;
        }
        .confirmation-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 25px;
        }
        .confirmation-badge .checkmark {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .confirmation-badge h2 {
            margin: 0 0 5px;
            font-size: 20px;
        }
        .confirmation-badge p {
            margin: 0;
            font-size: 14px;
            opacity: 0.95;
        }
        .member-info {
            background-color: #f9fafb;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .member-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .member-info strong {
            color: #374151;
            display: inline-block;
            min-width: 120px;
        }
        .event-details {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .event-details h3 {
            margin: 0 0 15px;
            color: #1e40af;
            font-size: 18px;
            display: flex;
            align-items: center;
        }
        .event-details h3::before {
            content: '📅';
            margin-right: 8px;
        }
        .event-info-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .event-info-row .icon {
            width: 30px;
            flex-shrink: 0;
            color: #3b82f6;
        }
        .event-info-row .content {
            flex: 1;
            color: #1e40af;
        }
        .attendance-details {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .attendance-details h4 {
            margin: 0 0 10px;
            color: #15803d;
            font-size: 16px;
        }
        .attendance-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #166534;
        }
        .message-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .message-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
            text-align: center;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .church-name {
            font-weight: 700;
            color: #10b981;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">✓</div>
            <h1>Attendance Confirmed!</h1>
            <p>Thank you for attending</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Confirmation Badge -->
            <div class="confirmation-badge">
                <div class="checkmark">✓</div>
                <h2>Your Attendance Has Been Recorded</h2>
                <p>{{ $attendance->checked_in_at->format('l, F j, Y \a\t g:i A') }}</p>
            </div>

            <!-- Member Information -->
            <div class="member-info">
                <p><strong>Name:</strong> {{ $member->full_name }}</p>
                @if($member->email)
                    <p><strong>Email:</strong> {{ $member->email }}</p>
                @endif
                @if($member->phone)
                    <p><strong>Phone:</strong> {{ $member->phone }}</p>
                @endif
                @if($member->member_id)
                    <p><strong>Member ID:</strong> {{ $member->member_id }}</p>
                @endif
            </div>

            <!-- Event Details -->
            <div class="event-details">
                <h3>{{ $event->title }}</h3>
                
                <div class="event-info-row">
                    <div class="icon">📅</div>
                    <div class="content">
                        <strong>Date:</strong> {{ $event->start_datetime->format('l, F j, Y') }}
                    </div>
                </div>

                <div class="event-info-row">
                    <div class="icon">🕐</div>
                    <div class="content">
                        <strong>Time:</strong> {{ $event->start_datetime->format('g:i A') }}
                        @if($event->end_datetime)
                            - {{ $event->end_datetime->format('g:i A') }}
                        @endif
                    </div>
                </div>

                @if($event->location)
                    <div class="event-info-row">
                        <div class="icon">📍</div>
                        <div class="content">
                            <strong>Location:</strong> {{ $event->location }}
                        </div>
                    </div>
                @endif

                @if($event->description)
                    <div class="divider"></div>
                    <p style="margin: 10px 0 0; color: #1e40af; font-size: 14px;">
                        {{ Str::limit($event->description, 150) }}
                    </p>
                @endif
            </div>

            <!-- Attendance Details -->
            <div class="attendance-details">
                <h4>Attendance Information</h4>
                <p><strong>Check-in Time:</strong> {{ $attendance->checked_in_at->format('g:i A') }}</p>
                <p><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $attendance->attendance_method)) }}</p>
                @if($attendance->notes)
                    <p><strong>Note:</strong> {{ $attendance->notes }}</p>
                @endif
            </div>

            <!-- Welcome Message for New Members/Guests -->
            @if(in_array($member->membership_type, ['visitor', 'friend']) || $member->approval_status === 'pending')
                <div class="message-box">
                    <p>
                        <strong>👋 Welcome!</strong> 
                        @if($member->membership_type === 'visitor')
                            We're so glad you joined us today! If you have any questions or would like to know more about our church, please don't hesitate to reach out.
                        @else
                            Thank you for being part of our community. We look forward to seeing you again!
                        @endif
                    </p>
                </div>
            @endif

            <!-- Call to Action -->
            <center>
                @if($event->event_url)
                    <a href="{{ $event->event_url }}" class="cta-button">
                        View Event Details
                    </a>
                @endif
            </center>

            <!-- Additional Info -->
            <p style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 25px;">
                If you have any questions about this event or your attendance, 
                please contact the church office.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p class="church-name">{{ $churchName }}</p>
            <p>You received this email because your attendance was marked for our event.</p>
            @php
                $churchEmail = \App\Models\Setting::getValue('system_email', 'system', null);
                $churchPhone = \App\Models\Setting::getValue('organization_phone', 'general', null);
            @endphp
            @if($churchEmail || $churchPhone)
                <p style="margin-top: 10px;">
                    @if($churchEmail)
                        <span>✉ {{ $churchEmail }}</span>
                    @endif
                    @if($churchPhone)
                        <span style="margin-left: 15px;">📞 {{ $churchPhone }}</span>
                    @endif
                </p>
            @endif
            <p style="font-size: 12px; color: #9ca3af; margin-top: 15px;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
