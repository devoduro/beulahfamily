@extends('components.app-layout')

@section('title', 'Families')
@section('subtitle', 'Manage church families and household information')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Analytics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Families</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_families'] }}</p>
                        <p class="text-sm text-emerald-600 font-medium mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            {{ $stats['active_families'] }} active
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-home text-emerald-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Members</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_members'] }}</p>
                        <p class="text-sm text-blue-600 font-medium mt-1">
                            <i class="fas fa-users mr-1"></i>
                            {{ $stats['active_members'] }} active
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Average Family Size</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['avg_family_size'], 1) }}</p>
                        <p class="text-sm text-purple-600 font-medium mt-1">
                            <i class="fas fa-chart-line mr-1"></i>
                            members per family
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Cities Covered</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['cities_count'] }}</p>
                        <p class="text-sm text-orange-600 font-medium mt-1">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            locations served
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center space-x-4">
                <button onclick="toggleView()" id="view-toggle" class="flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-th-large mr-3 text-purple-500"></i>
                    Grid View
                </button>
                <button onclick="exportFamilies()" class="flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-download mr-3 text-green-500"></i>
                    Export
                </button>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('families.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-700 hover:via-green-700 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-home mr-3"></i>
                    Add New Family
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-6 mb-8">
            <form method="GET" action="{{ route('families.index') }}" class="space-y-6">
                <!-- Basic Search -->
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search families by name, address, phone, email, or members..." 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        <button type="button" onclick="toggleAdvancedFilters()" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <span id="filter-toggle-text">Show Advanced</span>
                            <i id="filter-toggle-icon" class="fas fa-chevron-down ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters (Hidden by default) -->
                <div id="advanced-filters" class="hidden space-y-4 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- City Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <select name="city" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">All Cities</option>
                                @foreach($stats['cities'] as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Family Size Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Family Size</label>
                            <select name="family_size" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Any Size</option>
                                <option value="1-2" {{ request('family_size') == '1-2' ? 'selected' : '' }}>1-2 members</option>
                                <option value="3-4" {{ request('family_size') == '3-4' ? 'selected' : '' }}>3-4 members</option>
                                <option value="5-6" {{ request('family_size') == '5-6' ? 'selected' : '' }}>5-6 members</option>
                                <option value="7+" {{ request('family_size') == '7+' ? 'selected' : '' }}>7+ members</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="family_name" {{ request('sort') == 'family_name' ? 'selected' : '' }}>Family Name</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                                <option value="members_count" {{ request('sort') == 'members_count' ? 'selected' : '' }}>Member Count</option>
                                <option value="city" {{ request('sort') == 'city' ? 'selected' : '' }}>City</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('families.index') }}" class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                            Apply Advanced Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Families Display -->
        <div id="families-container">
            <!-- Grid View (Default) -->
            <div id="grid-view" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @forelse($families as $family)
                    <div class="family-card bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group" data-family-id="{{ $family->id }}">
                        <!-- Family Header -->
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 via-green-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-home text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-200">{{ $family->family_name }}</h3>
                                    <p class="text-sm text-gray-600 font-medium">{{ $family->total_members }} {{ Str::plural('member', $family->total_members) }}</p>
                                    <p class="text-xs text-gray-500">{{ $family->active_members ?? 0 }} active</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('families.show', $family) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 hover:scale-110">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('families.edit', $family) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200 hover:scale-110">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Family Head Information -->
                        @if($family->head)
                        <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-crown text-yellow-500 mr-2"></i>
                                Family Head
                            </h4>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden">
                                    @if($family->head->photo_path)
                                        <img src="{{ asset('storage/' . $family->head->photo_path) }}" alt="{{ $family->head->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-white"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $family->head->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $family->head->email ?: 'No email' }}</p>
                                    <p class="text-xs text-gray-500">{{ $family->head->phone ?: 'No phone' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Contact Information -->
                        <div class="space-y-3 mb-6">
                            @if($family->phone)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone w-4 mr-3 text-emerald-500"></i>
                                <span>{{ $family->phone }}</span>
                            </div>
                            @endif
                            @if($family->email)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope w-4 mr-3 text-blue-500"></i>
                                <span>{{ $family->email }}</span>
                            </div>
                            @endif
                            @if($family->address)
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt w-4 mr-3 text-red-500 mt-0.5"></i>
                                <span>{{ $family->address }}{{ $family->city ? ', ' . $family->city : '' }}{{ $family->state ? ', ' . $family->state : '' }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Family Members Preview -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center justify-between">
                                <span><i class="fas fa-users mr-2 text-purple-500"></i>Family Members</span>
                                <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full">{{ $family->total_members }}</span>
                            </h4>
                            <div class="flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    @foreach($family->members->take(6) as $member)
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full border-2 border-white flex items-center justify-center overflow-hidden" title="{{ $member->full_name }}">
                                            @if($member->photo_path)
                                                <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-user text-white text-xs"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($family->total_members > 6)
                                        <div class="w-8 h-8 bg-gray-300 rounded-full border-2 border-white flex items-center justify-center">
                                            <span class="text-xs font-bold text-gray-600">+{{ $family->total_members - 6 }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if($family->total_members > 0)
                                <a href="{{ route('families.show', $family) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">View All</a>
                                @endif
                            </div>
                        </div>

                        <!-- Status and Quick Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $family->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas fa-circle mr-1 text-xs {{ $family->is_active ? 'text-emerald-500' : 'text-gray-500' }}"></i>
                                    {{ $family->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($family->created_at->isToday())
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-star mr-1"></i>New
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('families.show', $family) }}" class="text-xs text-emerald-600 hover:text-emerald-800 font-semibold transition-colors">Details</a>
                                <span class="text-gray-300">•</span>
                                <a href="{{ route('members.create') }}?family_id={{ $family->id }}" class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition-colors">Add Member</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-16 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50">
                            <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-home text-3xl text-emerald-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">No families found</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">Start building your church community by adding your first family. Track relationships, manage contact information, and strengthen connections.</p>
                            <a href="{{ route('families.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 text-white font-bold rounded-2xl hover:from-emerald-700 hover:via-green-700 hover:to-teal-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                                <i class="fas fa-home mr-3"></i>
                                Add First Family
                                <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- List View (Hidden by default) -->
            <div id="list-view" class="hidden space-y-4 mb-8">
                @foreach($families as $family)
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100/50 p-4 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-home text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $family->family_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $family->total_members }} members • {{ $family->head->full_name ?? 'No head assigned' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $family->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $family->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('families.show', $family) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('families.edit', $family) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Enhanced Pagination -->
        @if($families->hasPages())
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-6">
                <div class="text-sm text-gray-600">
                    Showing {{ $families->firstItem() }} to {{ $families->lastItem() }} of {{ $families->total() }} families
                </div>
                <div class="flex items-center space-x-2">
                    {{ $families->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Advanced Filters
    window.toggleAdvancedFilters = function() {
        const filters = document.getElementById('advanced-filters');
        const toggleText = document.getElementById('filter-toggle-text');
        const toggleIcon = document.getElementById('filter-toggle-icon');
        
        if (filters.classList.contains('hidden')) {
            filters.classList.remove('hidden');
            toggleText.textContent = 'Hide Advanced';
            toggleIcon.classList.remove('fa-chevron-down');
            toggleIcon.classList.add('fa-chevron-up');
        } else {
            filters.classList.add('hidden');
            toggleText.textContent = 'Show Advanced';
            toggleIcon.classList.remove('fa-chevron-up');
            toggleIcon.classList.add('fa-chevron-down');
        }
    };

    // Toggle View (Grid/List)
    window.toggleView = function() {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const toggleBtn = document.getElementById('view-toggle');
        
        if (gridView.classList.contains('hidden')) {
            // Switch to grid view
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            toggleBtn.innerHTML = '<i class="fas fa-th-large mr-3 text-purple-500"></i>Grid View';
        } else {
            // Switch to list view
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            toggleBtn.innerHTML = '<i class="fas fa-list mr-3 text-purple-500"></i>List View';
        }
    };

    // Export Families
    window.exportFamilies = function() {
        window.open('{{ route('families.export') }}', '_blank');
    };

    // Real-time search (debounced)
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit form after 500ms of no typing
                this.form.submit();
            }, 500);
        });
    }
});
</script>
@endsection
