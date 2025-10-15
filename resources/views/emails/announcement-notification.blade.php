<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .priority-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .priority-urgent {
            background-color: #ef4444;
            color: #ffffff;
        }
        .priority-high {
            background-color: #f59e0b;
            color: #ffffff;
        }
        .priority-medium {
            background-color: #3b82f6;
            color: #ffffff;
        }
        .priority-low {
            background-color: #6b7280;
            color: #ffffff;
        }
        .email-body {
            padding: 30px 20px;
        }
        .announcement-meta {
            background-color: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .announcement-meta p {
            margin: 5px 0;
            font-size: 14px;
            color: #6b7280;
        }
        .announcement-meta strong {
            color: #374151;
        }
        .announcement-content {
            font-size: 16px;
            line-height: 1.8;
            color: #374151;
            margin-bottom: 20px;
        }
        .announcement-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }
        .target-audience {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .target-audience p {
            margin: 0;
            color: #1e40af;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
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
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            @if($announcement->priority === 'urgent' || $announcement->priority === 'high')
                <div class="priority-badge priority-{{ $announcement->priority }}">
                    {{ strtoupper($announcement->priority) }} PRIORITY
                </div>
            @endif
            <h1>{{ $announcement->title }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Meta Information -->
            <div class="announcement-meta">
                <p><strong>Category:</strong> {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}</p>
                <p><strong>Published:</strong> {{ \Carbon\Carbon::parse($announcement->publish_date)->format('F j, Y g:i A') }}</p>
                @if($announcement->expire_date)
                    <p><strong>Valid Until:</strong> {{ \Carbon\Carbon::parse($announcement->expire_date)->format('F j, Y') }}</p>
                @endif
            </div>

            <!-- Target Audience -->
            @if($announcement->target_audience && is_array($announcement->target_audience) && count($announcement->target_audience) > 0)
                <div class="target-audience">
                    <p><strong>📢 This message is for:</strong> {{ implode(', ', array_map('ucfirst', $announcement->target_audience)) }}</p>
                </div>
            @endif

            <!-- Image -->
            @if($announcement->image_path)
                <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="announcement-image">
            @endif

            <!-- Content -->
            <div class="announcement-content">
                {!! nl2br(e($announcement->content)) !!}
            </div>

            <!-- Call to Action -->
            <center>
                <a href="{{ route('announcements.show', $announcement->id) }}" class="cta-button">
                    View Full Announcement
                </a>
            </center>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p class="church-name">{{ $churchName }}</p>
            <p>You received this email because you are a member of our church community.</p>
            <p style="font-size: 12px; color: #9ca3af; margin-top: 15px;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
