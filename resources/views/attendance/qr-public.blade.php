<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - Attendance QR Code</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white !important; }
        }
        
        .print-only { display: none; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b no-print">
        <div class="max-w-4xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-qrcode text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Event Attendance</h1>
                        <p class="text-sm text-gray-500">Scan QR Code to Mark Attendance</p>
                    </div>
                </div>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-print mr-2"></i>
                    Print QR
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Event Info -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h2>
            <div class="text-lg text-gray-600 space-y-2">
                <div class="flex items-center justify-center space-x-2">
                    <i class="fas fa-calendar text-blue-600"></i>
                    <span>{{ $event->start_datetime->format('l, F j, Y') }}</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                    <i class="fas fa-clock text-blue-600"></i>
                    <span>{{ $event->start_datetime->format('g:i A') }}</span>
                </div>
                @if($event->location)
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-map-marker-alt text-blue-600"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center">
            @if($eventQr)
                <div class="space-y-6">
                    <h3 class="text-2xl font-semibold text-gray-900">Scan to Mark Your Attendance</h3>
                    
                    <!-- QR Code Display -->
                    <div class="flex justify-center">
                        <div class="bg-white p-6 rounded-2xl border-2 border-gray-200 shadow-lg">
                            <img src="{{ asset('storage/' . $eventQr->qr_code_path) }}" 
                                 alt="QR Code for {{ $event->title }}" 
                                 class="w-80 h-80 mx-auto">
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="bg-blue-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-blue-900 mb-4">How to Mark Attendance</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-mobile-alt text-white"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900 mb-2">Open Camera</h5>
                                <p class="text-gray-600">Use your phone's camera or QR scanner app</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-qrcode text-white"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900 mb-2">Scan QR Code</h5>
                                <p class="text-gray-600">Point your camera at the QR code above</p>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <h5 class="font-semibold text-gray-900 mb-2">Complete Form</h5>
                                <p class="text-gray-600">Follow the link and select your name</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- QR Info -->
                    <div class="text-sm text-gray-500 space-y-1">
                        <div>Event ID: {{ $event->id }}</div>
                        @if($eventQr->expires_at)
                            <div>QR Code expires: {{ $eventQr->expires_at->format('M j, Y g:i A') }}</div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No QR Code Available -->
                <div class="py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">QR Code Not Available</h3>
                    <p class="text-gray-600 mb-6">No QR code has been generated for this event yet.</p>
                    <p class="text-sm text-gray-500">Please contact the event organizer to generate a QR code for attendance.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-500 no-print">
            <p>Need help? Contact the event organizer or church administration.</p>
        </div>
    </div>

    <!-- Print-only content -->
    <div class="print-only">
        <div style="text-align: center; padding: 20px;">
            <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 10px;">{{ $event->title }}</h1>
            <p style="font-size: 16px; margin-bottom: 20px;">
                {{ $event->start_datetime->format('l, F j, Y \a\t g:i A') }}
                @if($event->location) - {{ $event->location }} @endif
            </p>
            @if($eventQr)
                <div style="margin: 30px 0;">
                    <img src="{{ asset('storage/' . $eventQr->qr_code_path) }}" 
                         alt="QR Code" 
                         style="width: 300px; height: 300px; margin: 0 auto; display: block; border: 2px solid #000;">
                </div>
                <p style="font-size: 18px; font-weight: bold; margin-top: 20px;">
                    Scan this QR code to mark your attendance
                </p>
            @endif
        </div>
    </div>

    <script>
        // Auto-refresh QR code every 5 minutes to check for updates
        setInterval(function() {
            if (!document.hidden) {
                location.reload();
            }
        }, 300000); // 5 minutes
    </script>
</body>
</html>
