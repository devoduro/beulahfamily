@extends('components.app-layout')

@section('title', 'Edit Program - ' . $program->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Program</h1>
                <p class="text-gray-600">Update program details and settings</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.programs.show', $program) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-eye mr-2"></i>View Program
                </a>
                <a href="{{ route('admin.programs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Programs
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form method="POST" action="{{ route('admin.programs.update', $program) }}">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Program Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Program Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $program->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                            <select id="type" name="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="conference" {{ old('type', $program->type) == 'conference' ? 'selected' : '' }}>Conference</option>
                                <option value="workshop" {{ old('type', $program->type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="seminar" {{ old('type', $program->type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="retreat" {{ old('type', $program->type) == 'retreat' ? 'selected' : '' }}>Retreat</option>
                                <option value="other" {{ old('type', $program->type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status', $program->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $program->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="ongoing" {{ old('status', $program->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $program->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $program->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $program->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Date & Time Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Date & Time Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $program->start_date?->format('Y-m-d')) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $program->end_date?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $program->start_time) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-500 @enderror">
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $program->end_time) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-500 @enderror">
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Location Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Venue -->
                        <div class="md:col-span-2">
                            <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
                            <input type="text" id="venue" name="venue" value="{{ old('venue', $program->venue) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('venue') border-red-500 @enderror">
                            @error('venue')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $program->address) }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Registration Settings -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Registration Settings
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Registration Fee -->
                        <div>
                            <label for="registration_fee" class="block text-sm font-medium text-gray-700 mb-2">Registration Fee (GHS)</label>
                            <input type="number" id="registration_fee" name="registration_fee" value="{{ old('registration_fee', $program->registration_fee) }}" min="0" step="0.01"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_fee') border-red-500 @enderror">
                            @error('registration_fee')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Participants -->
                        <div>
                            <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Max Participants</label>
                            <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $program->max_participants) }}" min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_participants') border-red-500 @enderror">
                            @error('max_participants')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Registration Deadline -->
                        <div>
                            <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-2">Registration Deadline</label>
                            <input type="date" id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline', $program->registration_deadline?->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_deadline') border-red-500 @enderror">
                            @error('registration_deadline')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Registration Open -->
                        <div>
                            <label for="registration_open" class="block text-sm font-medium text-gray-700 mb-2">Registration Status</label>
                            <select id="registration_open" name="registration_open"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_open') border-red-500 @enderror">
                                <option value="1" {{ old('registration_open', $program->registration_open) == '1' ? 'selected' : '' }}>Open</option>
                                <option value="0" {{ old('registration_open', $program->registration_open) == '0' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('registration_open')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- File Upload Settings -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        File Upload Settings
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Allow File Uploads -->
                        <div>
                            <label for="allow_file_uploads" class="block text-sm font-medium text-gray-700 mb-2">Allow File Uploads</label>
                            <select id="allow_file_uploads" name="allow_file_uploads"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('allow_file_uploads') border-red-500 @enderror">
                                <option value="1" {{ old('allow_file_uploads', $program->allow_file_uploads) == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('allow_file_uploads', $program->allow_file_uploads) == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('allow_file_uploads')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Files -->
                        <div>
                            <label for="max_files" class="block text-sm font-medium text-gray-700 mb-2">Max Files</label>
                            <input type="number" id="max_files" name="max_files" value="{{ old('max_files', $program->max_files ?? 5) }}" min="1" max="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_files') border-red-500 @enderror">
                            @error('max_files')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max File Size -->
                        <div>
                            <label for="max_file_size" class="block text-sm font-medium text-gray-700 mb-2">Max File Size (MB)</label>
                            <input type="number" id="max_file_size" name="max_file_size" value="{{ old('max_file_size', $program->max_file_size ?? 100) }}" min="1" max="500"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_file_size') border-red-500 @enderror">
                            @error('max_file_size')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        Terms and Conditions
                    </h3>
                    
                    <div>
                        <label for="terms_and_conditions" class="block text-sm font-medium text-gray-700 mb-2">Terms and Conditions</label>
                        <textarea id="terms_and_conditions" name="terms_and_conditions" rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('terms_and_conditions') border-red-500 @enderror"
                                  placeholder="Enter terms and conditions for registration...">{{ old('terms_and_conditions', $program->terms_and_conditions) }}</textarea>
                        @error('terms_and_conditions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.programs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" name="action" value="save" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Program
                        </button>
                        <button type="submit" name="action" value="save_and_view" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-2"></i>Update & View
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill end date when start date is selected
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    startDateInput.addEventListener('change', function() {
        if (!endDateInput.value) {
            endDateInput.value = this.value;
        }
    });
    
    // Auto-fill end time when start time is selected
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    startTimeInput.addEventListener('change', function() {
        if (!endTimeInput.value && this.value) {
            // Add 2 hours to start time as default end time
            const startTime = new Date('2000-01-01 ' + this.value);
            startTime.setHours(startTime.getHours() + 2);
            const endTime = startTime.toTimeString().slice(0, 5);
            endTimeInput.value = endTime;
        }
    });
    
    // Toggle file upload fields based on allow_file_uploads
    const allowFileUploadsSelect = document.getElementById('allow_file_uploads');
    const maxFilesInput = document.getElementById('max_files');
    const maxFileSizeInput = document.getElementById('max_file_size');
    
    function toggleFileUploadFields() {
        const isEnabled = allowFileUploadsSelect.value === '1';
        maxFilesInput.disabled = !isEnabled;
        maxFileSizeInput.disabled = !isEnabled;
        
        if (!isEnabled) {
            maxFilesInput.style.opacity = '0.5';
            maxFileSizeInput.style.opacity = '0.5';
        } else {
            maxFilesInput.style.opacity = '1';
            maxFileSizeInput.style.opacity = '1';
        }
    }
    
    allowFileUploadsSelect.addEventListener('change', toggleFileUploadFields);
    toggleFileUploadFields(); // Initial call
});
</script>
@endsection
