@extends('components.app-layout')

@section('title', 'Mark Attendance')
@section('subtitle', 'QR Code Scan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-qrcode text-2xl text-white"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Mark Your Attendance</h1>
        <p class="text-gray-600">{{ $event->title }}</p>
        <p class="text-sm text-gray-500">{{ $event->start_datetime->format('l, F j, Y \a\t g:i A') }}</p>
    </div>

    <!-- Attendance Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <form id="attendanceForm" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <!-- Member Selection -->
            <div>
                <label for="member_search" class="block text-sm font-medium text-gray-700 mb-3">Find Your Name</label>
                <div class="relative">
                    <input type="text" 
                           id="member_search" 
                           placeholder="Type your name to search..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                           autocomplete="off">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Search Results -->
                <div id="member_results" class="mt-3 max-h-60 overflow-y-auto hidden bg-white border border-gray-200 rounded-xl shadow-lg">
                    <!-- Results will be populated here -->
                </div>
                
                <input type="hidden" id="selected_member_id" name="member_id">
            </div>

            <!-- Selected Member Display -->
            <div id="selected_member" class="hidden bg-blue-50 rounded-xl p-4">
                <div class="flex items-center space-x-4">
                    <div id="member_avatar" class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span id="member_initials" class="text-white font-medium"></span>
                    </div>
                    <div class="flex-1">
                        <h3 id="member_name" class="font-semibold text-gray-900"></h3>
                        <p id="member_email" class="text-sm text-gray-600"></p>
                    </div>
                    <button type="button" onclick="clearSelection()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" 
                        id="submitBtn"
                        disabled
                        class="w-full py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    Mark Attendance
                </button>
            </div>
        </form>
    </div>

    <!-- Instructions -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
        <h3 class="font-semibold text-gray-900 mb-3">Instructions</h3>
        <ul class="space-y-2 text-sm text-gray-600">
            <li class="flex items-start">
                <i class="fas fa-circle text-blue-500 text-xs mt-2 mr-3"></i>
                Search for your name in the field above
            </li>
            <li class="flex items-start">
                <i class="fas fa-circle text-blue-500 text-xs mt-2 mr-3"></i>
                Select your name from the search results
            </li>
            <li class="flex items-start">
                <i class="fas fa-circle text-blue-500 text-xs mt-2 mr-3"></i>
                Click "Mark Attendance" to complete check-in
            </li>
        </ul>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
        <div class="mt-3 text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-2xl text-white"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Attendance Marked!</h3>
            <p class="text-gray-600 mb-6">Your attendance has been successfully recorded.</p>
            <div id="attendance_details" class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <!-- Attendance details will be populated here -->
            </div>
            <button onclick="closeSuccessModal()" 
                    class="w-full py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let searchTimeout;
let members = []; // This would typically be loaded from an API

// Member search will use real API data

// Member search functionality
document.getElementById('member_search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim().toLowerCase();
    
    if (query.length < 2) {
        document.getElementById('member_results').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        searchMembers(query);
    }, 300);
});

function searchMembers(query) {
    // Make AJAX call to search members
    fetch(`/api/members/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(members => {
            displaySearchResults(members);
        })
        .catch(error => {
            console.error('Error searching members:', error);
            displaySearchResults([]);
        });
}

function displaySearchResults(members) {
    const resultsDiv = document.getElementById('member_results');
    
    if (members.length === 0) {
        resultsDiv.innerHTML = '<div class="p-4 text-center text-gray-500">No members found</div>';
    } else {
        resultsDiv.innerHTML = members.map(member => `
            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0" 
                 onclick="selectMember(${member.id}, '${member.full_name}', '${member.email}', '${member.first_name}', '${member.last_name}')">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-medium">${member.first_name.charAt(0)}${member.last_name.charAt(0)}</span>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">${member.full_name}</div>
                        <div class="text-sm text-gray-500">${member.email}</div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    resultsDiv.classList.remove('hidden');
}

function selectMember(id, fullName, email, firstName, lastName) {
    document.getElementById('selected_member_id').value = id;
    document.getElementById('member_search').value = fullName;
    document.getElementById('member_results').classList.add('hidden');
    
    // Show selected member
    document.getElementById('member_name').textContent = fullName;
    document.getElementById('member_email').textContent = email;
    document.getElementById('member_initials').textContent = firstName.charAt(0) + lastName.charAt(0);
    document.getElementById('selected_member').classList.remove('hidden');
    
    // Enable submit button
    document.getElementById('submitBtn').disabled = false;
}

function clearSelection() {
    document.getElementById('selected_member_id').value = '';
    document.getElementById('member_search').value = '';
    document.getElementById('selected_member').classList.add('hidden');
    document.getElementById('submitBtn').disabled = true;
}

// Form submission
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    
    fetch('{{ route("attendance.mark") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal(data.attendance);
        } else {
            alert(data.message);
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while marking attendance');
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function showSuccessModal(attendance) {
    const detailsDiv = document.getElementById('attendance_details');
    detailsDiv.innerHTML = `
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Member:</span>
                <span class="font-medium">${attendance.member_name}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Event:</span>
                <span class="font-medium">${attendance.event_title}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Time:</span>
                <span class="font-medium">${new Date(attendance.checked_in_at).toLocaleString()}</span>
            </div>
        </div>
    `;
    
    document.getElementById('successModal').classList.remove('hidden');
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    // Optionally redirect or reset form
    window.location.href = '{{ route("attendance.index") }}';
}

// Hide search results when clicking outside
document.addEventListener('click', function(e) {
    const searchInput = document.getElementById('member_search');
    const resultsDiv = document.getElementById('member_results');
    
    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
        resultsDiv.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
