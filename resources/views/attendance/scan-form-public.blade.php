<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mark Attendance - {{ $event->title }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-2xl mx-auto px-4 py-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-qrcode text-white"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Mark Your Attendance</h1>
                <p class="text-sm text-gray-500">{{ $event->title }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Event Info -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $event->title }}</h2>
            <div class="text-gray-600 space-y-1">
                <div class="flex items-center justify-center space-x-2">
                    <i class="fas fa-calendar text-green-600"></i>
                    <span>{{ $event->start_datetime->format('l, F j, Y') }}</span>
                </div>
                <div class="flex items-center justify-center space-x-2">
                    <i class="fas fa-clock text-green-600"></i>
                    <span>{{ $event->start_datetime->format('g:i A') }}</span>
                </div>
                @if($event->location)
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-map-marker-alt text-green-600"></i>
                        <span>{{ $event->location }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Attendance Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <form id="attendanceForm" class="space-y-6">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="token" value="{{ $token }}">
                
                <!-- Member Selection -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label for="member_search" class="block text-sm font-medium text-gray-700">Find Your Name</label>
                        <button type="button" onclick="showGuestForm()" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            <i class="fas fa-user-plus mr-1"></i>New Visitor?
                        </button>
                    </div>
                    <div class="relative">
                        <input type="text" 
                               id="member_search" 
                               placeholder="Type your name to search..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg"
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
                <div id="selected_member" class="hidden bg-green-50 rounded-xl p-4">
                    <div class="flex items-center space-x-4">
                        <div id="member_avatar" class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                            <span id="member_initials" class="text-white font-medium"></span>
                        </div>
                        <div class="flex-1">
                            <div id="member_name" class="font-semibold text-gray-900"></div>
                            <div id="member_details" class="text-sm text-gray-600"></div>
                        </div>
                        <button type="button" onclick="clearSelection()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            id="submit_btn" 
                            disabled
                            class="w-full py-4 px-6 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed transition-all duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Select Your Name First
                    </button>
                </div>
            </form>
        </div>

        <!-- Guest Registration Form -->
        <div id="guest_form" class="hidden bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mt-6">
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-plus text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Register as New Member/Guest</h3>
                <p class="text-sm text-gray-500">Please fill in your details to mark attendance</p>
            </div>

            <form id="guestRegistrationForm" class="space-y-4">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="is_guest" value="1">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="tel" name="phone" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 0241234567">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                    <input type="email" name="email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="your@email.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">I am a: *</label>
                    <select name="guest_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select...</option>
                        <option value="first_timer">First Time Visitor</option>
                        <option value="guest">Guest</option>
                        <option value="new_member">New Member</option>
                        <option value="returning_member">Returning Member</option>
                    </select>
                </div>

                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="hideGuestForm()"
                            class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </button>
                    <button type="submit" id="guest_submit_btn"
                            class="flex-1 py-3 px-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-check mr-2"></i>Register & Mark Attendance
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        <div id="success_message" class="hidden bg-green-50 border border-green-200 rounded-xl p-6 mt-6">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-check text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-green-900">Attendance Marked Successfully!</h3>
                    <p class="text-green-700">Thank you for attending <strong>{{ $event->title }}</strong></p>
                </div>
            </div>
            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-envelope mr-2"></i>
                    A confirmation email has been sent to your email address with the event details.
                </p>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error_message" class="hidden bg-red-50 border border-red-200 rounded-xl p-6 mt-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-red-900">Error</h3>
                    <p id="error_text" class="text-red-700"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;
        const memberSearch = document.getElementById('member_search');
        const memberResults = document.getElementById('member_results');
        const selectedMember = document.getElementById('selected_member');
        const selectedMemberId = document.getElementById('selected_member_id');
        const submitBtn = document.getElementById('submit_btn');

        // Member search functionality
        memberSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                memberResults.classList.add('hidden');
                return;
            }
            
            searchTimeout = setTimeout(() => {
                searchMembers(query);
            }, 300);
        });

        function searchMembers(query) {
            fetch(`/api/members/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }

        function displaySearchResults(members) {
            if (members.length === 0) {
                memberResults.innerHTML = `
                    <div class="p-4 text-center">
                        <p class="text-gray-500 mb-3">No member found with that name</p>
                        <button type="button" onclick="showGuestForm()" class="text-green-600 hover:text-green-700 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>Register as New Member/Guest
                        </button>
                    </div>
                `;
            } else {
                memberResults.innerHTML = members.map(member => `
                    <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0" 
                         onclick="selectMember(${member.id}, '${member.first_name} ${member.last_name}', '${member.email || ''}', '${member.phone || ''}')">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm">${member.first_name.charAt(0)}${member.last_name.charAt(0)}</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">${member.first_name} ${member.last_name}</div>
                                <div class="text-sm text-gray-500">${member.email || member.phone || ''}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
            memberResults.classList.remove('hidden');
        }

        function selectMember(id, name, email, phone) {
            selectedMemberId.value = id;
            
            // Update selected member display
            document.getElementById('member_initials').textContent = name.split(' ').map(n => n.charAt(0)).join('');
            document.getElementById('member_name').textContent = name;
            document.getElementById('member_details').textContent = email || phone || '';
            
            // Show selected member, hide search results
            selectedMember.classList.remove('hidden');
            memberResults.classList.add('hidden');
            memberSearch.value = '';
            
            // Enable submit button
            submitBtn.disabled = false;
            submitBtn.className = 'w-full py-4 px-6 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all duration-200';
            submitBtn.innerHTML = '<i class="fas fa-user-check mr-2"></i>Mark My Attendance';
        }

        function clearSelection() {
            selectedMemberId.value = '';
            selectedMember.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.className = 'w-full py-4 px-6 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed transition-all duration-200';
            submitBtn.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Select Your Name First';
        }

        // Form submission
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedMemberId.value) {
                showError('Please select your name first.');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Marking Attendance...';
            
            const formData = new FormData(this);
            
            fetch('/mark-attendance', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess();
                } else {
                    showError(data.message || 'Failed to mark attendance. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            })
            .finally(() => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-check mr-2"></i>Mark My Attendance';
            });
        });

        function showSuccess() {
            document.getElementById('success_message').classList.remove('hidden');
            document.getElementById('error_message').classList.add('hidden');
            document.getElementById('attendanceForm').style.display = 'none';
        }

        function showError(message) {
            document.getElementById('error_text').textContent = message;
            document.getElementById('error_message').classList.remove('hidden');
            document.getElementById('success_message').classList.add('hidden');
        }

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!memberSearch.contains(e.target) && !memberResults.contains(e.target)) {
                memberResults.classList.add('hidden');
            }
        });

        // Guest form functions
        function showGuestForm() {
            document.getElementById('attendanceForm').parentElement.classList.add('hidden');
            document.getElementById('guest_form').classList.remove('hidden');
            memberResults.classList.add('hidden');
        }

        function hideGuestForm() {
            document.getElementById('guest_form').classList.add('hidden');
            document.getElementById('attendanceForm').parentElement.classList.remove('hidden');
            memberSearch.value = '';
        }

        // Guest registration form submission
        document.getElementById('guestRegistrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const guestSubmitBtn = document.getElementById('guest_submit_btn');
            
            // Show loading state
            guestSubmitBtn.disabled = true;
            guestSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Registering...';
            
            const formData = new FormData(this);
            
            fetch('/mark-attendance', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess();
                    document.getElementById('guest_form').style.display = 'none';
                } else {
                    showError(data.message || 'Failed to register and mark attendance. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            })
            .finally(() => {
                // Reset button
                guestSubmitBtn.disabled = false;
                guestSubmitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Register & Mark Attendance';
            });
        });
    </script>
</body>
</html>
