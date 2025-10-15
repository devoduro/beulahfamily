@extends('components.public-layout')

@section('title', 'Programs & Events')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Programs, Events & Announcements</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Stay connected with our church community. View upcoming programs, events, and important announcements.</p>
        </div>

        <!-- Announcements Section -->
        @if($announcements->count() > 0)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-bullhorn text-yellow-600 mr-3"></i>
                        Latest Announcements
                    </h2>
                    <a href="{{ route('announcements.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                            <div class="flex flex-col md:flex-row">
                                <!-- Priority Badge -->
                                @php
                                    $priorityColors = [
                                        'urgent' => 'bg-red-600',
                                        'high' => 'bg-orange-500',
                                        'normal' => 'bg-blue-500',
                                        'low' => 'bg-gray-400'
                                    ];
                                    $priorityColor = $priorityColors[$announcement->priority] ?? 'bg-gray-400';
                                @endphp
                                <div class="{{ $priorityColor }} w-2 hidden md:block"></div>
                                
                                <!-- Announcement Flyer/Image -->
                                @if($announcement->image_path)
                                    <div class="relative">
                                        <!-- Mobile: Top border for priority -->
                                        <div class="{{ $priorityColor }} h-2 w-full md:hidden"></div>
                                        
                                        <div class="flex-shrink-0 w-full md:w-56 lg:w-64 h-48 md:h-56 overflow-hidden">
                                            <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                                                 alt="{{ $announcement->title }}" 
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                                        </div>
                                    </div>
                                @else
                                    <!-- Show priority bar at top on mobile when no image -->
                                    <div class="{{ $priorityColor }} h-2 w-full md:hidden"></div>
                                @endif
                                
                                <div class="flex-1 p-6">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                                        <div class="flex-1 mb-4 md:mb-0">
                                            <div class="flex flex-wrap items-center mb-2 gap-2">
                                                @if($announcement->priority === 'urgent' || $announcement->priority === 'high')
                                                    <span class="inline-flex px-2 py-1 rounded text-xs font-bold {{ $priorityColor }} text-white">
                                                        {{ strtoupper($announcement->priority) }}
                                                    </span>
                                                @endif
                                                <h3 class="text-xl font-bold text-gray-900">{{ $announcement->title }}</h3>
                                            </div>
                                            
                                            <p class="text-gray-600 mb-3 leading-relaxed">
                                                {{ Str::limit(strip_tags($announcement->content), 150) }}
                                            </p>
                                            
                                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                                <span><i class="fas fa-calendar mr-1"></i>{{ $announcement->created_at->format('M j, Y') }}</span>
                                                @if($announcement->expire_date)
                                                    <span><i class="fas fa-clock mr-1"></i>Expires: {{ $announcement->expire_date->format('M j, Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <a href="{{ route('announcements.show', $announcement) }}" 
                                           class="inline-block md:ml-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium text-center">
                                            <i class="fas fa-arrow-right mr-2 md:hidden"></i>Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Upcoming Events Section -->
        @if($events->count() > 0)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-alt text-green-600 mr-3"></i>
                        Upcoming Events
                    </h2>
                    <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                            @if($event->banner)
                                <div class="h-40 overflow-hidden">
                                    <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="h-40 bg-gradient-to-r from-green-500 to-teal-500 flex items-center justify-center">
                                    <i class="fas fa-calendar-day text-white text-4xl"></i>
                                </div>
                            @endif
                            
                            <div class="p-5">
                                <div class="mb-3">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                                    @if($event->description)
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ Str::limit($event->description, 100) }}
                                        </p>
                                    @endif
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-calendar text-green-600 w-5"></i>
                                        <span class="ml-2">{{ $event->start_datetime->format('M j, Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i class="fas fa-clock text-green-600 w-5"></i>
                                        <span class="ml-2">{{ $event->start_datetime->format('g:i A') }}</span>
                                    </div>
                                    @if($event->location)
                                        <div class="flex items-center text-sm text-gray-700">
                                            <i class="fas fa-map-marker-alt text-green-600 w-5"></i>
                                            <span class="ml-2">{{ Str::limit($event->location, 30) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($event->registration_fee > 0)
                                    <div class="mb-4 p-2 bg-green-50 rounded-lg text-center">
                                        <span class="text-lg font-bold text-green-700">₵{{ number_format($event->registration_fee, 2) }}</span>
                                        <span class="text-xs text-green-600 ml-1">Registration Fee</span>
                                    </div>
                                @endif
                                
                                <a href="{{ route('events.show', $event) }}" 
                                   class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition-colors font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Programs Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-graduation-cap text-purple-600 mr-3"></i>
                    Programs & Conferences
                </h2>
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
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">No Programs Available</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        We're currently planning exciting new programs and conferences. 
                        Check back soon for updates!
                    </p>
                </div>
            </div>
        @endif
        </div>

        <!-- Empty State for All Content -->
        @if($announcements->count() === 0 && $events->count() === 0 && $programs->count() === 0)
            <div class="text-center py-16">
                <div class="max-w-2xl mx-auto">
                    <div class="w-32 h-32 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl">
                        <i class="fas fa-church text-white text-5xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Stay Tuned!</h3>
                    <p class="text-gray-600 leading-relaxed text-lg mb-8">
                        We're preparing exciting new programs, events, and announcements for our community. 
                        Check back soon or contact us for more information.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('announcements.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-bullhorn mr-2"></i>
                            View All Announcements
                        </a>
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-teal-500 text-white font-medium rounded-lg hover:from-green-600 hover:to-teal-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            View All Events
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
