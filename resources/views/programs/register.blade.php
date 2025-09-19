@extends('components.public-layout')

@section('title', 'Register for ' . $program->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="text-white">
                        <h1 class="text-3xl font-bold mb-2">{{ $program->name }}</h1>
                        <p class="text-blue-100 text-lg">Business Registration Form</p>
                    </div>
                    <div class="mt-4 lg:mt-0 lg:text-right">
                        <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 text-white">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ $program->date_range }}
                        </div>
                        @if($program->venue)
                            <div class="mt-2 text-blue-100">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $program->venue }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-8 py-6">
                @if($program->description)
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">{{ $program->description }}</p>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($program->registration_fee > 0)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800">Registration Fee</p>
                                    <p class="text-lg font-bold text-blue-900">₵{{ number_format($program->registration_fee, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($program->registration_deadline)
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-amber-800">Registration Deadline</p>
                                    <p class="text-lg font-bold text-amber-900">{{ $program->registration_deadline->format('M j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Progress Indicator -->
            <div class="bg-gray-50 px-8 py-4">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center text-blue-600">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">1</div>
                            <span class="ml-2 font-medium">Member Info</span>
                        </div>
                        <div class="w-8 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center text-gray-400">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-white text-xs font-bold">2</div>
                            <span class="ml-2">Business Details</span>
                        </div>
                        <div class="w-8 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center text-gray-400">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-white text-xs font-bold">3</div>
                            <span class="ml-2">Contact & Files</span>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('programs.register.store', $program) }}" enctype="multipart/form-data" class="px-8 py-8 space-y-8">
                @csrf

                <!-- Member Selection Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Member Information</h3>
                            <p class="text-gray-600">Optional - Select if you are a church member</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6">
                        <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Are you a church member?
                        </label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('member_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                id="member_id" name="member_id">
                            <option value="">Select if you are a member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->full_name }} - {{ $member->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('member_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Business Information Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Business Information</h3>
                            <p class="text-gray-600">Tell us about your business</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 space-y-6">
                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Name of Business <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('business_name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   id="business_name" 
                                   name="business_name" 
                                   value="{{ old('business_name') }}" 
                                   placeholder="Enter your business name"
                                   required>
                            @error('business_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Business Type <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('business_type') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                        id="business_type" name="business_type" required>
                                    <option value="">Select Business Type</option>
                                    @foreach($businessTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('business_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('business_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Other Business Type -->
                            <div id="other_business_type_div" style="display: none;">
                                <label for="business_type_other" class="block text-sm font-medium text-gray-700 mb-2">
                                    Specify Other Business Type
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('business_type_other') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="business_type_other" 
                                       name="business_type_other" 
                                       value="{{ old('business_type_other') }}"
                                       placeholder="Please specify your business type">
                                @error('business_type_other')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Services Offered -->
                        <div>
                            <label for="services_offered" class="block text-sm font-medium text-gray-700 mb-2">
                                Services Offered <span class="text-red-500">*</span>
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('services_offered') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      id="services_offered" 
                                      name="services_offered" 
                                      rows="4" 
                                      placeholder="Describe the services or products your business offers..."
                                      required>{{ old('services_offered') }}</textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                <span id="services_count">0</span> characters
                            </div>
                            @error('services_offered')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Address -->
                        <div>
                            <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Business Address or Location <span class="text-red-500">*</span>
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('business_address') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      id="business_address" 
                                      name="business_address" 
                                      rows="3" 
                                      placeholder="Enter your complete business address..."
                                      required>{{ old('business_address') }}</textarea>
                            @error('business_address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Contact Information</h3>
                            <p class="text-gray-600">How can we reach you?</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Contact Name -->
                            <div>
                                <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contact Person Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="contact_name" 
                                       name="contact_name" 
                                       value="{{ old('contact_name') }}" 
                                       placeholder="Enter contact person's name"
                                       required>
                                @error('contact_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Business Phone -->
                            <div>
                                <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Business Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('business_phone') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="business_phone" 
                                       name="business_phone" 
                                       value="{{ old('business_phone') }}" 
                                       placeholder="e.g., +233 24 123 4567"
                                       required>
                                @error('business_phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- WhatsApp -->
                            <div>
                                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    WhatsApp Number
                                </label>
                                <input type="tel" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('whatsapp_number') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="whatsapp_number" 
                                       name="whatsapp_number" 
                                       value="{{ old('whatsapp_number') }}"
                                       placeholder="If different from business phone">
                                <p class="mt-1 text-xs text-gray-500">Leave blank if same as business phone</p>
                                @error('whatsapp_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="business@example.com"
                                       required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                @if($program->allow_file_uploads)
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-upload text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Business Files</h3>
                            <p class="text-gray-600">Upload your business flyer or promotional materials</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6">
                        <label for="files" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Files (Optional)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="files" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload files</span>
                                        <input type="file" 
                                               class="sr-only @error('files.*') border-red-500 @enderror" 
                                               id="files" 
                                               name="files[]" 
                                               multiple 
                                               accept=".pdf,.jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.mp3,.wav">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PDF, images, audio, or video up to {{ $program->max_file_size }}MB each (max {{ $program->max_files }} files)
                                </p>
                            </div>
                        </div>
                        @error('files.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- File preview area -->
                        <div id="file-preview" class="mt-4 space-y-2 hidden">
                            <h4 class="text-sm font-medium text-gray-700">Selected Files:</h4>
                            <div id="file-list" class="space-y-1"></div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Additional Information Section -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Additional Information</h3>
                            <p class="text-gray-600">Tell us more about your business</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 space-y-6">
                        <!-- Special Offers -->
                        <div>
                            <label for="special_offers" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Offers or Promotions for Conference Attendees
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('special_offers') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      id="special_offers" 
                                      name="special_offers" 
                                      rows="3"
                                      placeholder="Describe any special offers, discounts, or promotions you'll provide to conference attendees...">{{ old('special_offers') }}</textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                <span id="offers_count">0</span> characters
                            </div>
                            @error('special_offers')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Additional Info -->
                        <div>
                            <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Information
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('additional_info') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                      id="additional_info" 
                                      name="additional_info" 
                                      rows="3"
                                      placeholder="Any other information you'd like to share about your business...">{{ old('additional_info') }}</textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                <span id="additional_count">0</span> characters
                            </div>
                            @error('additional_info')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                @if($program->terms_and_conditions)
                <div class="space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-contract text-blue-600 text-xl mt-1"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-blue-900 mb-3">Terms and Conditions</h4>
                                <div class="bg-white rounded-lg p-4 mb-4 max-h-40 overflow-y-auto">
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $program->terms_and_conditions }}</p>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" 
                                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('accept_terms') border-red-500 @enderror" 
                                           id="accept_terms" 
                                           name="accept_terms"
                                           required>
                                    <label for="accept_terms" class="text-sm text-gray-700">
                                        I have read and agree to the terms and conditions <span class="text-red-500">*</span>
                                    </label>
                                </div>
                                @error('accept_terms')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 pt-6 border-t border-gray-200">
                    <a href="{{ route('programs.show', $program) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Program
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Business type toggle functionality
    const businessTypeSelect = document.getElementById('business_type');
    const otherBusinessTypeDiv = document.getElementById('other_business_type_div');
    const otherBusinessTypeInput = document.getElementById('business_type_other');

    function toggleOtherBusinessType() {
        if (businessTypeSelect.value === 'other') {
            otherBusinessTypeDiv.style.display = 'block';
            otherBusinessTypeInput.required = true;
        } else {
            otherBusinessTypeDiv.style.display = 'none';
            otherBusinessTypeInput.required = false;
            otherBusinessTypeInput.value = '';
        }
    }

    businessTypeSelect.addEventListener('change', toggleOtherBusinessType);
    toggleOtherBusinessType(); // Check on page load

    // Character counting functionality
    function setupCharacterCounter(textareaId, counterId) {
        const textarea = document.getElementById(textareaId);
        const counter = document.getElementById(counterId);
        
        if (textarea && counter) {
            function updateCount() {
                const count = textarea.value.length;
                counter.textContent = count;
                
                // Color coding based on length
                if (count > 500) {
                    counter.className = 'text-red-600 font-medium';
                } else if (count > 300) {
                    counter.className = 'text-yellow-600 font-medium';
                } else {
                    counter.className = 'text-gray-500';
                }
            }
            
            textarea.addEventListener('input', updateCount);
            updateCount(); // Initial count
        }
    }

    // Setup character counters
    setupCharacterCounter('services_offered', 'services_count');
    setupCharacterCounter('special_offers', 'offers_count');
    setupCharacterCounter('additional_info', 'additional_count');

    // File upload functionality
    const fileInput = document.getElementById('files');
    const filePreview = document.getElementById('file-preview');
    const fileList = document.getElementById('file-list');

    if (fileInput && filePreview && fileList) {
        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            if (files.length > 0) {
                filePreview.classList.remove('hidden');
                fileList.innerHTML = '';
                
                files.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between bg-white rounded-lg p-3 border border-gray-200';
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'flex items-center space-x-3';
                    
                    const fileIcon = document.createElement('div');
                    fileIcon.className = 'flex-shrink-0';
                    
                    // Determine file type icon
                    let iconClass = 'fas fa-file text-gray-400';
                    if (file.type.startsWith('image/')) {
                        iconClass = 'fas fa-image text-green-500';
                    } else if (file.type.startsWith('video/')) {
                        iconClass = 'fas fa-video text-blue-500';
                    } else if (file.type.startsWith('audio/')) {
                        iconClass = 'fas fa-music text-purple-500';
                    } else if (file.type === 'application/pdf') {
                        iconClass = 'fas fa-file-pdf text-red-500';
                    }
                    
                    fileIcon.innerHTML = `<i class="${iconClass}"></i>`;
                    
                    const fileDetails = document.createElement('div');
                    fileDetails.innerHTML = `
                        <p class="text-sm font-medium text-gray-900">${file.name}</p>
                        <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    `;
                    
                    fileInfo.appendChild(fileIcon);
                    fileInfo.appendChild(fileDetails);
                    
                    const removeButton = document.createElement('button');
                    removeButton.type = 'button';
                    removeButton.className = 'text-red-500 hover:text-red-700 transition-colors';
                    removeButton.innerHTML = '<i class="fas fa-times"></i>';
                    removeButton.onclick = function() {
                        // Remove file from input (this is tricky with file inputs)
                        const dt = new DataTransfer();
                        const { files } = fileInput;
                        
                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            if (index !== i) dt.items.add(file);
                        }
                        
                        fileInput.files = dt.files;
                        fileItem.remove();
                        
                        if (fileInput.files.length === 0) {
                            filePreview.classList.add('hidden');
                        }
                    };
                    
                    fileItem.appendChild(fileInfo);
                    fileItem.appendChild(removeButton);
                    fileList.appendChild(fileItem);
                });
            } else {
                filePreview.classList.add('hidden');
            }
        });
    }

    // Form validation enhancement
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    
                    // Remove error styling on input
                    field.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                        }
                    });
                } else {
                    field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = form.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Please fill in all required fields</span>
                        <button type="button" class="ml-4 text-red-500 hover:text-red-700" onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                document.body.appendChild(errorDiv);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (errorDiv.parentElement) {
                        errorDiv.remove();
                    }
                }, 5000);
            }
        });
    }

    // Progress indicator update (visual enhancement)
    const sections = document.querySelectorAll('[data-section]');
    const progressSteps = document.querySelectorAll('.progress-step');
    
    function updateProgress() {
        // This could be enhanced to show real progress based on filled fields
        // For now, it's just visual
    }
});
</script>
@endsection
