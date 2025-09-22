@extends('components.app-layout')

@section('title', 'Event Attendance - ' . $event->title)
@section('subtitle', 'Attendance Details')

@section('content')
<div class="space-y-6">
    <!-- Enhanced Header with Gradient Background -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <a href="{{ route('attendance.index') }}" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-white">{{ $event->title }}</h1>
                    <div class="ml-3">
                        @if($event->qrCodes->where('is_active', true)->count() > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-qrcode mr-1"></i>
                                QR Active
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-white/90">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>{{ $event->start_datetime->format('l, F j, Y \a\t g:i A') }}</span>
                    </div>
                    @if($event->location)
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                    @endif
                    @if($event->end_datetime)
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Duration: {{ $event->start_datetime->diffForHumans($event->end_datetime, true) }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button onclick="openBulkEntryModal()" class="inline-flex items-center justify-center px-4 py-2.5 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition-all duration-200 border border-white/20">
                    <i class="fas fa-users mr-2"></i>
                    Bulk Entry
                </button>
                <button onclick="openManualEntryModal()" class="inline-flex items-center justify-center px-4 py-2.5 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition-all duration-200 border border-white/20">
                    <i class="fas fa-plus mr-2"></i>
                    Manual Entry
                </button>
                <a href="{{ route('attendance.qr.show', $event) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white text-blue-600 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-lg">
                    <i class="fas fa-qrcode mr-2"></i>
                    QR Code
                </a>
                <button onclick="exportAttendance()" class="inline-flex items-center justify-center px-4 py-2.5 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition-all duration-200 border border-white/20">
                    <i class="fas fa-download mr-2"></i>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards with Animations -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold">{{ $attendanceStats['total_attendance'] }}</p>
                    <p class="text-blue-100 font-medium">Total Attendance</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold">{{ $attendanceStats['checked_in'] }}</p>
                    <p class="text-green-100 font-medium">Checked In</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-sign-in-alt text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold">{{ $attendanceStats['checked_out'] }}</p>
                    <p class="text-orange-100 font-medium">Checked Out</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-sign-out-alt text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold">{{ $attendanceStats['qr_scans'] }}</p>
                    <p class="text-purple-100 font-medium">QR Scans</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold">{{ $attendanceStats['manual_entries'] }}</p>
                    <p class="text-gray-100 font-medium">Manual Entries</p>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-hand-paper text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Attendance List -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Attendance Records</h3>
                    <p class="text-sm text-gray-600">Real-time attendance tracking and management</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                    <div class="relative">
                        <input type="text" id="searchAttendance" placeholder="Search members..." 
                               class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white shadow-sm">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select id="filterMethod" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white shadow-sm">
                        <option value="">All Methods</option>
                        <option value="qr_code">QR Code</option>
                        <option value="manual">Manual</option>
                    </select>
                    <button onclick="refreshAttendance()" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
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

<!-- Enhanced Manual Entry Modal -->
<div id="manualEntryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl -m-6 mb-6 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Manual Attendance Entry</h3>
                    <p class="text-blue-100 text-sm">Add member attendance manually</p>
                </div>
                <button onclick="closeManualEntryModal()" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="manualEntryForm" class="space-y-6">
            @csrf
            <div>
                <label for="member_search" class="block text-sm font-bold text-gray-700 mb-3">Select Member *</label>
                <div class="relative">
                    <input type="text" id="member_search" placeholder="Type member name or email..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-colors">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div id="member_results" class="mt-2 max-h-48 overflow-y-auto hidden bg-white border border-gray-200 rounded-xl shadow-lg"></div>
                <input type="hidden" id="selected_member_id" name="member_id">
                
                <!-- Selected Member Display -->
                <div id="selected_member_display" class="hidden mt-3 p-3 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="flex items-center space-x-3">
                        <div id="member_avatar" class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span id="member_initials" class="text-white font-medium text-sm"></span>
                        </div>
                        <div class="flex-1">
                            <p id="selected_member_name" class="font-semibold text-gray-900"></p>
                            <p id="selected_member_email" class="text-sm text-gray-600"></p>
                        </div>
                        <button type="button" onclick="clearMemberSelection()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="check_in_time" class="block text-sm font-bold text-gray-700 mb-3">Check In Time</label>
                <input type="datetime-local" id="check_in_time" name="checked_in_at" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-colors">
                <p class="text-xs text-gray-500 mt-1">Leave empty to use current time</p>
            </div>
            
            <div>
                <label for="notes" class="block text-sm font-bold text-gray-700 mb-3">Notes</label>
                <textarea id="notes" name="notes" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-colors resize-none"
                          placeholder="Any additional notes or comments..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="closeManualEntryModal()" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" id="manualEntrySubmitBtn"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-plus mr-2"></i>
                    Add Attendance
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Entry Modal -->
<div id="bulkEntryModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-6 border w-full max-w-2xl shadow-2xl rounded-2xl bg-white">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-t-2xl -m-6 mb-6 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Bulk Attendance Entry</h3>
                    <p class="text-green-100 text-sm">Add multiple members at once</p>
                </div>
                <button onclick="closeBulkEntryModal()" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form id="bulkEntryForm" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Select Members *</label>
                <div class="relative">
                    <input type="text" id="bulk_member_search" placeholder="Search and select multiple members..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50 focus:bg-white transition-colors">
                    <i class="fas fa-users absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <div id="bulk_member_results" class="mt-2 max-h-48 overflow-y-auto hidden bg-white border border-gray-200 rounded-xl shadow-lg"></div>
                
                <!-- Selected Members Display -->
                <div id="selected_members_container" class="mt-4 space-y-2 max-h-40 overflow-y-auto"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="bulk_check_in_time" class="block text-sm font-bold text-gray-700 mb-3">Check In Time</label>
                    <input type="datetime-local" id="bulk_check_in_time" name="checked_in_at" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50 focus:bg-white transition-colors">
                </div>
                
                <div>
                    <label for="bulk_notes" class="block text-sm font-bold text-gray-700 mb-3">Notes</label>
                    <textarea id="bulk_notes" name="notes" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50 focus:bg-white transition-colors resize-none"
                              placeholder="Notes for all selected members..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-between items-center pt-4">
                <div class="text-sm text-gray-600">
                    <span id="selected_count">0</span> members selected
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeBulkEntryModal()" 
                            class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="bulkEntrySubmitBtn"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-teal-700 transition-all duration-200 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-users mr-2"></i>
                        Add All Attendance
                    </button>
                </div>
            </div>
        </form>
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

// Enhanced Member search functionality with caching
let searchTimeout;
let selectedMembers = new Map(); // Changed to Map for better data storage
let memberCache = new Map(); // Cache for member data
let isSearching = false;

// Manual entry member search
document.getElementById('member_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        document.getElementById('member_results').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        searchMembers(query, 'member_results', false);
    }, 300);
});

// Bulk entry member search
document.getElementById('bulk_member_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        document.getElementById('bulk_member_results').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        searchMembers(query, 'bulk_member_results', true);
    }, 300);
});

function searchMembers(query, resultsContainerId, isBulk = false) {
    // Check cache first
    const cacheKey = query.toLowerCase();
    if (memberCache.has(cacheKey)) {
        displaySearchResults(memberCache.get(cacheKey), resultsContainerId, isBulk);
        return;
    }
    
    if (isSearching) return; // Prevent multiple simultaneous requests
    
    isSearching = true;
    const resultsDiv = document.getElementById(resultsContainerId);
    
    // Show loading state
    resultsDiv.innerHTML = `
        <div class="p-4 text-center text-gray-500">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Searching members...
        </div>
    `;
    resultsDiv.classList.remove('hidden');
    
    fetch(`/api/members/search?q=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        isSearching = false;
        
        if (data.error) {
            throw new Error(data.message || 'API returned an error');
        }
        
        // Cache the results
        memberCache.set(cacheKey, data);
        
        displaySearchResults(data, resultsContainerId, isBulk);
    })
    .catch(error => {
        isSearching = false;
        console.error('Error searching members:', error);
        
        resultsDiv.innerHTML = `
            <div class="p-4 text-center">
                <div class="text-red-500 mb-2">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <p class="text-red-600 font-medium mb-2">Error loading members</p>
                <p class="text-sm text-gray-600 mb-3">${error.message}</p>
                <button onclick="retrySearch('${query}', '${resultsContainerId}', ${isBulk})" 
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-redo mr-1"></i>
                    Retry
                </button>
            </div>
        `;
        resultsDiv.classList.remove('hidden');
    });
}

function retrySearch(query, resultsContainerId, isBulk) {
    // Clear cache for this query and retry
    memberCache.delete(query.toLowerCase());
    searchMembers(query, resultsContainerId, isBulk);
}

function displaySearchResults(members, resultsContainerId, isBulk = false) {
    const resultsDiv = document.getElementById(resultsContainerId);
    
    if (members.length === 0) {
        resultsDiv.innerHTML = `
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-slash text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 font-medium">No members found</p>
                <p class="text-sm text-gray-400 mt-1">Try a different search term</p>
            </div>
        `;
    } else {
        resultsDiv.innerHTML = members.map(member => {
            const isSelected = selectedMembers.has(member.id);
            const disabledClass = isBulk && isSelected ? 'opacity-50 cursor-not-allowed bg-gray-50' : 'hover:bg-blue-50 cursor-pointer transition-colors';
            const checkIcon = isBulk && isSelected ? '<i class="fas fa-check-circle text-green-600 text-lg"></i>' : '';
            const borderClass = isBulk && isSelected ? 'border-green-200 bg-green-50' : 'border-gray-100';
            
            // Create member data object for storage
            const memberData = {
                id: member.id,
                full_name: member.full_name,
                email: member.email,
                first_name: member.first_name,
                last_name: member.last_name,
                member_id: member.member_id,
                phone: member.phone,
                chapter: member.chapter,
                photo: member.photo,
                initials: member.initials
            };
            
            return `
                <div class="p-4 ${disabledClass} border-b ${borderClass} last:border-0 transition-all duration-200" 
                     onclick="${isBulk ? `selectBulkMember(${JSON.stringify(memberData).replace(/"/g, '&quot;')})` : `selectMember(${JSON.stringify(memberData).replace(/"/g, '&quot;')})`}"
                     ${isBulk && isSelected ? '' : 'onmouseenter="this.classList.add(\'shadow-sm\')" onmouseleave="this.classList.remove(\'shadow-sm\')"'}>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            ${member.photo ? 
                                `<img src="${member.photo}" alt="${member.full_name}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">` :
                                `<div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-sm">
                                    <span class="text-white font-semibold">${member.initials}</span>
                                </div>`
                            }
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-semibold text-gray-900 truncate">${member.full_name}</h4>
                                    ${member.chapter ? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${member.chapter}</span>` : ''}
                                </div>
                                <div class="flex items-center space-x-4 mt-1">
                                    <p class="text-sm text-gray-600 truncate">${member.email}</p>
                                    ${member.member_id ? `<span class="text-xs text-gray-500 font-mono">#${member.member_id}</span>` : ''}
                                </div>
                                ${member.phone ? `<p class="text-xs text-gray-500 mt-1"><i class="fas fa-phone mr-1"></i>${member.phone}</p>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            ${checkIcon}
                            ${!isBulk || !isSelected ? '<i class="fas fa-chevron-right text-gray-400"></i>' : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    resultsDiv.classList.remove('hidden');
}

// Enhanced Member selection functions
function selectMember(memberData) {
    if (typeof memberData === 'string') {
        memberData = JSON.parse(memberData);
    }
    
    document.getElementById('selected_member_id').value = memberData.id;
    document.getElementById('member_search').value = memberData.full_name;
    document.getElementById('member_results').classList.add('hidden');
    
    // Show selected member display with enhanced info
    document.getElementById('selected_member_name').textContent = memberData.full_name;
    document.getElementById('selected_member_email').textContent = memberData.email;
    document.getElementById('member_initials').textContent = memberData.initials;
    
    // Update avatar if photo exists
    const avatarDiv = document.getElementById('member_avatar');
    if (memberData.photo) {
        avatarDiv.innerHTML = `<img src="${memberData.photo}" alt="${memberData.full_name}" class="w-10 h-10 rounded-full object-cover">`;
    } else {
        avatarDiv.innerHTML = `<span id="member_initials" class="text-white font-medium text-sm">${memberData.initials}</span>`;
    }
    
    document.getElementById('selected_member_display').classList.remove('hidden');
    
    // Enable submit button
    document.getElementById('manualEntrySubmitBtn').disabled = false;
}

function clearMemberSelection() {
    document.getElementById('selected_member_id').value = '';
    document.getElementById('member_search').value = '';
    document.getElementById('selected_member_display').classList.add('hidden');
    document.getElementById('manualEntrySubmitBtn').disabled = true;
    
    // Reset avatar
    const avatarDiv = document.getElementById('member_avatar');
    avatarDiv.innerHTML = `<span id="member_initials" class="text-white font-medium text-sm"></span>`;
}

function selectBulkMember(memberData) {
    if (typeof memberData === 'string') {
        memberData = JSON.parse(memberData);
    }
    
    if (selectedMembers.has(memberData.id)) return; // Already selected
    
    selectedMembers.set(memberData.id, memberData);
    updateSelectedMembersDisplay();
    updateSelectedCount();
    
    // Clear search
    document.getElementById('bulk_member_search').value = '';
    document.getElementById('bulk_member_results').classList.add('hidden');
}

function removeBulkMember(id) {
    selectedMembers.delete(id);
    updateSelectedMembersDisplay();
    updateSelectedCount();
}

function updateSelectedMembersDisplay() {
    const container = document.getElementById('selected_members_container');
    const memberDataArray = Array.from(selectedMembers.values());
    
    if (memberDataArray.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-gray-500">
                <i class="fas fa-users text-2xl mb-2"></i>
                <p class="text-sm">No members selected yet</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = memberDataArray.map(member => `
        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 transition-all duration-200 hover:shadow-sm">
            <div class="flex items-center space-x-3">
                ${member.photo ? 
                    `<img src="${member.photo}" alt="${member.full_name}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">` :
                    `<div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center shadow-sm">
                        <span class="text-white text-sm font-medium">${member.initials}</span>
                    </div>`
                }
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold text-gray-900 truncate">${member.full_name}</span>
                        ${member.chapter ? `<span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">${member.chapter}</span>` : ''}
                    </div>
                    <p class="text-sm text-gray-600 truncate">${member.email}</p>
                </div>
            </div>
            <button type="button" onclick="removeBulkMember(${member.id})" 
                    class="text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full p-1 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
}

function updateSelectedCount() {
    document.getElementById('selected_count').textContent = selectedMembers.size;
    document.getElementById('bulkEntrySubmitBtn').disabled = selectedMembers.size === 0;
}

// Modal functions
function openBulkEntryModal() {
    selectedMembers.clear();
    updateSelectedMembersDisplay();
    updateSelectedCount();
    document.getElementById('bulkEntryModal').classList.remove('hidden');
}

function closeBulkEntryModal() {
    document.getElementById('bulkEntryModal').classList.add('hidden');
    document.getElementById('bulkEntryForm').reset();
    selectedMembers.clear();
    document.getElementById('bulk_member_results').classList.add('hidden');
}

// Enhanced form submissions
document.getElementById('manualEntryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('manualEntrySubmitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
    submitBtn.disabled = true;
    
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
            showSuccessToast('Attendance added successfully!');
            closeManualEntryModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showErrorToast(data.message);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('An error occurred while adding attendance');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Bulk entry form submission
document.getElementById('bulkEntryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('bulkEntrySubmitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    
    // Add selected member IDs to form data
    const memberIds = Array.from(selectedMembers.keys());
    memberIds.forEach(id => {
        formData.append('member_ids[]', id);
    });
    
    fetch(`{{ route('attendance.bulk-entry', $event) }}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(`${data.details.success_count} attendance records added successfully!`);
            if (data.details.skipped_count > 0) {
                showInfoToast(`${data.details.skipped_count} members were skipped (already have attendance)`);
            }
            closeBulkEntryModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showErrorToast(data.message);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = selectedMembers.size === 0;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('An error occurred while processing bulk attendance');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = selectedMembers.size === 0;
    });
});

// Additional utility functions
function refreshAttendance() {
    location.reload();
}

function exportAttendance() {
    window.open(`{{ route('attendance.export') }}?event_id={{ $event->id }}`, '_blank');
}

function showSuccessToast(message) {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.innerHTML = `<i class="fas fa-check mr-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function showErrorToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

function showInfoToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// Initialize form states on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize manual entry submit button state
    document.getElementById('manualEntrySubmitBtn').disabled = true;
    
    // Initialize bulk entry submit button state
    document.getElementById('bulkEntrySubmitBtn').disabled = true;
    
    // Set default check-in times to current time
    const now = new Date();
    const currentDateTime = now.toISOString().slice(0, 16);
    document.getElementById('check_in_time').value = currentDateTime;
    document.getElementById('bulk_check_in_time').value = currentDateTime;
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
