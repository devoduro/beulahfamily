@extends('components.app-layout')

@section('title', 'Send SMS')
@section('subtitle', 'Bulk messaging system')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-sms text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Send SMS Message</h1>
            <p class="text-gray-600 text-lg mt-2">Communicate with your church members instantly</p>
            
            @if($balance['success'])
            <div class="mt-4 inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                <i class="fas fa-wallet mr-2"></i>
                Balance: {{ $balance['currency'] }} {{ number_format($balance['balance'], 2) }}
            </div>
            @endif
        </div>

        <form id="sms-form" class="space-y-8">
            @csrf
            
            <!-- Message Composition -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Compose Message</h2>
                        <p class="text-gray-500 text-sm">Create your SMS content</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Message Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-800 mb-2">Message Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" required 
                                   class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                                   placeholder="Enter message title for reference">
                        </div>

                        <!-- Template Selection -->
                        <div>
                            <label for="template_id" class="block text-sm font-semibold text-gray-800 mb-2">Use Template (Optional)</label>
                            <select name="template_id" id="template_id" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                                <option value="">Select a template</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" data-message="{{ $template->message }}">
                                        {{ $template->name }} ({{ ucfirst($template->category) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Message Content -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-800 mb-2">Message Content <span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="6" required 
                                      class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none" 
                                      placeholder="Type your message here..." maxlength="1600"></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm text-gray-500">
                                    <span id="char-count">0</span>/1600 characters
                                </span>
                                <span class="text-sm text-gray-500">
                                    <span id="sms-count">0</span> SMS
                                </span>
                            </div>
                        </div>

                        <!-- Sender Name -->
                        <div>
                            <label for="sender_name" class="block text-sm font-semibold text-gray-800 mb-2">Sender Name (Optional)</label>
                            <input type="text" name="sender_name" id="sender_name" maxlength="11"
                                   class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                                   placeholder="e.g., BeulahFam (max 11 chars)">
                            <p class="text-xs text-gray-500 mt-1">Custom sender name (11 characters max). Leave blank to use default.</p>
                        </div>
                    </div>

                    <!-- Cost Estimation -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                            <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                                <i class="fas fa-calculator mr-2"></i>
                                Cost Estimation
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-green-700">Recipients:</span>
                                    <span class="font-semibold text-green-900" id="recipient-count">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">SMS Count:</span>
                                    <span class="font-semibold text-green-900" id="total-sms">0</span>
                                </div>
                                <div class="flex justify-between border-t border-green-200 pt-2">
                                    <span class="text-green-700">Estimated Cost:</span>
                                    <span class="font-bold text-green-900" id="estimated-cost">GHS 0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <button type="button" id="preview-btn" class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-colors text-sm font-medium">
                                    <i class="fas fa-eye mr-2"></i>Preview Message
                                </button>
                                <button type="button" id="save-template-btn" class="w-full px-4 py-2 bg-purple-100 text-purple-700 rounded-xl hover:bg-purple-200 transition-colors text-sm font-medium">
                                    <i class="fas fa-save mr-2"></i>Save as Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipients Selection -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Select Recipients</h2>
                        <p class="text-gray-500 text-sm">Choose who will receive your message</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Recipient Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-4">Recipient Type <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <label class="recipient-type-card cursor-pointer border-2 border-gray-200 rounded-2xl p-4 hover:border-blue-500 hover:bg-blue-50 transition-all duration-300">
                                <input type="radio" name="recipient_type" value="all" class="sr-only">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-globe text-blue-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">All Members</h3>
                                    <p class="text-sm text-gray-600">Send to everyone</p>
                                </div>
                            </label>

                            <label class="recipient-type-card cursor-pointer border-2 border-gray-200 rounded-2xl p-4 hover:border-green-500 hover:bg-green-50 transition-all duration-300">
                                <input type="radio" name="recipient_type" value="chapter" class="sr-only">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">By Chapter</h3>
                                    <p class="text-sm text-gray-600">Select specific chapter</p>
                                </div>
                            </label>

                            <label class="recipient-type-card cursor-pointer border-2 border-gray-200 rounded-2xl p-4 hover:border-purple-500 hover:bg-purple-50 transition-all duration-300">
                                <input type="radio" name="recipient_type" value="custom" class="sr-only">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-user-check text-purple-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Custom Selection</h3>
                                    <p class="text-sm text-gray-600">Pick specific members</p>
                                </div>
                            </label>

                            <label class="recipient-type-card cursor-pointer border-2 border-gray-200 rounded-2xl p-4 hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                                <input type="radio" name="recipient_type" value="members" class="sr-only">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-users text-orange-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-900">Active Members</h3>
                                    <p class="text-sm text-gray-600">All active members</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Chapter Selection -->
                    <div id="chapter-selection" class="hidden">
                        <label for="chapter" class="block text-sm font-semibold text-gray-800 mb-2">Select Chapter</label>
                        <select name="chapter" id="chapter" class="block w-full md:w-1/2 px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select Chapter</option>
                            <option value="ACCRA">ACCRA</option>
                            <option value="KUMASI">KUMASI</option>
                            <option value="NEW JESSY">NEW JESSY</option>
                        </select>
                    </div>

                    <!-- Custom Recipients Selection -->
                    <div id="custom-selection" class="hidden">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Select Members</label>
                        <div class="bg-gray-50 rounded-2xl p-4 max-h-64 overflow-y-auto">
                            <div class="mb-4">
                                <input type="text" id="member-search" placeholder="Search members..." 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2" id="member-list">
                                @foreach($members as $member)
                                <label class="flex items-center p-2 hover:bg-white rounded-lg transition-colors">
                                    <input type="checkbox" name="custom_recipients[]" value="{{ $member->id }}" 
                                           class="mr-3 text-blue-600 focus:ring-blue-500 rounded member-checkbox"
                                           data-name="{{ $member->first_name }} {{ $member->last_name }}"
                                           data-chapter="{{ $member->chapter }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $member->first_name }} {{ $member->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $member->chapter }} • {{ $member->phone }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                <span id="selected-count">0</span> members selected
                            </span>
                            <div class="space-x-2">
                                <button type="button" id="select-all-btn" class="text-sm text-blue-600 hover:text-blue-800">Select All</button>
                                <button type="button" id="clear-all-btn" class="text-sm text-red-600 hover:text-red-800">Clear All</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scheduling Options -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Delivery Options</h2>
                        <p class="text-gray-500 text-sm">Choose when to send your message</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="delivery_option" value="now" checked class="mr-2 text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700 font-medium">Send Now</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="delivery_option" value="schedule" class="mr-2 text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700 font-medium">Schedule for Later</span>
                        </label>
                    </div>

                    <div id="schedule-options" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="schedule_date" class="block text-sm font-semibold text-gray-800 mb-2">Date</label>
                                <input type="date" name="schedule_date" id="schedule_date" 
                                       class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            </div>
                            <div>
                                <label for="schedule_time" class="block text-sm font-semibold text-gray-800 mb-2">Time</label>
                                <input type="time" name="schedule_time" id="schedule_time" 
                                       class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" id="send-btn" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white font-bold rounded-2xl hover:from-purple-700 hover:via-indigo-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane mr-3"></i>
                    <span id="send-btn-text">Send SMS</span>
                    <div class="ml-3 w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin hidden" id="loading-spinner"></div>
                </button>
                <p class="text-sm text-gray-600 mt-4">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Messages are sent securely via MNotify
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character and SMS count
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    const smsCount = document.getElementById('sms-count');
    
    function updateCounts() {
        const length = messageTextarea.value.length;
        const smsCountValue = Math.ceil(length / 160) || 0;
        
        charCount.textContent = length;
        smsCount.textContent = smsCountValue;
        
        updateCostEstimation();
    }
    
    messageTextarea.addEventListener('input', updateCounts);

    // Template selection
    document.getElementById('template_id').addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const message = selectedOption.dataset.message;
            if (message) {
                messageTextarea.value = message;
                updateCounts();
            }
        }
    });

    // Recipient type selection
    document.querySelectorAll('input[name="recipient_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Update card styles
            document.querySelectorAll('.recipient-type-card').forEach(card => {
                card.classList.remove('border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50', 
                                    'border-purple-500', 'bg-purple-50', 'border-orange-500', 'bg-orange-50');
                card.classList.add('border-gray-200');
            });
            
            const parentCard = this.closest('.recipient-type-card');
            if (this.value === 'all') {
                parentCard.classList.add('border-blue-500', 'bg-blue-50');
            } else if (this.value === 'chapter') {
                parentCard.classList.add('border-green-500', 'bg-green-50');
            } else if (this.value === 'custom') {
                parentCard.classList.add('border-purple-500', 'bg-purple-50');
            } else if (this.value === 'members') {
                parentCard.classList.add('border-orange-500', 'bg-orange-50');
            }
            
            // Show/hide relevant sections
            document.getElementById('chapter-selection').classList.toggle('hidden', this.value !== 'chapter');
            document.getElementById('custom-selection').classList.toggle('hidden', this.value !== 'custom');
            
            updateCostEstimation();
        });
    });

    // Chapter selection
    document.getElementById('chapter').addEventListener('change', updateCostEstimation);

    // Custom recipients
    const memberCheckboxes = document.querySelectorAll('.member-checkbox');
    const selectedCount = document.getElementById('selected-count');
    
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.member-checkbox:checked').length;
        selectedCount.textContent = checked;
        updateCostEstimation();
    }
    
    memberCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Select/Clear all buttons
    document.getElementById('select-all-btn').addEventListener('click', function() {
        const visibleCheckboxes = document.querySelectorAll('.member-checkbox:not([style*="display: none"])');
        visibleCheckboxes.forEach(cb => cb.checked = true);
        updateSelectedCount();
    });
    
    document.getElementById('clear-all-btn').addEventListener('click', function() {
        memberCheckboxes.forEach(cb => cb.checked = false);
        updateSelectedCount();
    });

    // Member search
    document.getElementById('member-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const memberLabels = document.querySelectorAll('#member-list label');
        
        memberLabels.forEach(label => {
            const checkbox = label.querySelector('.member-checkbox');
            const name = checkbox.dataset.name.toLowerCase();
            const chapter = checkbox.dataset.chapter.toLowerCase();
            
            if (name.includes(searchTerm) || chapter.includes(searchTerm)) {
                label.style.display = 'flex';
            } else {
                label.style.display = 'none';
            }
        });
    });

    // Delivery options
    document.querySelectorAll('input[name="delivery_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const scheduleOptions = document.getElementById('schedule-options');
            const sendBtnText = document.getElementById('send-btn-text');
            
            if (this.value === 'schedule') {
                scheduleOptions.classList.remove('hidden');
                sendBtnText.textContent = 'Schedule SMS';
            } else {
                scheduleOptions.classList.add('hidden');
                sendBtnText.textContent = 'Send SMS';
            }
        });
    });

    // Cost estimation
    function updateCostEstimation() {
        const recipientType = document.querySelector('input[name="recipient_type"]:checked')?.value;
        let recipientCount = 0;
        
        if (recipientType === 'all' || recipientType === 'members') {
            recipientCount = {{ $members->count() }};
        } else if (recipientType === 'chapter') {
            const selectedChapter = document.getElementById('chapter').value;
            if (selectedChapter) {
                recipientCount = {{ $members->groupBy('chapter')->map->count()->toJson() }}[selectedChapter] || 0;
            }
        } else if (recipientType === 'custom') {
            recipientCount = document.querySelectorAll('.member-checkbox:checked').length;
        }
        
        const messageLength = messageTextarea.value.length;
        const smsCountValue = Math.ceil(messageLength / 160) || 1;
        const totalSms = recipientCount * smsCountValue;
        const costPerSms = 0.05; // GHS 0.05 per SMS
        const estimatedCost = totalSms * costPerSms;
        
        document.getElementById('recipient-count').textContent = recipientCount;
        document.getElementById('total-sms').textContent = totalSms;
        document.getElementById('estimated-cost').textContent = `GHS ${estimatedCost.toFixed(2)}`;
    }

    // Form submission
    document.getElementById('sms-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const sendBtn = document.getElementById('send-btn');
        const loadingSpinner = document.getElementById('loading-spinner');
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;
        
        // Add scheduling data if needed
        if (deliveryOption === 'schedule') {
            const scheduleDate = document.getElementById('schedule_date').value;
            const scheduleTime = document.getElementById('schedule_time').value;
            
            if (scheduleDate && scheduleTime) {
                formData.append('is_scheduled', '1');
                formData.append('scheduled_at', scheduleDate + ' ' + scheduleTime);
            }
        }
        
        // Show loading state
        sendBtn.disabled = true;
        loadingSpinner.classList.remove('hidden');
        
        fetch('{{ route("sms.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '{{ route("sms.index") }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            sendBtn.disabled = false;
            loadingSpinner.classList.add('hidden');
        });
    });
});
</script>
@endsection
