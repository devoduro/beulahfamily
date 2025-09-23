@extends('components.app-layout')

@section('title', 'Create Program')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Enhanced Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl shadow-2xl mb-8">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
            <div class="relative px-8 py-12">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-plus-circle text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-white">Create New Program</h1>
                            <p class="text-blue-100 text-lg">Design and configure your program with advanced settings</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.programs.index') }}" class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-2xl hover:bg-white/30 transition-all duration-200 border border-white/30">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Programs
                    </a>
                </div>
            </div>
        </div>

        <!-- Enhanced Form Container -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50">
            <div class="p-8">
                <!-- Progress Indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                            <span class="text-sm font-medium text-blue-600">Basic Info</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-4 rounded-full">
                            <div class="h-1 bg-blue-600 rounded-full" style="width: 20%"></div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                            <span class="text-sm text-gray-500">Schedule</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-4 rounded-full"></div>
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                            <span class="text-sm text-gray-500">Settings</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 mx-4 rounded-full"></div>
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-bold">4</div>
                            <span class="text-sm text-gray-500">Review</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.programs.store') }}" enctype="multipart/form-data" id="programForm">
                    @csrf

                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="step1">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                                    <p class="text-gray-600">Set up the fundamental details of your program</p>
                                </div>
                            </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Program Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Program Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                            <!-- Program Type Cards -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">Program Type *</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    @php
                                        $programTypes = [
                                            'ergates_conference' => ['name' => 'ERGATES Conference', 'icon' => 'fas fa-handshake', 'color' => 'from-orange-500 to-red-500', 'desc' => 'Business networking and entrepreneurship'],
                                            'annual_retreat' => ['name' => 'Annual Retreat', 'icon' => 'fas fa-mountain', 'color' => 'from-green-500 to-blue-500', 'desc' => 'Spiritual retreat and fellowship'],
                                            'conference' => ['name' => 'Conference', 'icon' => 'fas fa-users', 'color' => 'from-blue-500 to-purple-500', 'desc' => 'Large gathering with multiple sessions'],
                                            'workshop' => ['name' => 'Workshop', 'icon' => 'fas fa-tools', 'color' => 'from-yellow-500 to-orange-500', 'desc' => 'Hands-on learning experience'],
                                            'seminar' => ['name' => 'Seminar', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'from-purple-500 to-pink-500', 'desc' => 'Educational presentation'],
                                            'other' => ['name' => 'Other', 'icon' => 'fas fa-ellipsis-h', 'color' => 'from-gray-500 to-slate-500', 'desc' => 'Custom program type']
                                        ];
                                    @endphp
                                    @foreach($programTypes as $key => $type)
                                        <label class="program-type-card cursor-pointer">
                                            <input type="radio" name="type" value="{{ $key }}" class="sr-only" {{ old('type') == $key ? 'checked' : '' }} required>
                                            <div class="relative p-4 border-2 border-gray-200 rounded-xl hover:border-blue-300 transition-all duration-200 hover:shadow-lg group">
                                                <div class="flex flex-col items-center text-center">
                                                    <div class="w-12 h-12 bg-gradient-to-r {{ $type['color'] }} rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                                        <i class="{{ $type['icon'] }} text-white text-lg"></i>
                                                    </div>
                                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $type['name'] }}</h4>
                                                    <p class="text-xs text-gray-500 leading-tight">{{ $type['desc'] }}</p>
                                                </div>
                                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <div class="w-3 h-3 bg-blue-600 rounded-full scale-0 transition-transform"></div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                            <!-- Program Flyer Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Program Flyer/Images</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-blue-400 transition-colors">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                        </div>
                                        <input type="file" name="flyer" id="flyer" accept="image/*,application/pdf" class="hidden" onchange="handleFileUpload(this)">
                                        <label for="flyer" class="cursor-pointer">
                                            <span class="text-sm font-medium text-blue-600 hover:text-blue-500">Click to upload</span>
                                            <span class="text-sm text-gray-500"> or drag and drop</span>
                                        </label>
                                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, PDF up to 10MB</p>
                                    </div>
                                    <div id="filePreview" class="mt-4 hidden">
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <i class="fas fa-file-image text-blue-500"></i>
                                                <span class="text-sm font-medium" id="fileName"></span>
                                            </div>
                                            <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Program Description</label>
                                <div class="relative">
                                    <textarea id="description" name="description" rows="5" maxlength="1000" placeholder="Describe your program, its objectives, target audience, and what participants can expect..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror resize-none">{{ old('description') }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                        <span id="charCount">0</span>/1000
                                    </div>
                                </div>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="nextStep(2)" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Next: Schedule & Venue
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Date & Time Information -->
                <div class="form-step hidden" id="step2">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Schedule & Venue</h3>
                                <p class="text-gray-600">Set the timing and location for your program</p>
                            </div>
                        </div>
                    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <div class="relative">
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                                @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                <div class="relative">
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                                @error('end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                <div class="relative">
                                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_time') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-clock text-gray-400"></i>
                                    </div>
                                </div>
                                @error('start_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                <div class="relative">
                                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_time') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-clock text-gray-400"></i>
                                    </div>
                                </div>
                                @error('end_time')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Venue -->
                            <div class="md:col-span-2">
                                <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue/Location</label>
                                <div class="relative">
                                    <input type="text" id="venue" name="venue" value="{{ old('venue') }}" placeholder="Enter the venue or location for your program"
                                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('venue') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                </div>
                                @error('venue')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep(1)" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Previous
                            </button>
                            <button type="button" onclick="nextStep(3)" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Next: Registration Settings
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Registration Settings -->
                <div class="form-step hidden" id="step3">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-cogs text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Registration Settings</h3>
                                <p class="text-gray-600">Configure registration options and file uploads</p>
                            </div>
                        </div>
                    
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Registration Fee -->
                            <div>
                                <label for="registration_fee" class="block text-sm font-medium text-gray-700 mb-2">Registration Fee</label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" id="registration_fee" name="registration_fee" value="{{ old('registration_fee', 0) }}" placeholder="0.00"
                                           class="w-full px-4 py-3 pl-8 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_fee') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">₵</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Set to 0 for free programs</p>
                                @error('registration_fee')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Participants -->
                            <div>
                                <label for="max_participants" class="block text-sm font-medium text-gray-700 mb-2">Max Participants</label>
                                <div class="relative">
                                    <input type="number" min="1" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" placeholder="Unlimited"
                                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('max_participants') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-users text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Leave empty for unlimited</p>
                                @error('max_participants')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Registration Deadline -->
                            <div>
                                <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-2">Registration Deadline</label>
                                <div class="relative">
                                    <input type="date" id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('registration_deadline') border-red-500 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-times text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Optional deadline</p>
                                @error('registration_deadline')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- File Upload Settings -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">File Upload Settings</h4>
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Allow File Uploads -->
                                    <div class="md:col-span-3">
                                        <label class="flex items-center p-4 bg-white rounded-xl border-2 border-gray-200 hover:border-blue-300 transition-colors cursor-pointer">
                                            <input type="checkbox" name="allow_file_uploads" value="1" {{ old('allow_file_uploads') ? 'checked' : '' }} id="allowUploads"
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <div class="ml-3">
                                                <span class="text-sm font-medium text-gray-900">Allow File Uploads</span>
                                                <p class="text-xs text-gray-500">Enable participants to upload documents, images, or other files</p>
                                            </div>
                                        </label>
                                    </div>

                                    <div id="uploadSettings" class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                                        <!-- Max File Size -->
                                        <div>
                                            <label for="max_file_size" class="block text-sm font-medium text-gray-700 mb-2">Max File Size (MB)</label>
                                            <select id="max_file_size" name="max_file_size" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="5" {{ old('max_file_size', 10) == 5 ? 'selected' : '' }}>5 MB</option>
                                                <option value="10" {{ old('max_file_size', 10) == 10 ? 'selected' : '' }}>10 MB</option>
                                                <option value="25" {{ old('max_file_size', 10) == 25 ? 'selected' : '' }}>25 MB</option>
                                                <option value="50" {{ old('max_file_size', 10) == 50 ? 'selected' : '' }}>50 MB</option>
                                                <option value="100" {{ old('max_file_size', 10) == 100 ? 'selected' : '' }}>100 MB</option>
                                            </select>
                                        </div>

                                        <!-- Max Files -->
                                        <div>
                                            <label for="max_files" class="block text-sm font-medium text-gray-700 mb-2">Max Files per Registration</label>
                                            <select id="max_files" name="max_files" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="1" {{ old('max_files', 5) == 1 ? 'selected' : '' }}>1 File</option>
                                                <option value="3" {{ old('max_files', 5) == 3 ? 'selected' : '' }}>3 Files</option>
                                                <option value="5" {{ old('max_files', 5) == 5 ? 'selected' : '' }}>5 Files</option>
                                                <option value="10" {{ old('max_files', 5) == 10 ? 'selected' : '' }}>10 Files</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contact Email -->
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                                    <div class="relative">
                                        <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}" placeholder="contact@example.com"
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_email') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('contact_email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Contact Phone -->
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                                    <div class="relative">
                                        <input type="tel" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+233 XX XXX XXXX"
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('contact_phone') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('contact_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep(2)" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Previous
                            </button>
                            <button type="button" onclick="nextStep(4)" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Next: Review & Submit
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Review & Submit -->
                <div class="form-step hidden" id="step4">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Review & Submit</h3>
                                <p class="text-gray-600">Review your program details and add terms & conditions</p>
                            </div>
                        </div>

                        <!-- Program Summary -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Program Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-tag text-blue-500"></i>
                                        <span class="text-sm text-gray-600">Name:</span>
                                        <span class="font-medium" id="summaryName">-</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-layer-group text-purple-500"></i>
                                        <span class="text-sm text-gray-600">Type:</span>
                                        <span class="font-medium" id="summaryType">-</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-calendar text-green-500"></i>
                                        <span class="text-sm text-gray-600">Dates:</span>
                                        <span class="font-medium" id="summaryDates">-</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-map-marker-alt text-red-500"></i>
                                        <span class="text-sm text-gray-600">Venue:</span>
                                        <span class="font-medium" id="summaryVenue">-</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-money-bill text-yellow-500"></i>
                                        <span class="text-sm text-gray-600">Fee:</span>
                                        <span class="font-medium" id="summaryFee">-</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-users text-indigo-500"></i>
                                        <span class="text-sm text-gray-600">Max Participants:</span>
                                        <span class="font-medium" id="summaryMaxParticipants">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div>
                            <label for="terms_and_conditions" class="block text-sm font-medium text-gray-700 mb-2">Terms and Conditions (Optional)</label>
                            <div class="relative">
                                <textarea id="terms_and_conditions" name="terms_and_conditions" rows="8" maxlength="2000" placeholder="Enter any terms and conditions for registration..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('terms_and_conditions') border-red-500 @enderror resize-none">{{ old('terms_and_conditions') }}</textarea>
                                <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                    <span id="termsCharCount">0</span>/2000
                                </div>
                            </div>
                            @error('terms_and_conditions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep(3)" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Previous
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>Create Program
                            </button>
                        </div>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced JavaScript -->
<script>
let currentStep = 1;
const totalSteps = 4;

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    updateProgressIndicator();
    setupFormValidation();
    setupFileUpload();
    setupCharacterCounters();
    setupProgramTypeSelection();
    setupFileUploadToggle();
});

// Step navigation functions
function nextStep(step) {
    if (validateCurrentStep()) {
        document.getElementById(`step${currentStep}`).classList.add('hidden');
        document.getElementById(`step${step}`).classList.remove('hidden');
        currentStep = step;
        updateProgressIndicator();
        
        if (step === 4) {
            updateSummary();
        }
        
        // Scroll to top
        document.querySelector('.form-step.active, .form-step:not(.hidden)').scrollIntoView({ behavior: 'smooth' });
    }
}

function prevStep(step) {
    document.getElementById(`step${currentStep}`).classList.add('hidden');
    document.getElementById(`step${step}`).classList.remove('hidden');
    currentStep = step;
    updateProgressIndicator();
    
    // Scroll to top
    document.querySelector('.form-step.active, .form-step:not(.hidden)').scrollIntoView({ behavior: 'smooth' });
}

// Update progress indicator
function updateProgressIndicator() {
    const progressBar = document.querySelector('.h-1.bg-blue-600');
    const percentage = (currentStep / totalSteps) * 100;
    progressBar.style.width = percentage + '%';
    
    // Update step indicators
    for (let i = 1; i <= totalSteps; i++) {
        const stepIndicator = document.querySelector(`.flex.items-center.space-x-4:nth-child(${i * 2 - 1}) .w-8.h-8`);
        const stepText = document.querySelector(`.flex.items-center.space-x-4:nth-child(${i * 2 - 1}) span`);
        
        if (i <= currentStep) {
            stepIndicator.classList.remove('bg-gray-200', 'text-gray-500');
            stepIndicator.classList.add('bg-blue-600', 'text-white');
            stepText.classList.remove('text-gray-500');
            stepText.classList.add('text-blue-600', 'font-medium');
        } else {
            stepIndicator.classList.remove('bg-blue-600', 'text-white');
            stepIndicator.classList.add('bg-gray-200', 'text-gray-500');
            stepText.classList.remove('text-blue-600', 'font-medium');
            stepText.classList.add('text-gray-500');
        }
    }
}

// Form validation
function validateCurrentStep() {
    const currentStepElement = document.getElementById(`step${currentStep}`);
    const requiredFields = currentStepElement.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
            
            // Show error message
            let errorMsg = field.parentNode.querySelector('.text-red-500');
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'text-red-500 text-sm mt-1';
                errorMsg.textContent = 'This field is required';
                field.parentNode.appendChild(errorMsg);
            }
        } else {
            field.classList.remove('border-red-500');
            // Remove error message
            const errorMsg = field.parentNode.querySelector('.text-red-500');
            if (errorMsg && errorMsg.textContent === 'This field is required') {
                errorMsg.remove();
            }
        }
    });
    
    if (!isValid) {
        showToast('Please fill in all required fields', 'error');
    }
    
    return isValid;
}

// Setup form validation
function setupFormValidation() {
    const form = document.getElementById('programForm');
    form.addEventListener('submit', function(e) {
        if (!validateCurrentStep()) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Program...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });
}

// File upload handling
function handleFileUpload(input) {
    const file = input.files[0];
    if (file) {
        const preview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        
        fileName.textContent = file.name;
        preview.classList.remove('hidden');
    }
}

function removeFile() {
    const input = document.getElementById('flyer');
    const preview = document.getElementById('filePreview');
    
    input.value = '';
    preview.classList.add('hidden');
}

function setupFileUpload() {
    const dropZone = document.querySelector('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            document.getElementById('flyer').files = files;
            handleFileUpload(document.getElementById('flyer'));
        }
    }
}

// Character counters
function setupCharacterCounters() {
    const description = document.getElementById('description');
    const terms = document.getElementById('terms_and_conditions');
    
    description.addEventListener('input', function() {
        const count = this.value.length;
        const counter = document.getElementById('charCount');
        counter.textContent = count;
        
        if (count > 800) {
            counter.classList.add('text-red-500');
        } else if (count > 600) {
            counter.classList.add('text-yellow-500');
        } else {
            counter.classList.remove('text-red-500', 'text-yellow-500');
        }
    });
    
    terms.addEventListener('input', function() {
        const count = this.value.length;
        const counter = document.getElementById('termsCharCount');
        counter.textContent = count;
        
        if (count > 1600) {
            counter.classList.add('text-red-500');
        } else if (count > 1200) {
            counter.classList.add('text-yellow-500');
        } else {
            counter.classList.remove('text-red-500', 'text-yellow-500');
        }
    });
}

// Program type selection
function setupProgramTypeSelection() {
    const typeCards = document.querySelectorAll('.program-type-card');
    
    typeCards.forEach(card => {
        const input = card.querySelector('input[type="radio"]');
        const cardDiv = card.querySelector('div');
        const indicator = cardDiv.querySelector('.absolute .w-3');
        
        card.addEventListener('click', function() {
            // Reset all cards
            typeCards.forEach(c => {
                c.querySelector('div').classList.remove('border-blue-500', 'bg-blue-50');
                c.querySelector('.absolute .w-3').classList.remove('scale-100');
                c.querySelector('.absolute .w-3').classList.add('scale-0');
            });
            
            // Activate selected card
            cardDiv.classList.add('border-blue-500', 'bg-blue-50');
            indicator.classList.remove('scale-0');
            indicator.classList.add('scale-100');
        });
        
        // Check if already selected
        if (input.checked) {
            cardDiv.classList.add('border-blue-500', 'bg-blue-50');
            indicator.classList.remove('scale-0');
            indicator.classList.add('scale-100');
        }
    });
}

// File upload toggle
function setupFileUploadToggle() {
    const checkbox = document.getElementById('allowUploads');
    const settings = document.getElementById('uploadSettings');
    
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            settings.style.display = 'grid';
        } else {
            settings.style.display = 'none';
        }
    });
    
    // Check initial state
    if (checkbox.checked) {
        settings.style.display = 'grid';
    }
}

// Update summary
function updateSummary() {
    const name = document.getElementById('name').value || 'Not specified';
    const type = document.querySelector('input[name="type"]:checked');
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const venue = document.getElementById('venue').value || 'Not specified';
    const fee = document.getElementById('registration_fee').value;
    const maxParticipants = document.getElementById('max_participants').value || 'Unlimited';
    
    document.getElementById('summaryName').textContent = name;
    document.getElementById('summaryType').textContent = type ? type.nextElementSibling.querySelector('h4').textContent : 'Not selected';
    
    let dateRange = 'Not specified';
    if (startDate && endDate) {
        dateRange = `${new Date(startDate).toLocaleDateString()} - ${new Date(endDate).toLocaleDateString()}`;
    } else if (startDate) {
        dateRange = new Date(startDate).toLocaleDateString();
    }
    document.getElementById('summaryDates').textContent = dateRange;
    
    document.getElementById('summaryVenue').textContent = venue;
    document.getElementById('summaryFee').textContent = fee > 0 ? `₵${parseFloat(fee).toFixed(2)}` : 'Free';
    document.getElementById('summaryMaxParticipants').textContent = maxParticipants;
}

// Toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    if (type === 'error') {
        toast.classList.add('bg-red-500', 'text-white');
        toast.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
    } else {
        toast.classList.add('bg-blue-500', 'text-white');
        toast.innerHTML = `<i class="fas fa-info-circle mr-2"></i>${message}`;
    }
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}
</script>

@endsection
