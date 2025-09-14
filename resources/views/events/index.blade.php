@extends('components.app-layout')

@section('title', 'Events')
@section('subtitle', 'Manage church events and activities')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Church Events</h1>
            <p class="text-gray-600 mt-1">Schedule and manage church events and activities</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-calendar-plus mr-2"></i>
                Add Event
            </a>
            <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-calendar-alt mr-2"></i>
                Calendar View
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Events</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Search events...">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Event Type</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">All Types</option>
                    <option value="service">Service</option>
                    <option value="conference">Conference</option>
                    <option value="workshop">Workshop</option>
                    <option value="social">Social</option>
                    <option value="outreach">Outreach</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">All Status</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Events List -->
    <div class="space-y-4">
        @forelse($events ?? [] as $event)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Event Info -->
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex flex-col items-center justify-center text-white">
                            <div class="text-xs font-medium">{{ $event->start_datetime ? $event->start_datetime->format('M') : 'JAN' }}</div>
                            <div class="text-lg font-bold">{{ $event->start_datetime ? $event->start_datetime->format('d') : '15' }}</div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $event->title ?? 'Sunday Service' }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($event->status ?? 'upcoming') === 'upcoming' ? 'bg-blue-100 text-blue-800' : (($event->status ?? 'upcoming') === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($event->status ?? 'Upcoming') }}
                                </span>
                            </div>
                            <p class="text-gray-600 mb-3 line-clamp-2">{{ $event->description ?? 'Join us for our weekly Sunday worship service with inspiring messages and uplifting music.' }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-clock w-4 mr-2 text-gray-400"></i>
                                    <span>{{ $event->start_datetime ? $event->start_datetime->format('g:i A') : '10:00 AM' }} - {{ $event->end_datetime ? $event->end_datetime->format('g:i A') : '12:00 PM' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt w-4 mr-2 text-gray-400"></i>
                                    <span>{{ $event->location ?? 'Main Sanctuary' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-users w-4 mr-2 text-gray-400"></i>
                                    <span>{{ $event->registered_count ?? 45 }} registered</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 lg:flex-col lg:w-32">
                        <a href="{{ route('events.show', $event->id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            View
                        </a>
                        <a href="{{ route('events.edit', $event->id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                        <button class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Check-in
                        </button>
                    </div>
                </div>

                <!-- Event Ministry and Organizer -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-hands-praying w-4 mr-2 text-gray-400"></i>
                            <span>{{ $event->ministry->name ?? 'General Ministry' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user w-4 mr-2 text-gray-400"></i>
                            <span>Organized by {{ $event->organizer->full_name ?? 'Pastor John' }}</span>
                        </div>
                    </div>
                    @if($event->registration_fee ?? 0 > 0)
                        <div class="text-sm font-medium text-green-600">
                            ${{ number_format($event->registration_fee ?? 0, 2) }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-2xl text-orange-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
                <p class="text-gray-500 mb-6">Get started by scheduling your first church event.</p>
                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Schedule First Event
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($events) && $events->hasPages())
        <div class="flex justify-center">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
