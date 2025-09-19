@extends('components.public-layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Program Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg text-white p-8 mb-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $program->name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-blue-100">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>{{ $program->date_range }}</span>
                        </div>
                        @if($program->time_range)
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $program->time_range }}</span>
                        </div>
                        @endif
                        @if($program->venue)
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $program->venue }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    @if($program->registration_fee > 0)
                        <div class="text-2xl font-bold">₵{{ number_format($program->registration_fee, 2) }}</div>
                        <div class="text-blue-200">Registration Fee</div>
                    @else
                        <div class="text-2xl font-bold text-green-300">FREE</div>
                        <div class="text-blue-200">Registration</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Program Description -->
                @if($program->description)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">About This Program</h2>
                    <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $program->description }}</div>
                </div>
                @endif

                <!-- Program Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Program Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-tag text-blue-600 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Type</div>
                                <div class="font-medium">{{ ucfirst($program->type) }}</div>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-calendar text-blue-600 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Duration</div>
                                <div class="font-medium">{{ $program->start_date->diffInDays($program->end_date) + 1 }} day(s)</div>
                            </div>
                        </div>

                        @if($program->max_participants)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-users text-blue-600 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Max Participants</div>
                                <div class="font-medium">{{ number_format($program->max_participants) }}</div>
                            </div>
                        </div>
                        @endif

                        @if($program->registration_deadline)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-clock text-blue-600 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Registration Deadline</div>
                                <div class="font-medium">{{ $program->registration_deadline->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Information -->
                @if($program->contact_email || $program->contact_phone)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                    <div class="space-y-3">
                        @if($program->contact_email)
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-3"></i>
                            <a href="mailto:{{ $program->contact_email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $program->contact_email }}
                            </a>
                        </div>
                        @endif
                        @if($program->contact_phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-blue-600 mr-3"></i>
                            <a href="tel:{{ $program->contact_phone }}" class="text-blue-600 hover:text-blue-800">
                                {{ $program->contact_phone }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Terms and Conditions -->
                @if($program->terms_and_conditions)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Terms and Conditions</h2>
                    <div class="text-gray-700 text-sm whitespace-pre-wrap leading-relaxed">{{ $program->terms_and_conditions }}</div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Registration Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration</h3>
                    
                    @if($program->isRegistrationOpen())
                        <div class="space-y-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600 mb-1">
                                    @if($program->registration_fee > 0)
                                        ₵{{ number_format($program->registration_fee, 2) }}
                                    @else
                                        FREE
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">Registration Fee</div>
                            </div>

                            @if($program->max_participants)
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Available Spots</span>
                                    <span class="font-medium">
                                        {{ $program->max_participants - $program->registrations->count() }} / {{ $program->max_participants }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <div class="bg-gray-200 rounded-full h-2">
                                        @php
                                            $percentage = $program->max_participants > 0 ? 
                                                ($program->registrations->count() / $program->max_participants) * 100 : 0;
                                        @endphp
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($program->registration_deadline)
                            <div class="text-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-1"></i>
                                Registration closes {{ $program->registration_deadline->diffForHumans() }}
                            </div>
                            @endif

                            <a href="{{ route('programs.register', $program) }}" 
                               class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 px-4 rounded-lg font-medium transition-colors">
                                Register Now
                            </a>

                            @if($program->allow_file_uploads)
                            <div class="text-xs text-gray-500 text-center">
                                <i class="fas fa-paperclip mr-1"></i>
                                File uploads supported ({{ $program->max_files ?? 5 }} files, {{ $program->max_file_size ?? 100 }}MB max)
                            </div>
                            @endif
                        </div>
                    @elseif($program->registration_deadline && $program->registration_deadline->isPast())
                        <div class="text-center py-4">
                            <i class="fas fa-clock text-red-500 text-3xl mb-3"></i>
                            <h4 class="text-lg font-medium text-red-600 mb-2">Registration Closed</h4>
                            <p class="text-sm text-gray-600">
                                Registration deadline was {{ $program->registration_deadline->format('M d, Y') }}
                            </p>
                        </div>
                    @elseif($program->max_participants && $program->registrations->count() >= $program->max_participants)
                        <div class="text-center py-4">
                            <i class="fas fa-users text-yellow-500 text-3xl mb-3"></i>
                            <h4 class="text-lg font-medium text-yellow-600 mb-2">Program Full</h4>
                            <p class="text-sm text-gray-600">
                                Maximum capacity of {{ $program->max_participants }} participants reached
                            </p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-pause-circle text-gray-500 text-3xl mb-3"></i>
                            <h4 class="text-lg font-medium text-gray-600 mb-2">Registration Not Available</h4>
                            <p class="text-sm text-gray-600">
                                Registration is currently not open for this program
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Program Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Program Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Registrations</span>
                            <span class="font-medium">{{ $program->registrations->count() }}</span>
                        </div>
                        @if($program->max_participants)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Available Spots</span>
                            <span class="font-medium">{{ $program->max_participants - $program->registrations->count() }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Program Status</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($program->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Share Program -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Share This Program</h3>
                    <div class="flex space-x-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                            <i class="fab fa-facebook-f mr-1"></i>Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($program->name) }}" 
                           target="_blank" 
                           class="flex-1 bg-blue-400 hover:bg-blue-500 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                            <i class="fab fa-twitter mr-1"></i>Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($program->name . ' - ' . request()->url()) }}" 
                           target="_blank" 
                           class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded text-sm transition-colors">
                            <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Back to Programs -->
                <div class="text-center">
                    <a href="{{ route('programs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Back to All Programs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
