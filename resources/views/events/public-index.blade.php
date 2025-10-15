@extends('components.public-layout')

@section('title', 'Church Events')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-teal-50 to-cyan-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-full mb-6 shadow-2xl">
                <i class="fas fa-calendar-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Church Events</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Join us for worship, fellowship, and community activities. Everyone is welcome!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Total Events</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $eventStats['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">This Month</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $eventStats['this_month'] ?? 0 }}</p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Upcoming Events</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $eventStats['upcoming'] ?? 0 }}</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events List -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    @php
                        $eventTypeColors = [
                            'service' => 'from-blue-600 to-blue-700',
                            'meeting' => 'from-green-600 to-green-700',
                            'conference' => 'from-red-600 to-red-700',
                            'workshop' => 'from-yellow-600 to-yellow-700',
                            'social' => 'from-pink-600 to-pink-700',
                            'outreach' => 'from-purple-600 to-purple-700',
                            'fundraising' => 'from-orange-600 to-orange-700',
                            'other' => 'from-gray-600 to-gray-700'
                        ];
                        $colorClass = $eventTypeColors[$event->event_type ?? 'other'] ?? $eventTypeColors['other'];
                        
                        $isUpcoming = $event->start_datetime && $event->start_datetime->isFuture();
                        $isPast = $event->start_datetime && $event->start_datetime->isPast();
                    @endphp
                    
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Event Banner/Image -->
                        @if($event->banner)
                            <div class="h-48 overflow-hidden relative">
                                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                @if($isUpcoming)
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white shadow-lg">
                                            <i class="fas fa-clock mr-1"></i>Upcoming
                                        </span>
                                    </div>
                                @elseif($isPast)
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-500 text-white shadow-lg">
                                            <i class="fas fa-check mr-1"></i>Completed
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br {{ $colorClass }} flex items-center justify-center relative">
                                <i class="fas fa-calendar-day text-white text-6xl opacity-80"></i>
                                @if($isUpcoming)
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-gray-900 shadow-lg">
                                            <i class="fas fa-clock mr-1"></i>Upcoming
                                        </span>
                                    </div>
                                @elseif($isPast)
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-gray-900 shadow-lg">
                                            <i class="fas fa-check mr-1"></i>Completed
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <!-- Event Type Badge -->
                            <div class="mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r {{ $colorClass }} text-white">
                                    @php
                                        $typeIcons = [
                                            'service' => 'fa-church',
                                            'meeting' => 'fa-users',
                                            'conference' => 'fa-graduation-cap',
                                            'workshop' => 'fa-tools',
                                            'social' => 'fa-glass-cheers',
                                            'outreach' => 'fa-hands-helping',
                                            'fundraising' => 'fa-hand-holding-usd',
                                            'other' => 'fa-calendar'
                                        ];
                                        $icon = $typeIcons[$event->event_type ?? 'other'] ?? $typeIcons['other'];
                                    @endphp
                                    <i class="fas {{ $icon }} mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $event->event_type ?? 'Event')) }}
                                </span>
                            </div>
                            
                            <!-- Event Title -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">{{ $event->title }}</h3>
                            
                            <!-- Event Description -->
                            @if($event->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit(strip_tags($event->description), 100) }}
                                </p>
                            @endif
                            
                            <!-- Event Details -->
                            <div class="space-y-2 mb-4">
                                <!-- Date and Time -->
                                <div class="flex items-center text-gray-700 text-sm">
                                    <i class="fas fa-calendar-alt text-green-600 w-5"></i>
                                    <span class="ml-3">
                                        {{ $event->start_datetime ? $event->start_datetime->format('l, M j, Y') : 'Date TBD' }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center text-gray-700 text-sm">
                                    <i class="fas fa-clock text-green-600 w-5"></i>
                                    <span class="ml-3">
                                        {{ $event->start_datetime ? $event->start_datetime->format('g:i A') : 'Time TBD' }}
                                        @if($event->end_datetime)
                                            - {{ $event->end_datetime->format('g:i A') }}
                                        @endif
                                    </span>
                                </div>
                                
                                @if($event->location)
                                    <div class="flex items-center text-gray-700 text-sm">
                                        <i class="fas fa-map-marker-alt text-green-600 w-5"></i>
                                        <span class="ml-3">{{ Str::limit($event->location, 40) }}</span>
                                    </div>
                                @endif
                                
                                @if($event->organizer)
                                    <div class="flex items-center text-gray-700 text-sm">
                                        <i class="fas fa-user text-green-600 w-5"></i>
                                        <span class="ml-3">{{ $event->organizer->name }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Registration Fee -->
                            @if($event->registration_fee > 0)
                                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                                    <span class="text-lg font-bold text-green-700">₵{{ number_format($event->registration_fee, 2) }}</span>
                                    <span class="text-xs text-green-600 ml-1">Registration Fee</span>
                                </div>
                            @else
                                <div class="mb-4 p-2 bg-blue-50 border border-blue-200 rounded-lg text-center">
                                    <span class="text-sm font-bold text-blue-700">FREE EVENT</span>
                                </div>
                            @endif
                            
                            <!-- Attendance Count -->
                            @if(isset($event->registered_count) && $event->registered_count > 0)
                                <div class="mb-4 flex items-center justify-center text-sm text-gray-600">
                                    <i class="fas fa-users text-gray-500 mr-2"></i>
                                    <span>{{ $event->registered_count }} {{ Str::plural('person', $event->registered_count) }} registered</span>
                                </div>
                            @endif
                            
                            <!-- Action Button -->
                            <a href="{{ route('events.show', $event) }}" 
                               class="block w-full text-center bg-gradient-to-r {{ $colorClass }} hover:opacity-90 text-white py-3 px-4 rounded-lg transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-info-circle mr-2"></i>View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $events->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                        <i class="fas fa-calendar-alt text-white text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Events Available</h3>
                    <p class="text-gray-600 leading-relaxed">
                        We're currently planning exciting new events for our community. 
                        Check back soon or contact us for more information.
                    </p>
                    <div class="mt-8">
                        <a href="{{ route('programs.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-medium rounded-lg hover:from-green-700 hover:to-teal-700 transition-colors shadow-lg hover:shadow-xl">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Programs
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
