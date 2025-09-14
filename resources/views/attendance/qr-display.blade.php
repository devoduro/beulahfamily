@extends('components.app-layout')

@section('title', 'QR Code - ' . $event->title)
@section('subtitle', 'Attendance QR Code')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="text-center">
        <div class="flex items-center justify-center space-x-3 mb-4">
            <a href="{{ route('attendance.show', $event) }}" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">{{ $event->title }}</h1>
        </div>
        <p class="text-lg text-gray-600">{{ $event->start_datetime->format('l, F j, Y \a\t g:i A') }}</p>
        @if($event->location)
            <p class="text-gray-500 mt-2">
                <i class="fas fa-map-marker-alt mr-1"></i>
                {{ $event->location }}
            </p>
        @endif
    </div>

    <!-- QR Code Display -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="text-center">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Scan to Mark Attendance</h2>
            
            @if($eventQr)
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-8 mb-6">
                    <div class="bg-white rounded-xl p-6 inline-block shadow-lg">
                        <img src="{{ asset('storage/' . $eventQr->qr_code_path) }}" 
                             alt="QR Code for {{ $event->title }}" 
                             class="w-64 h-64 mx-auto">
                    </div>
                </div>
                
                <!-- QR Code Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-2xl font-bold text-blue-600">{{ $eventQr->scan_count }}</div>
                        <div class="text-sm text-gray-600">Total Scans</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-lg font-semibold text-green-600">
                            @if($eventQr->expires_at)
                                {{ $eventQr->expires_at->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Expires</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-lg font-semibold {{ $eventQr->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $eventQr->is_active ? 'Active' : 'Inactive' }}
                        </div>
                        <div class="text-sm text-gray-600">Status</div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-center space-x-4">
                    <button onclick="generateNewQr()" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Generate New QR
                    </button>
                    @if($eventQr->is_active)
                        <button onclick="deactivateQr({{ $eventQr->id }})" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors">
                            <i class="fas fa-stop mr-2"></i>
                            Deactivate QR
                        </button>
                    @endif
                    <button onclick="printQr()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        Print QR Code
                    </button>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-qrcode text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium mb-4">No QR code generated yet</p>
                    <button onclick="generateNewQr()" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Generate QR Code
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Use</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-white font-bold">1</span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Display QR Code</h4>
                <p class="text-sm text-gray-600">Show this QR code on a screen or print it for members to scan</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-white font-bold">2</span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Members Scan</h4>
                <p class="text-sm text-gray-600">Members use their phone camera or QR scanner app to scan the code</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-white font-bold">3</span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Automatic Check-in</h4>
                <p class="text-sm text-gray-600">Attendance is automatically recorded when members complete the scan</p>
            </div>
        </div>
    </div>

    <!-- Recent Scans -->
    @if($eventQr && $eventQr->scan_logs)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Recent Scans</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach(array_slice(array_reverse($eventQr->scan_logs), 0, 10) as $log)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 rounded-full {{ $log['success'] ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $log['member_info']['name'] ?? 'Unknown Member' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log['timestamp'])->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full {{ $log['success'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $log['success'] ? 'Success' : 'Failed' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- QR Generation Modal -->
<div id="qrGenerationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Generate New QR Code</h3>
                <button onclick="closeQrModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="qrGenerationForm" class="space-y-4">
                @csrf
                <div>
                    <label for="expiration_hours" class="block text-sm font-medium text-gray-700 mb-2">Expiration (Hours)</label>
                    <select id="expiration_hours" name="expiration_hours" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="24">24 Hours</option>
                        <option value="12">12 Hours</option>
                        <option value="6">6 Hours</option>
                        <option value="3">3 Hours</option>
                        <option value="">Never Expires</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeQrModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Generate QR Code
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateNewQr() {
    document.getElementById('qrGenerationModal').classList.remove('hidden');
}

function closeQrModal() {
    document.getElementById('qrGenerationModal').classList.add('hidden');
}

document.getElementById('qrGenerationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`{{ route('attendance.qr.generate', $event) }}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating QR code');
    });
});

function deactivateQr(qrId) {
    if (confirm('Are you sure you want to deactivate this QR code?')) {
        fetch(`/attendance/qr/${qrId}/deactivate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deactivating QR code');
        });
    }
}

function printQr() {
    window.print();
}

// Print styles
const printStyles = `
    @media print {
        body * {
            visibility: hidden;
        }
        .bg-white.rounded-2xl.shadow-lg.border.border-gray-100.p-8,
        .bg-white.rounded-2xl.shadow-lg.border.border-gray-100.p-8 * {
            visibility: visible;
        }
        .bg-white.rounded-2xl.shadow-lg.border.border-gray-100.p-8 {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
`;

const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = printStyles;
document.head.appendChild(styleSheet);
</script>
@endpush
@endsection
