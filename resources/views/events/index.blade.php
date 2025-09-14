@extends('components.app-layout')

@section('title', 'Events')
@section('subtitle', 'Manage church events and activities')

@section('content')
<div class="space-y-8">
    <!-- Hero Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-800 rounded-3xl shadow-2xl">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
        <div class="relative px-8 py-12">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-white">Church Events</h1>
                            <p class="text-blue-100 text-lg">Discover and manage upcoming church activities</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Total Events</p>
                                    <p class="text-3xl font-bold text-white">{{ $events->total() ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 bg-blue-500/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-200"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">This Month</p>
                                    <p class="text-3xl font-bold text-white">{{ $events->where('start_datetime', '>=', now()->startOfMonth())->count() ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 bg-green-500/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-chart-line text-green-200"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Upcoming</p>
                                    <p class="text-3xl font-bold text-white">{{ $events->where('start_datetime', '>=', now())->count() ?? 0 }}</p>
                                </div>
                                <div class="w-10 h-10 bg-purple-500/30 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-purple-200"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('events.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-900 font-semibold rounded-2xl hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-plus mr-2"></i>
                        Create Event
                    </a>
                    <button onclick="toggleView()" class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-2xl hover:bg-white/30 transition-all duration-200 border border-white/30">
                        <i class="fas fa-th-large mr-2" id="viewIcon"></i>
                        <span id="viewText">Grid View</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Search and Filters -->
    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50 p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Find Events</h2>
            <button onclick="toggleFilters()" class="text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-sliders-h"></i>
            </button>
        </div>
        
        <!-- Main Search -->
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-lg"></i>
            </div>
            <input type="text" id="searchInput" class="block w-full pl-12 pr-4 py-4 text-lg border-0 bg-gray-50 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:bg-white transition-all duration-200" placeholder="Search events by name, description, or location...">
        </div>

        <!-- Advanced Filters -->
        <div id="advancedFilters" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Event Type</label>
                    <select id="typeFilter" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                        <option value="">All Types</option>
                        <option value="service">🙏 Service</option>
                        <option value="conference">📚 Conference</option>
                        <option value="workshop">🛠️ Workshop</option>
                        <option value="social">🎉 Social</option>
                        <option value="outreach">🤝 Outreach</option>
                        <option value="meeting">💼 Meeting</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                    <select id="statusFilter" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                        <option value="">All Status</option>
                        <option value="upcoming">⏰ Upcoming</option>
                        <option value="published">✅ Published</option>
                        <option value="draft">📝 Draft</option>
                        <option value="completed">🎯 Completed</option>
                        <option value="cancelled">❌ Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Date Range</label>
                    <input type="date" id="dateFilter" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilters()" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Display -->
    <div id="eventsContainer">
        <!-- List View (Default) -->
        <div id="listView" class="space-y-6">
            @forelse($events ?? [] as $event)
                <div class="group bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50 p-8 hover:shadow-2xl hover:bg-white transition-all duration-500 transform hover:-translate-y-1">
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                        <!-- Event Info -->
                        <div class="flex items-start space-x-6 flex-1">
                            <!-- Date Badge -->
                            <div class="relative">
                                <div class="w-20 h-20 bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg">
                                    <div class="text-xs font-bold uppercase tracking-wide">{{ $event->start_datetime ? $event->start_datetime->format('M') : 'JAN' }}</div>
                                    <div class="text-2xl font-black">{{ $event->start_datetime ? $event->start_datetime->format('d') : '15' }}</div>
                                </div>
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <!-- Title and Status -->
                                <div class="flex items-center gap-4 mb-3">
                                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-purple-700 transition-colors">{{ $event->title ?? 'Sunday Service' }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ ($event->status ?? 'upcoming') === 'upcoming' ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white' : (($event->status ?? 'upcoming') === 'completed' ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' : 'bg-gradient-to-r from-gray-500 to-slate-500 text-white') }}">
                                        @if(($event->status ?? 'upcoming') === 'upcoming')
                                            <i class="fas fa-clock mr-1"></i>
                                        @elseif(($event->status ?? 'upcoming') === 'completed')
                                            <i class="fas fa-check mr-1"></i>
                                        @else
                                            <i class="fas fa-pause mr-1"></i>
                                        @endif
                                        {{ ucfirst($event->status ?? 'Upcoming') }}
                                    </span>
                                </div>
                                
                                <!-- Description -->
                                <p class="text-gray-600 mb-4 text-lg leading-relaxed">{{ $event->description ?? 'Join us for our weekly Sunday worship service with inspiring messages and uplifting music.' }}</p>
                                
                                <!-- Event Details Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="flex items-center bg-gray-50 rounded-xl p-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-clock text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Time</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $event->start_datetime ? $event->start_datetime->format('g:i A') : '10:00 AM' }} - {{ $event->end_datetime ? $event->end_datetime->format('g:i A') : '12:00 PM' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-gray-50 rounded-xl p-3">
                                        <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-map-marker-alt text-pink-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Location</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $event->location ?? 'Main Sanctuary' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center bg-gray-50 rounded-xl p-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-users text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Attendance</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $event->registered_count ?? 45 }} registered</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Ministry and Organizer -->
                                <div class="flex flex-wrap items-center gap-4 text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-hands-praying w-4 mr-2 text-purple-500"></i>
                                        <span class="font-medium">{{ $event->ministry->name ?? 'General Ministry' }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-user w-4 mr-2 text-pink-500"></i>
                                        <span>Organized by <span class="font-medium">{{ $event->organizer->full_name ?? 'Pastor John' }}</span></span>
                                    </div>
                                    @if($event->registration_fee ?? 0 > 0)
                                        <div class="flex items-center text-green-600 font-semibold">
                                            <i class="fas fa-dollar-sign w-4 mr-1"></i>
                                            <span>${{ number_format($event->registration_fee ?? 0, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Event Actions -->
                        <div class="flex flex-col gap-3 xl:w-48">
                            <a href="{{ route('events.show', $event->id ?? 1) }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('events.edit', $event->id ?? 1) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                <a href="{{ route('attendance.qr-display', $event->id ?? 1) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                                    <i class="fas fa-qrcode mr-1"></i>
                                    QR
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl border-2 border-dashed border-gray-300">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-alt text-4xl text-purple-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No events found</h3>
                    <p class="text-gray-600 mb-8 text-lg">Get started by scheduling your first church event.</p>
                    <a href="{{ route('events.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-calendar-plus mr-3"></i>
                        Schedule First Event
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Grid View -->
        <div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($events ?? [] as $event)
                <div class="group bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50 p-6 hover:shadow-2xl hover:bg-white transition-all duration-500 transform hover:-translate-y-2">
                    <!-- Event Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg">
                            <div class="text-xs font-bold uppercase">{{ $event->start_datetime ? $event->start_datetime->format('M') : 'JAN' }}</div>
                            <div class="text-lg font-black">{{ $event->start_datetime ? $event->start_datetime->format('d') : '15' }}</div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ ($event->status ?? 'upcoming') === 'upcoming' ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white' : (($event->status ?? 'upcoming') === 'completed' ? 'bg-gradient-to-r from-green-500 to-emerald-500 text-white' : 'bg-gradient-to-r from-gray-500 to-slate-500 text-white') }}">
                            {{ ucfirst($event->status ?? 'Upcoming') }}
                        </span>
                    </div>

                    <!-- Event Title -->
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-700 transition-colors mb-3">{{ $event->title ?? 'Sunday Service' }}</h3>
                    
                    <!-- Event Description -->
                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $event->description ?? 'Join us for our weekly Sunday worship service with inspiring messages and uplifting music.' }}</p>
                    
                    <!-- Event Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-purple-600"></i>
                            </div>
                            <span>{{ $event->start_datetime ? $event->start_datetime->format('g:i A') : '10:00 AM' }} - {{ $event->end_datetime ? $event->end_datetime->format('g:i A') : '12:00 PM' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-pink-600"></i>
                            </div>
                            <span>{{ $event->location ?? 'Main Sanctuary' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <span>{{ $event->registered_count ?? 45 }} registered</span>
                        </div>
                    </div>

                    <!-- Event Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('events.show', $event->id ?? 1) }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </a>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('events.edit', $event->id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            <a href="{{ route('attendance.qr-display', $event->id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                                <i class="fas fa-qrcode mr-1"></i>
                                QR
                            </a>
                        </div>
                    </div>

                    <!-- Event Footer -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $event->ministry->name ?? 'General Ministry' }}</span>
                            @if($event->registration_fee ?? 0 > 0)
                                <span class="font-semibold text-green-600">${{ number_format($event->registration_fee ?? 0, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl border-2 border-dashed border-gray-300">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-alt text-4xl text-purple-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No events found</h3>
                    <p class="text-gray-600 mb-8 text-lg">Get started by scheduling your first church event.</p>
                    <a href="{{ route('events.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-calendar-plus mr-3"></i>
                        Schedule First Event
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($events) && $events->hasPages())
        <div class="flex justify-center mt-12">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 p-4">
                {{ $events->links() }}
            </div>
        </div>
    @endif
</div>

<!-- JavaScript for Interactive Features -->
<script>
let currentView = 'list';
let filtersVisible = false;

function toggleView() {
    const listView = document.getElementById('listView');
    const gridView = document.getElementById('gridView');
    const viewIcon = document.getElementById('viewIcon');
    const viewText = document.getElementById('viewText');
    
    if (currentView === 'list') {
        listView.classList.add('hidden');
        gridView.classList.remove('hidden');
        viewIcon.className = 'fas fa-list mr-2';
        viewText.textContent = 'List View';
        currentView = 'grid';
    } else {
        gridView.classList.add('hidden');
        listView.classList.remove('hidden');
        viewIcon.className = 'fas fa-th-large mr-2';
        viewText.textContent = 'Grid View';
        currentView = 'list';
    }
}

function toggleFilters() {
    const filters = document.getElementById('advancedFilters');
    filtersVisible = !filtersVisible;
    
    if (filtersVisible) {
        filters.classList.remove('hidden');
        filters.classList.add('animate-fadeIn');
    } else {
        filters.classList.add('hidden');
        filters.classList.remove('animate-fadeIn');
    }
}

function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value;
    const eventType = document.getElementById('typeFilter').value;
    const status = document.getElementById('statusFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (eventType) params.append('type', eventType);
    if (status) params.append('status', status);
    if (date) params.append('date', date);
    
    // Redirect with filters
    const url = new URL(window.location.href);
    url.search = params.toString();
    window.location.href = url.toString();
}

// Real-time search with debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

// Add custom CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
`;
document.head.appendChild(style);
</script>

@endsection
