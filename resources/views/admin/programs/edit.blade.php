@extends('components.app-layout')

@section('title', 'Edit Program - ' . $program->name)

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
                            <i class="fas fa-edit text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-white">Edit Program</h1>
                            <p class="text-blue-100 text-lg">{{ $program->name }}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-500/80',
                                        'published' => 'bg-green-500/80',
                                        'ongoing' => 'bg-blue-500/80',
                                        'completed' => 'bg-purple-500/80',
                                        'cancelled' => 'bg-red-500/80'
                                    ];
                                    $statusColor = $statusColors[$program->status] ?? 'bg-gray-500/80';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }} text-white">
                                    {{ ucfirst($program->status) }}
                                </span>
                                <span class="text-blue-200 text-sm">
                                    <i class="fas fa-users mr-1"></i>{{ $program->registrations->count() }} registrations
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('admin.programs.show', $program) }}" class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-2xl hover:bg-white/30 transition-all duration-200 border border-white/30">
                            <i class="fas fa-eye mr-2"></i>View Program
                        </a>
                        <a href="{{ route('admin.programs.index') }}" class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-2xl hover:bg-white/30 transition-all duration-200 border border-white/30">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Programs
                        </a>
                    </div>
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
                            <div class="h-1 bg-blue-600 rounded-full" style="width: 25%"></div>
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

                <form method="POST" action="{{ route('admin.programs.update', $program) }}" enctype="multipart/form-data" id="programEditForm">
                    @csrf
                    @method('PUT')

                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" id="step1">
                        <div class="mb-8">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                                    <p class="text-gray-600">Update the fundamental details of your program</p>
                                </div>
                            </div>
                    
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
                                            <input type="radio" name="type" value="{{ $key }}" class="sr-only" {{ old('type', $program->type) == $key ? 'checked' : '' }} required>
                                            <div class="relative p-4 border-2 {{ old('type', $program->type) == $key ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} rounded-xl hover:border-blue-300 transition-all duration-200 hover:shadow-lg group">
                                                <div class="flex flex-col items-center text-center">
                                                    <div class="w-12 h-12 bg-gradient-to-r {{ $type['color'] }} rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                                        <i class="{{ $type['icon'] }} text-white text-lg"></i>
                                                    </div>
                                                    <h4 class="font-semibold text-gray-900 mb-1">{{ $type['name'] }}</h4>
                                                    <p class="text-xs text-gray-500 leading-tight">{{ $type['desc'] }}</p>
                                                </div>
                                                <div class="absolute top-2 right-2 w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <div class="w-3 h-3 bg-blue-600 rounded-full {{ old('type', $program->type) == $key ? 'scale-100' : 'scale-0' }} transition-transform"></div>
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

                            <!-- Current Flyer Display -->
                            @if($program->hasFlyer())
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Program Flyer</label>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ $program->flyer_url }}" alt="Current flyer" class="w-20 h-20 object-cover rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Current flyer uploaded</p>
                                                <p class="text-xs text-gray-500">Upload a new file below to replace it</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Program Flyer Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Program Flyer/Images {{ $program->hasFlyer() ? '(Replace Current)' : '' }}</label>
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
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror resize-none">{{ old('description', $program->description) }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                        <span id="charCount">{{ strlen($program->description ?? '') }}</span>/1000
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
                                <p class="text-gray-600">Update the timing and location for your program</p>
                            </div>
                        </div>
                    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <div class="relative">
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $program->start_date?->format('Y-m-d')) }}" required
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
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $program->end_date?->format('Y-m-d')) }}" required
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
                                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $program->start_time) }}"
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
                                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $program->end_time) }}"
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
                                    <input type="text" id="venue" name="venue" value="{{ old('venue', $program->venue) }}" placeholder="Enter the venue or location for your program"
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
                                    <input type="number" step="0.01" min="0" id="registration_fee" name="registration_fee" value="{{ old('registration_fee', $program->registration_fee) }}" placeholder="0.00"
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
                                    <input type="number" min="1" id="max_participants" name="max_participants" value="{{ old('max_participants', $program->max_participants) }}" placeholder="Unlimited"
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
                                    <input type="date" id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline', $program->registration_deadline?->format('Y-m-d')) }}"
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
