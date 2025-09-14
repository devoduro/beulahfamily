@extends('components.app-layout')

@section('title', 'Event Attendance - ' . $event->title)
@section('subtitle', 'Attendance Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('attendance.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
            </div>
            <p class="text-gray-600">{{ $event->start_datetime->format('l, F j, Y \a\t g:i A') }}</p>
            @if($event->location)
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    {{ $event->location }}
                </p>
            @endif
        </div>
        
        <div class="flex items-center space-x-3">
            <button onclick="openManualEntryModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Manual Entry
            </button>
            <a href="{{ route('attendance.qr.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                <i class="fas fa-qrcode mr-2"></i>
                Show QR Code
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $attendanceStats['total_attendance'] }}</p>
                <p class="text-sm text-gray-600">Total Attendance</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $attendanceStats['checked_in'] }}</p>
                <p class="text-sm text-gray-600">Checked In</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-600">{{ $attendanceStats['checked_out'] }}</p>
                <p class="text-sm text-gray-600">Checked Out</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $attendanceStats['qr_scans'] }}</p>
                <p class="text-sm text-gray-600">QR Scans</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-600">{{ $attendanceStats['manual_entries'] }}</p>
                <p class="text-sm text-gray-600">Manual Entries</p>
            </div>
        </div>
    </div>

    <!-- Attendance List -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Attendance Records</h3>
                <div class="flex items-center space-x-3">
                    <input type="text" id="searchAttendance" placeholder="Search members..." 
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <select id="filterMethod" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Methods</option>
                        <option value="qr_code">QR Code</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if($attendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="attendanceTableBody">
                            @foreach($attendances as $attendance)
                                <tr class="attendance-row hover:bg-gray-50" data-member-name="{{ strtolower($attendance->member->full_name) }}" data-method="{{ $attendance->attendance_method }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($attendance->member->photo)
                                                <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ asset('storage/' . $attendance->member->photo) }}" alt="{{ $attendance->member->full_name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                                    <span class="text-white font-medium text-sm">{{ substr($attendance->member->first_name, 0, 1) }}{{ substr($attendance->member->last_name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $attendance->member->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $attendance->member->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $attendance->checked_in_at->format('g:i A') }}
                                        <div class="text-xs text-gray-500">{{ $attendance->checked_in_at->format('M j, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($attendance->checked_out_at)
                                            {{ $attendance->checked_out_at->format('g:i A') }}
                                            <div class="text-xs text-gray-500">{{ $attendance->checked_out_at->format('M j, Y') }}</div>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->attendance_method === 'qr_code')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-qrcode mr-1"></i>
                                                QR Code
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-hand-paper mr-1"></i>
                                                Manual
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($attendance->duration)
                                            {{ floor($attendance->duration / 60) }}h {{ $attendance->duration % 60 }}m
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if(!$attendance->checked_out_at)
                                            <button onclick="checkOutMember({{ $attendance->id }})" class="text-orange-600 hover:text-orange-900">
                                                <i class="fas fa-sign-out-alt mr-1"></i>
                                                Check Out
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium">No attendance records</p>
                    <p class="text-sm text-gray-400 mt-1">Attendance records will appear here once members check in</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Manual Entry Modal -->
<div id="manualEntryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Manual Attendance Entry</h3>
                <button onclick="closeManualEntryModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="manualEntryForm" class="space-y-4">
                @csrf
                <div>
                    <label for="member_search" class="block text-sm font-medium text-gray-700 mb-2">Select Member</label>
                    <input type="text" id="member_search" placeholder="Search for member..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div id="member_results" class="mt-2 max-h-40 overflow-y-auto hidden"></div>
                    <input type="hidden" id="selected_member_id" name="member_id">
                </div>
                
                <div>
                    <label for="check_in_time" class="block text-sm font-medium text-gray-700 mb-2">Check In Time (Optional)</label>
                    <input type="datetime-local" id="check_in_time" name="checked_in_at" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Any additional notes..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeManualEntryModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Add Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Search and filter functionality
document.getElementById('searchAttendance').addEventListener('input', filterAttendance);
document.getElementById('filterMethod').addEventListener('change', filterAttendance);

function filterAttendance() {
    const searchTerm = document.getElementById('searchAttendance').value.toLowerCase();
    const methodFilter = document.getElementById('filterMethod').value;
    const rows = document.querySelectorAll('.attendance-row');
    
    rows.forEach(row => {
        const memberName = row.dataset.memberName;
        const method = row.dataset.method;
        
        const matchesSearch = memberName.includes(searchTerm);
        const matchesMethod = !methodFilter || method === methodFilter;
        
        if (matchesSearch && matchesMethod) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Manual entry modal
function openManualEntryModal() {
    document.getElementById('manualEntryModal').classList.remove('hidden');
}

function closeManualEntryModal() {
    document.getElementById('manualEntryModal').classList.add('hidden');
    document.getElementById('manualEntryForm').reset();
    document.getElementById('selected_member_id').value = '';
    document.getElementById('member_results').classList.add('hidden');
}

// Member search functionality
let searchTimeout;
document.getElementById('member_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        document.getElementById('member_results').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        // This would typically make an AJAX call to search members
        // For now, we'll show a placeholder
        const resultsDiv = document.getElementById('member_results');
        resultsDiv.innerHTML = '<div class="p-2 text-sm text-gray-500">Search functionality will be implemented</div>';
        resultsDiv.classList.remove('hidden');
    }, 300);
});

// Manual entry form submission
document.getElementById('manualEntryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`{{ route('attendance.manual-entry', $event) }}`, {
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
        alert('An error occurred while adding attendance');
    });
});

// Check out functionality
function checkOutMember(attendanceId) {
    if (confirm('Are you sure you want to check out this member?')) {
        fetch(`/attendance/${attendanceId}/checkout`, {
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
            alert('An error occurred while checking out');
        });
    }
}
</script>
@endpush
@endsection
