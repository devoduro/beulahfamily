@extends('components.public-layout')

@section('title', 'Register for ' . $program->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden mb-8 border border-gray-200/50">
            @php
                $programType = $program->programType;
                $gradientColors = $programType ? $programType->gradient_colors : ['from-blue-600', 'to-purple-600'];
                $displayIcon = $programType ? $programType->display_icon : 'fas fa-calendar-alt';
                $programTypeName = $programType ? $programType->name : $program->formatted_type;
            @endphp
            
            <div class="bg-gradient-to-r {{ implode(' ', $gradientColors) }} px-8 py-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>
                
                <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="text-white">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                <i class="{{ $displayIcon }} text-3xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold mb-1">{{ $program->name }}</h1>
                                <p class="text-white/80 text-lg">{{ $programTypeName }} Registration</p>
                            </div>
                        </div>
                        
                        <!-- Program Images/Flyers Display -->
                        @if($program->hasImages())
                            <div class="flex space-x-3 mt-4">
                                @foreach(array_slice($program->image_urls, 0, 3) as $imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $program->name }}" 
                                         class="w-20 h-20 rounded-xl object-cover border-2 border-white/30 shadow-lg">
                                @endforeach
                                @if(count($program->image_urls) > 3)
                                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border-2 border-white/30">
                                        <span class="text-white font-semibold">+{{ count($program->image_urls) - 3 }}</span>
                                    </div>
                                @endif
                            </div>
                        @elseif($program->hasFlyer())
                            <div class="mt-4">
                                <img src="{{ $program->flyer_url }}" alt="{{ $program->name }}" 
                                     class="w-32 h-32 rounded-xl object-cover border-2 border-white/30 shadow-lg">
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 lg:mt-0 lg:text-right space-y-3">
                        <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-3 text-white border border-white/30">
                            <i class="fas fa-calendar-alt mr-3 text-lg"></i>
                            <div class="text-left">
                                <div class="font-semibold">{{ $program->date_range }}</div>
                                @if($program->time_range)
                                    <div class="text-sm text-white/80">{{ $program->time_range }}</div>
                                @endif
                            </div>
                        </div>
                        
                        @if($program->venue)
                            <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-3 text-white border border-white/30">
                                <i class="fas fa-map-marker-alt mr-3 text-lg"></i>
                                <div class="text-left">
                                    <div class="font-semibold">Venue</div>
                                    <div class="text-sm text-white/80">{{ $program->venue }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($program->registration_fee > 0)
                            <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-3 text-white border border-white/30">
                                <i class="fas fa-money-bill-wave mr-3 text-lg"></i>
                                <div class="text-left">
                                    <div class="font-semibold">Registration Fee</div>
                                    <div class="text-sm text-white/80">₵{{ number_format($program->registration_fee, 2) }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="px-8 py-6">
                @if($program->description)
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-6 mb-6">
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $program->description }}</p>
                    </div>
                @endif
                
                <!-- Program Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    @if($program->registration_deadline)
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/50 rounded-2xl p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-amber-800 uppercase tracking-wide">Deadline</p>
                                    <p class="text-lg font-bold text-amber-900">{{ $program->registration_deadline->format('M j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($program->max_participants)
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200/50 rounded-2xl p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-green-800 uppercase tracking-wide">Capacity</p>
                                    <p class="text-lg font-bold text-green-900">{{ $program->max_participants }} spots</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($program->registration_fee > 0)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/50 rounded-2xl p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-blue-800 uppercase tracking-wide">Fee</p>
                                    <p class="text-lg font-bold text-blue-900">₵{{ number_format($program->registration_fee, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200/50 rounded-2xl p-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-gift text-white text-lg"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-semibold text-green-800 uppercase tracking-wide">Registration</p>
                                    <p class="text-lg font-bold text-green-900">Free Event</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dynamic Registration Form -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-gray-200/50">
            <!-- Form Header -->
            <div class="bg-gradient-to-r {{ implode(' ', $gradientColors) }} px-8 py-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">Registration Form</h2>
                        <p class="text-white/80">Please fill in all required information</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('programs.register.store', $program) }}" enctype="multipart/form-data" class="px-8 py-8">
                @csrf

                <!-- Member Selection Section (Optional) -->
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Member Information</h3>
                            <p class="text-gray-600">Optional - Select if you are a church member</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6">
                        <label for="member_id" class="block text-sm font-semibold text-gray-700 mb-3">
                            Are you a church member?
                        </label>
                        <select class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 @error('member_id') border-red-500 ring-red-200 @enderror" 
                                id="member_id" name="member_id">
                            <option value="">-- Select if you are a member --</option>
                            @if(isset($members))
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }} - {{ $member->phone }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('member_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Dynamic Registration Fields -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-r {{ implode(' ', $gradientColors) }} rounded-xl flex items-center justify-center">
                            <i class="{{ $displayIcon }} text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $programTypeName }} Information</h3>
                            <p class="text-gray-600">Please provide the required information</p>
                        </div>
                    </div>

                    @php
                        $registrationFields = $program->getRegistrationFieldsForType();
                        $fieldGroups = array_chunk($registrationFields, 3, true);
                    @endphp

                    @foreach($fieldGroups as $groupIndex => $fieldGroup)
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-{{ count($fieldGroup) > 2 ? '3' : (count($fieldGroup) == 2 ? '2' : '1') }} gap-6">
                                @foreach($fieldGroup as $fieldName => $fieldConfig)
                                    <x-dynamic-form-field 
                                        :field="$fieldConfig" 
                                        :name="$fieldName" 
                                        :value="old($fieldName)" 
                                        :errors="$errors" />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- File Upload Section (if enabled) -->
                @if($program->allow_file_uploads || ($programType && $programType->allow_file_uploads))
                    <div class="mt-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-upload text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">File Uploads</h3>
                                <p class="text-gray-600">Upload supporting documents or files</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6">
                            <label for="files" class="block text-sm font-semibold text-gray-700 mb-3">
                                Upload Files
                                @if($program->max_files)
                                    <span class="text-gray-500">(Maximum {{ $program->max_files }} files)</span>
                                @endif
                            </label>
                            <input type="file" 
                                   id="files" 
                                   name="files[]" 
                                   multiple
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all duration-200"
                                   @if($program->allowed_file_types) 
                                       accept="{{ implode(',', array_map(fn($type) => '.' . $type, $program->allowed_file_types)) }}"
                                   @endif>
                            @if($program->allowed_file_types)
                                <p class="mt-2 text-xs text-gray-500">
                                    Allowed file types: {{ implode(', ', $program->allowed_file_types) }}
                                    @if($program->max_file_size)
                                        | Maximum size: {{ number_format($program->max_file_size / 1024, 1) }}MB per file
                                    @endif
                                </p>
                            @endif
                            @error('files')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                @endif

                <!-- Terms and Conditions -->
                <div class="mt-8">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200/50">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" 
                                   id="terms_accepted" 
                                   name="terms_accepted" 
                                   value="1"
                                   class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 mt-1"
                                   required>
                            <div class="flex-1">
                                <label for="terms_accepted" class="text-sm font-semibold text-gray-700">
                                    I agree to the terms and conditions <span class="text-red-500">*</span>
                                </label>
                                @if($program->terms_and_conditions)
                                    <div class="mt-2 text-sm text-gray-600 max-h-32 overflow-y-auto bg-white/50 rounded-lg p-3">
                                        {{ $program->terms_and_conditions }}
                                    </div>
                                @else
                                    <p class="mt-1 text-sm text-gray-600">
                                        By registering, you agree to participate in {{ $program->name }} and provide accurate information.
                                    </p>
                                @endif
                            </div>
                        </div>
                        @error('terms_accepted')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-center">
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r {{ implode(' ', $gradientColors) }} text-white font-bold text-lg rounded-2xl hover:shadow-2xl hover:scale-105 transition-all duration-300 transform focus:outline-none focus:ring-4 focus:ring-purple-300">
                        <i class="fas fa-paper-plane mr-3"></i>
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 text-center">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/50">
                <div class="flex items-center justify-center space-x-4 text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                        <span class="text-sm">Secure Registration</span>
                    </div>
                    <div class="w-1 h-4 bg-gray-300"></div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        <span class="text-sm">Quick Process</span>
                    </div>
                    <div class="w-1 h-4 bg-gray-300"></div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-purple-500 mr-2"></i>
                        <span class="text-sm">Email Confirmation</span>
                    </div>
                </div>
                @if($program->contact_email || $program->contact_phone)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600 mb-2">Need help with registration?</p>
                        <div class="flex justify-center space-x-4">
                            @if($program->contact_email)
                                <a href="mailto:{{ $program->contact_email }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-envelope mr-1"></i>{{ $program->contact_email }}
                                </a>
                            @endif
                            @if($program->contact_phone)
                                <a href="tel:{{ $program->contact_phone }}" class="text-green-600 hover:text-green-800 text-sm">
                                    <i class="fas fa-phone mr-1"></i>{{ $program->contact_phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
