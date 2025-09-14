@extends('components.app-layout')

@section('title', $event->title)
@section('subtitle', 'Event Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('events.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : 
                       ($event->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                        ($event->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
            <div class="flex items-center space-x-4 text-sm text-gray-600">
                <span>
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $event->start_datetime->format('M j, Y') }}
                </span>
                <span>
                    <i class="fas fa-clock mr-1"></i>
                    {{ $event->start_datetime->format('g:i A') }} - {{ $event->end_datetime->format('g:i A') }}
                </span>
                @if($event->location)
                    <span>
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $event->location }}
                    </span>
                @endif
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            @can('update', $event)
                <a href="{{ route('events.edit', $event) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Event
                </a>
            @endcan
            
            <a href="{{ route('attendance.show', $event) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                <i class="fas fa-users mr-2"></i>
                View Attendance
            </a>
            
            <a href="{{ route('attendance.qr.show', $event) }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-xl hover:bg-purple-700 transition-colors">
                <i class="fas fa-qrcode mr-2"></i>
                QR Code
            </a>
        </div>
    </div>

    <!-- Event Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Attendance</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $event->attendances->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Checked In</p>
                    <p class="text-2xl font-bold text-green-600">{{ $event->attendances->whereNotNull('checked_in_at')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">QR Scans</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $event->attendances->where('attendance_method', 'qr_code')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-qrcode text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Available Spots</p>
                    <p class="text-2xl font-bold text-orange-600">
                        @if($event->max_attendees)
                            {{ $event->max_attendees - $event->attendances->count() }}
                        @else
                            ∞
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chair text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Description</h3>
                @if($event->description)
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                @else
                    <p class="text-gray-500 italic">No description provided.</p>
                @endif
            </div>

            <!-- Special Instructions -->
            @if($event->special_instructions)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Special Instructions</h3>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="prose prose-gray max-w-none">
                            {!! nl2br(e($event->special_instructions)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Required Items -->
            @if($event->required_items && count($event->required_items) > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Required Items</h3>
                    <ul class="space-y-2">
                        @foreach($event->required_items as $item)
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Recent Attendees -->
            @if($event->attendances->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Attendees</h3>
                        <a href="{{ route('attendance.show', $event) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($event->attendances->take(5) as $attendance)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                <div class="flex items-center space-x-3">
                                    @if($attendance->member->photo)
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $attendance->member->photo) }}" alt="{{ $attendance->member->full_name }}">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-medium text-xs">{{ substr($attendance->member->first_name, 0, 1) }}{{ substr($attendance->member->last_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $attendance->member->full_name }}</p>
                                        <p class="text-xs text-gray-500">
                                            @if($attendance->checked_in_at)
                                                Checked in {{ $attendance->checked_in_at->format('g:i A') }}
                                            @else
                                                Registered
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($attendance->attendance_method === 'qr_code')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-qrcode mr-1"></i>
                                            QR
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Manual
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Event Info Sidebar -->
        <div class="space-y-6">
            <!-- Event Details Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Event Type</label>
                        <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', $event->event_type) }}</p>
                    </div>
                    
                    @if($event->ministry)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Ministry</label>
                            <p class="text-gray-900">{{ $event->ministry->name }}</p>
                        </div>
                    @endif
                    
                    @if($event->organizer)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Organizer</label>
                            <p class="text-gray-900">{{ $event->organizer->full_name }}</p>
                        </div>
                    @endif
                    
                    @if($event->max_attendees)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Max Attendees</label>
                            <p class="text-gray-900">{{ $event->max_attendees }}</p>
                        </div>
                    @endif
                    
                    @if($event->registration_fee)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Registration Fee</label>
                            <p class="text-gray-900">GHS {{ number_format($event->registration_fee, 2) }}</p>
                        </div>
                    @endif
                    
                    @if($event->registration_deadline)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Registration Deadline</label>
                            <p class="text-gray-900">{{ $event->registration_deadline->format('M j, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- QR Code Quick Actions -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">QR Attendance</h3>
                <div class="space-y-3">
                    <a href="{{ route('attendance.qr.show', $event) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-purple-600 text-white font-medium rounded-xl hover:bg-purple-700 transition-colors">
                        <i class="fas fa-qrcode mr-2"></i>
                        Display QR Code
                    </a>
                    
                    <button onclick="generateQrCode()" 
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-purple-300 text-purple-700 font-medium rounded-xl hover:bg-purple-50 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Generate New QR
                    </button>
                    
                    <a href="{{ route('attendance.show', $event) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-purple-300 text-purple-700 font-medium rounded-xl hover:bg-purple-50 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Manage Attendance
                    </a>
                </div>
            </div>

            <!-- Event Actions -->
            @can('update', $event)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('events.edit', $event) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Event
                        </a>
                        
                        @if($event->status === 'draft')
                            <button onclick="publishEvent()" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Publish Event
                            </button>
                        @endif
                        
                        <button onclick="deleteEvent()" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Event
                        </button>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateQrCode() {
    fetch(`{{ route('attendance.qr.generate', $event) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            expiration_hours: 24
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `{{ route('attendance.qr.show', $event) }}`;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while generating QR code');
    });
}

function publishEvent() {
    if (confirm('Are you sure you want to publish this event?')) {
        // Implementation for publishing event
        alert('Event publishing functionality will be implemented');
    }
}

function deleteEvent() {
    if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
        // Implementation for deleting event
        alert('Event deletion functionality will be implemented');
    }
}
</script>
@endpush
@endsection
