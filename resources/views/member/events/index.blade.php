@extends('member.layouts.app')

@section('title', 'Events & Programs')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Church Events & Programs</h1>
            <p class="text-gray-600">Discover upcoming events and programs</p>
        </div>

        <!-- Event Stats - Real Data from Database -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md border border-blue-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_events'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-green-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Events Attended</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_events_attended'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md border border-purple-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Registered</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['registered_events'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events - Real Data from Database -->
        <div class="bg-white rounded-xl shadow-md mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Upcoming Events
                </h2>
            </div>
            <div class="p-6">
                @if($upcomingEvents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($upcomingEvents as $event)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                                </div>
                                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">
                                    {{ ucfirst($event->event_type ?? 'Event') }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $event->description ?? 'No description available' }}</p>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($event->start_datetime)->format('M d, Y g:i A') }}
                                </div>
                                @if($event->location)
                                <div class="flex items-center text-sm text-gray-700">
                                    <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                                    {{ $event->location }}
                                </div>
                                @endif
                                @if($event->registration_fee && $event->registration_fee > 0)
                                <div class="flex items-center text-sm text-green-700 font-medium">
                                    <i class="fas fa-tag mr-2 text-green-400"></i>
                                    ₵{{ number_format($event->registration_fee, 2) }}
                                </div>
                                @else
                                <div class="flex items-center text-sm text-green-700 font-medium">
                                    <i class="fas fa-tag mr-2 text-green-400"></i>
                                    Free
                                </div>
                                @endif
                            </div>
                            <a href="/events/{{ $event->id }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-colors">
                                View Details
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Upcoming Events</h3>
                        <p class="text-gray-600">Check back soon for new events and programs</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Past Events Attended - Real Data from Database -->
        @if($pastEvents->count() > 0)
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-history text-green-600 mr-2"></i>
                    Events You Attended
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($pastEvents as $event)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center flex-1">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $event->title }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($event->start_datetime)->format('M d, Y') }}
                                    @if($event->location)
                                        • {{ $event->location }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">
                            Attended
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
