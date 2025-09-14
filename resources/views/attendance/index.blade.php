@extends('components.app-layout')

@section('title', 'Attendance Management')
@section('subtitle', 'QR Code Attendance System')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance Management</h1>
            <p class="text-gray-600 mt-1">Track member attendance with QR code scanning</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('attendance.statistics') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-chart-bar mr-2"></i>
                Statistics
            </a>
            <button onclick="exportAttendance()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-download mr-2"></i>
                Export Data
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Today's Attendance</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['today_attendance'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">This Week</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['this_week_attendance'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-week text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">This Month</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['this_month_attendance'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active QR Codes</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['total_events_with_qr'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-qrcode text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Events with Attendance -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Events</h3>
                <span class="text-sm text-gray-500">Last 30 days</span>
            </div>
        </div>
        
        <div class="p-6">
            @if($events->count() > 0)
                <div class="space-y-4">
                    @foreach($events as $event)
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-calendar text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $event->start_datetime->format('M d, Y \a\t g:i A') }}</p>
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span class="text-sm text-blue-600 font-medium">
                                                    <i class="fas fa-users mr-1"></i>
                                                    {{ $event->attendances->count() }} attendees
                                                </span>
                                                @if($event->qrCodes->where('is_active', true)->count() > 0)
                                                    <span class="text-sm text-green-600 font-medium">
                                                        <i class="fas fa-qrcode mr-1"></i>
                                                        QR Active
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('attendance.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Details
                                    </a>
                                    <a href="{{ route('attendance.qr.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-qrcode mr-2"></i>
                                        QR Code
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No events found</p>
                    <p class="text-sm text-gray-400 mt-1">Events with attendance tracking will appear here</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportAttendance() {
    // Placeholder for export functionality
    alert('Export functionality will be implemented');
}
</script>
@endpush
@endsection
