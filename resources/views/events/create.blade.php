@extends('components.app-layout')

@section('title', 'Create New Event')

@section('content')
@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="successToast">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="errorToast">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
@endif
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Enhanced Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl shadow-2xl mb-8">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative px-8 py-12">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-3xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white">Create New Event</h1>
                        <p class="text-blue-100 text-lg">Plan and organize church events</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50">
            <div class="p-8">
                <form method="POST" action="{{ route('store.event') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Information -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Event Details</h3>
                                <p class="text-gray-600">Basic information about your event</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                       placeholder="Enter event title">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Type -->
                            <div>
                                <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">Event Type *</label>
                                <select id="event_type" name="event_type" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('event_type') border-red-500 @enderror">
                                    <option value="">Select Event Type</option>
                                    <option value="service" {{ old('event_type') == 'service' ? 'selected' : '' }}>Church Service</option>
                                    <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                    <option value="conference" {{ old('event_type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="workshop" {{ old('event_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="social" {{ old('event_type') == 'social' ? 'selected' : '' }}>Social Event</option>
                                    <option value="outreach" {{ old('event_type') == 'outreach' ? 'selected' : '' }}>Outreach</option>
                                    <option value="fundraising" {{ old('event_type') == 'fundraising' ? 'selected' : '' }}>Fundraising</option>
                                    <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('event_type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ministry -->
                            <div>
                                <label for="ministry_id" class="block text-sm font-medium text-gray-700 mb-2">Ministry</label>
                                <select id="ministry_id" name="ministry_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ministry_id') border-red-500 @enderror">
                                    <option value="">Select Ministry (Optional)</option>
                                    @foreach($ministries as $ministry)
                                        <option value="{{ $ministry->id }}" {{ old('ministry_id') == $ministry->id ? 'selected' : '' }}>
                                            {{ $ministry->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ministry_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Event Status *</label>
                                <select id="status" name="status" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                    <option value="draft" {{ old('status', 'published') == 'draft' ? 'selected' : '' }}>Draft (Save for later)</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Visible to all)</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Draft events are only visible to admins</p>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date & Time -->
                            <div>
                                <label for="start_datetime" class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time *</label>
                                <input type="datetime-local" id="start_datetime" name="start_datetime" value="{{ old('start_datetime') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_datetime') border-red-500 @enderror">
                                @error('start_datetime')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date & Time -->
                            <div>
                                <label for="end_datetime" class="block text-sm font-medium text-gray-700 mb-2">End Date & Time</label>
                                <input type="datetime-local" id="end_datetime" name="end_datetime" value="{{ old('end_datetime') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_datetime') border-red-500 @enderror">
                                @error('end_datetime')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <div class="relative">
                                    <input type="text" id="location" name="location" value="{{ old('location') }}"
                                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror"
                                           placeholder="Enter event location">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                </div>
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="description" name="description" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                          placeholder="Describe the event details, agenda, and what attendees can expect...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Event Media & Documents -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-images text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Media & Documents</h3>
                                <p class="text-gray-600">Upload event flyer and program outline</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Flyer -->
                            <div>
                                <label for="flyer" class="block text-sm font-medium text-gray-700 mb-2">Event Flyer</label>
                                <div class="relative">
                                    <input type="file" id="flyer" name="flyer" accept="image/*"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('flyer') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Upload JPG, PNG, or GIF. Max size: 5MB</p>
                                @error('flyer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                
                                <!-- Flyer Preview -->
                                <div id="flyerPreview" class="mt-3 hidden">
                                    <div class="relative inline-block">
                                        <img id="flyerImage" src="" alt="Flyer Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                                        <button type="button" onclick="removeFlyerPreview()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Program Outline PDF -->
                            <div>
                                <label for="program_outline" class="block text-sm font-medium text-gray-700 mb-2">Program Outline (PDF)</label>
                                <div class="relative">
                                    <input type="file" id="program_outline" name="program_outline" accept=".pdf"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('program_outline') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-file-pdf text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Upload PDF file. Max size: 10MB</p>
                                @error('program_outline')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                
                                <!-- PDF Preview -->
                                <div id="pdfPreview" class="mt-3 hidden">
                                    <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg border border-gray-300">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                        <div class="flex-1">
                                            <p id="pdfFileName" class="text-sm font-medium text-gray-900"></p>
                                            <p id="pdfFileSize" class="text-xs text-gray-500"></p>
                                        </div>
                                        <button type="button" onclick="removePdfPreview()" class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="/events" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-calendar-plus mr-2"></i>Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss toast notifications
    const successToast = document.getElementById('successToast');
    const errorToast = document.getElementById('errorToast');
    
    if (successToast) {
        setTimeout(() => {
            successToast.style.opacity = '0';
            setTimeout(() => successToast.remove(), 300);
        }, 5000);
    }
    
    if (errorToast) {
        setTimeout(() => {
            errorToast.style.opacity = '0';
            setTimeout(() => errorToast.remove(), 300);
        }, 8000);
    }
    
    // Form validation
    const form = document.querySelector('form');
    const titleInput = document.getElementById('title');
    const eventTypeSelect = document.getElementById('event_type');
    const startDatetimeInput = document.getElementById('start_datetime');
    const endDatetimeInput = document.getElementById('end_datetime');
    
    // Auto-fill end datetime when start datetime is selected
    startDatetimeInput.addEventListener('change', function() {
        if (!endDatetimeInput.value && this.value) {
            // Add 2 hours to start datetime as default end datetime
            const startDate = new Date(this.value);
            startDate.setHours(startDate.getHours() + 2);
            
            // Format to datetime-local format
            const year = startDate.getFullYear();
            const month = String(startDate.getMonth() + 1).padStart(2, '0');
            const day = String(startDate.getDate()).padStart(2, '0');
            const hours = String(startDate.getHours()).padStart(2, '0');
            const minutes = String(startDate.getMinutes()).padStart(2, '0');
            
            endDatetimeInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const errors = [];
        
        // Validate required fields
        if (!titleInput.value.trim()) {
            errors.push('Event title is required');
            titleInput.classList.add('border-red-500');
            isValid = false;
        } else {
            titleInput.classList.remove('border-red-500');
        }
        
        if (!eventTypeSelect.value) {
            errors.push('Event type is required');
            eventTypeSelect.classList.add('border-red-500');
            isValid = false;
        } else {
            eventTypeSelect.classList.remove('border-red-500');
        }
        
        if (!startDatetimeInput.value) {
            errors.push('Start date and time is required');
            startDatetimeInput.classList.add('border-red-500');
            isValid = false;
        } else {
            startDatetimeInput.classList.remove('border-red-500');
            
            // Validate start date is not in the past
            const startDate = new Date(startDatetimeInput.value);
            const now = new Date();
            if (startDate < now) {
                errors.push('Start date cannot be in the past');
                startDatetimeInput.classList.add('border-red-500');
                isValid = false;
            }
        }
        
        // Validate end datetime is after start datetime
        if (endDatetimeInput.value && startDatetimeInput.value) {
            const startDate = new Date(startDatetimeInput.value);
            const endDate = new Date(endDatetimeInput.value);
            if (endDate <= startDate) {
                errors.push('End date must be after start date');
                endDatetimeInput.classList.add('border-red-500');
                isValid = false;
            } else {
                endDatetimeInput.classList.remove('border-red-500');
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            showToast(errors.join('<br>'), 'error');
        } else {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Event...';
            submitBtn.disabled = true;
            
            // Re-enable after 10 seconds as fallback
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 10000);
        }
    });
    
    // File upload handling
    const flyerInput = document.getElementById('flyer');
    const pdfInput = document.getElementById('program_outline');
    
    // Flyer preview
    flyerInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showToast('Please select a valid image file', 'error');
                this.value = '';
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showToast('Image file size must be less than 5MB', 'error');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('flyerImage').src = e.target.result;
                document.getElementById('flyerPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // PDF preview
    pdfInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (file.type !== 'application/pdf') {
                showToast('Please select a valid PDF file', 'error');
                this.value = '';
                return;
            }
            
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                showToast('PDF file size must be less than 10MB', 'error');
                this.value = '';
                return;
            }
            
            document.getElementById('pdfFileName').textContent = file.name;
            document.getElementById('pdfFileSize').textContent = formatFileSize(file.size);
            document.getElementById('pdfPreview').classList.remove('hidden');
        }
    });
    
    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'error' ? 'bg-red-500' : 
            type === 'success' ? 'bg-green-500' : 
            'bg-blue-500'
        } text-white`;
        
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${
                    type === 'error' ? 'fa-exclamation-circle' : 
                    type === 'success' ? 'fa-check-circle' : 
                    'fa-info-circle'
                } mr-2"></i>
                <div>${message}</div>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
});

// Global functions for preview removal
function removeFlyerPreview() {
    document.getElementById('flyer').value = '';
    document.getElementById('flyerPreview').classList.add('hidden');
}

function removePdfPreview() {
    document.getElementById('program_outline').value = '';
    document.getElementById('pdfPreview').classList.add('hidden');
}
</script>
@endsection
