<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $event->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: white;
            color: black;
            padding: 20px;
        }
        
        .print-container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            page-break-inside: avoid;
        }
        
        .event-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #000;
        }
        
        .event-details {
            font-size: 18px;
            margin-bottom: 30px;
            color: #333;
            line-height: 1.4;
        }
        
        .qr-container {
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #000;
            display: inline-block;
            background: white;
        }
        
        .qr-container img {
            width: 350px;
            height: 350px;
            display: block;
        }
        
        .scan-instruction {
            font-size: 22px;
            font-weight: bold;
            margin-top: 30px;
            color: #000;
        }
        
        .footer-info {
            margin-top: 40px;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15mm;
            }
            
            @page {
                margin: 15mm;
                size: A4 portrait;
            }
            
            .print-container {
                max-width: none;
                width: 100%;
            }
            
            .qr-container img {
                width: 300px;
                height: 300px;
            }
        }
        
        @media screen {
            body {
                background: #f5f5f5;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .print-container {
                background: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                padding: 40px;
            }
            
            .print-button {
                position: fixed;
                top: 20px;
                right: 20px;
                background: #3b82f6;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                font-size: 16px;
                cursor: pointer;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .print-button:hover {
                background: #2563eb;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Print QR Code
    </button>
    
    <div class="print-container">
        <div class="event-title">{{ $event->title }}</div>
        
        <div class="event-details">
            <div>{{ $event->start_datetime->format('l, F j, Y') }}</div>
            <div>{{ $event->start_datetime->format('g:i A') }}</div>
            @if($event->location)
                <div style="margin-top: 10px;">📍 {{ $event->location }}</div>
            @endif
        </div>
        
        @if($eventQr)
            <div class="qr-container">
                <img src="{{ asset('storage/' . $eventQr->qr_code_path) }}" 
                     alt="QR Code for {{ $event->title }}">
            </div>
            
            <div class="scan-instruction">
                Scan this QR code with your phone<br>
                to mark your attendance
            </div>
            
            <div class="footer-info">
                <div><strong>Event ID:</strong> {{ $event->id }}</div>
                <div><strong>Generated:</strong> {{ now()->format('M j, Y g:i A') }}</div>
                @if($eventQr->expires_at)
                    <div><strong>Expires:</strong> {{ $eventQr->expires_at->format('M j, Y g:i A') }}</div>
                @endif
            </div>
        @else
            <div style="padding: 60px; color: #666;">
                <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
                <div style="font-size: 18px;">No QR code available for this event</div>
            </div>
        @endif
    </div>
    
    <script>
        // Auto-print when print parameter is present
        if (window.location.search.includes('autoprint=1')) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>
