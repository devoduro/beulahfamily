@extends('components.public-layout')

@section('title', 'Programs & Conferences')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Programs & Conferences</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Join our upcoming programs and conferences. Open to everyone - church members and visitors alike!</p>
        </div>

        @if($programs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($programs as $program)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Program Flyer -->
                        @if($program->hasFlyer())
                            <div class="h-48 overflow-hidden">
                                <img src="{{ $program->flyer_url }}" alt="{{ $program->name }} Flyer" class="w-full h-full object-cover">
                            </div>
                        @endif
                        
                        <!-- Program Header -->
                        @php
                            $headerColors = [
                                'ergates_conference' => 'from-orange-600 via-red-600 to-pink-600',
                                'annual_retreat' => 'from-green-600 via-teal-600 to-blue-600',
                                'conference' => 'from-blue-600 via-purple-600 to-indigo-600',
                                'workshop' => 'from-yellow-600 via-orange-600 to-red-600',
                                'seminar' => 'from-purple-600 via-pink-600 to-red-600',
                                'retreat' => 'from-green-600 via-emerald-600 to-teal-600',
                                'other' => 'from-gray-600 via-slate-600 to-zinc-600'
                            ];
                            $headerColor = $headerColors[$program->type] ?? $headerColors['other'];
                        @endphp
                        <div class="bg-gradient-to-r {{ $headerColor }} px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $program->name }}</h3>
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                        {{ $program->formatted_type }}
                                    </span>
                                </div>
                                @if($program->registration_fee > 0)
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-white">₵{{ number_format($program->registration_fee, 2) }}</div>
                                        <div class="text-xs text-blue-100">Registration Fee</div>
                                    </div>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">
                                        FREE
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-6">
                            @if($program->description)
                                <p class="text-gray-600 mb-4 leading-relaxed">{{ Str::limit($program->description, 120) }}</p>
                            @endif
                            
                            <!-- Program Details -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-calendar-alt text-blue-600 w-5"></i>
                                    <span class="ml-3">{{ $program->date_range }}</span>
                                </div>
                                
                                @if($program->time_range)
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-clock text-blue-600 w-5"></i>
                                        <span class="ml-3">{{ $program->time_range }}</span>
                                    </div>
                                @endif
                                
                                @if($program->venue)
                                    <div class="flex items-center text-gray-700">
                                        <i class="fas fa-map-marker-alt text-blue-600 w-5"></i>
                                        <span class="ml-3">{{ $program->venue }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Registration Stats -->
                            @php $stats = $program->getRegistrationStats(); @endphp
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Registrations:</span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $stats['approved'] }}
                                        @if($program->max_participants)
                                            / {{ $program->max_participants }}
                                        @endif
                                    </span>
                                </div>
                                
                                @if($program->max_participants)
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ min(($stats['approved'] / $program->max_participants) * 100, 100) }}%"></div>
                                    </div>
                                    @if($stats['remaining_slots'] !== null && $stats['remaining_slots'] <= 10)
                                        <div class="mt-2 text-xs text-amber-600 font-medium">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Only {{ $stats['remaining_slots'] }} spots remaining!
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Registration Deadline -->
                            @if($program->registration_deadline)
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-6">
                                    <div class="text-sm text-amber-800">
                                        <i class="fas fa-clock mr-2"></i>
                                        Registration closes: <strong>{{ $program->registration_deadline->format('M j, Y') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="px-6 pb-6">
                            <div class="flex space-x-3">
                                <a href="{{ route('programs.show', $program) }}" 
                                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 px-4 rounded-lg transition-colors text-center">
                                    <i class="fas fa-info-circle mr-2"></i>Details
                                </a>
                                
                                @if($program->isRegistrationOpen())
                                    <a href="{{ route('programs.register', $program) }}" 
                                       class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <i class="fas fa-user-plus mr-2"></i>Register Now
                                    </a>
                                @else
                                    <button class="flex-1 bg-gray-300 text-gray-500 font-medium py-3 px-4 rounded-lg cursor-not-allowed text-center" disabled>
                                        <i class="fas fa-lock mr-2"></i>Registration Closed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Programs Available</h3>
                    <p class="text-gray-600 leading-relaxed">
                        We're currently planning exciting new programs and conferences. 
                        Check back soon or contact us for more information about upcoming events.
                    </p>
                    <div class="mt-8">
                        <a href="mailto:info@beulahfamily.org" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-colors">
                            <i class="fas fa-envelope mr-2"></i>
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
