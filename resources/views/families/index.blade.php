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
                <a href="{{ route('family.new') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-700 hover:via-green-700 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
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

                        <!-- Family Members Section -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-semibold text-gray-700 flex items-center">
                                    <i class="fas fa-users text-blue-500 mr-2"></i>
                                    Family Members ({{ $family->members->count() }})
                                </h4>
                                <button onclick="openMemberModal({{ $family->id }})" class="text-xs bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-plus mr-1"></i>Manage
                                </button>
                            </div>
                            
                            @if($family->members->count() > 0)
                                <div class="space-y-2 max-h-32 overflow-y-auto">
                                    @foreach($family->members->take(3) as $member)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden">
                                                    @if($member->photo_path)
                                                        <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <i class="fas fa-user text-white text-xs"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="text-xs font-medium text-gray-900">{{ $member->full_name }}</p>
                                                    @if($family->head_of_family_id == $member->id)
                                                        <span class="text-xs text-yellow-600 flex items-center">
                                                            <i class="fas fa-crown mr-1"></i>Head
                                                        </span>
                                                    @else
                                                        <p class="text-xs text-gray-500">{{ $member->membership_type ?? 'Member' }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($family->head_of_family_id != $member->id)
                                                <button onclick="removeMemberFromFamily({{ $family->id }}, {{ $member->id }})" class="text-red-500 hover:text-red-700 text-xs">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($family->members->count() > 3)
                                        <p class="text-xs text-gray-500 text-center">+{{ $family->members->count() - 3 }} more members</p>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-users text-gray-400 text-lg mb-2"></i>
                                    <p class="text-xs text-gray-500">No members assigned</p>
                                </div>
                            @endif
                        </div>

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
                            <a href="{{ route('family.new') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 text-white font-bold rounded-2xl hover:from-emerald-700 hover:via-green-700 hover:to-teal-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
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
<!-- Member Management Modal -->
<div id="memberModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-800">Manage Family Members</h3>
                    <button onclick="closeMemberModal()" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Current Members -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-users text-blue-500 mr-2"></i>
                            Current Members
                        </h4>
                        <div id="currentMembers" class="space-y-3 max-h-96 overflow-y-auto">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Available Members -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-plus text-green-500 mr-2"></i>
                            Add Members
                        </h4>
                        <div class="mb-4">
                            <input type="text" id="memberSearch" placeholder="Search members..." 
                                   class="w-full rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-3 px-4 text-slate-700">
                        </div>
                        <div id="availableMembers" class="space-y-3 max-h-80 overflow-y-auto">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentFamilyId = null;
let allMembers = [];

// Open member management modal
function openMemberModal(familyId) {
    currentFamilyId = familyId;
    document.getElementById('memberModal').classList.remove('hidden');
    loadFamilyMembers(familyId);
    loadAvailableMembers();
}

// Close member management modal
function closeMemberModal() {
    document.getElementById('memberModal').classList.add('hidden');
    currentFamilyId = null;
}

// Load current family members
async function loadFamilyMembers(familyId) {
    try {
        const response = await fetch(`/api/families/${familyId}/members`);
        const members = await response.json();
        
        const container = document.getElementById('currentMembers');
        container.innerHTML = '';
        
        if (members.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-8">No members in this family</p>';
            return;
        }
        
        members.forEach(member => {
            const memberDiv = document.createElement('div');
            memberDiv.className = 'flex items-center justify-between p-3 bg-blue-50 rounded-xl border border-blue-200';
            memberDiv.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center overflow-hidden">
                        ${member.photo_path ? 
                            `<img src="/storage/${member.photo_path}" alt="${member.full_name}" class="w-full h-full object-cover">` :
                            '<i class="fas fa-user text-white text-sm"></i>'
                        }
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">${member.full_name}</p>
                        <p class="text-sm text-gray-600">${member.email || 'No email'}</p>
                        ${member.is_head ? '<span class="text-xs text-yellow-600 flex items-center"><i class="fas fa-crown mr-1"></i>Family Head</span>' : ''}
                    </div>
                </div>
                ${!member.is_head ? `
                    <button onclick="removeMemberFromFamily(${familyId}, ${member.id})" 
                            class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                ` : ''}
            `;
            container.appendChild(memberDiv);
        });
    } catch (error) {
        console.error('Error loading family members:', error);
    }
}

// Load available members
async function loadAvailableMembers() {
    try {
        const response = await fetch('/api/members/available');
        allMembers = await response.json();
        displayAvailableMembers(allMembers);
    } catch (error) {
        console.error('Error loading available members:', error);
    }
}

// Display available members
function displayAvailableMembers(members) {
    const container = document.getElementById('availableMembers');
    container.innerHTML = '';
    
    if (members.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center py-8">No available members</p>';
        return;
    }
    
    members.forEach(member => {
        const memberDiv = document.createElement('div');
        memberDiv.className = 'flex items-center justify-between p-3 bg-green-50 rounded-xl border border-green-200';
        memberDiv.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center overflow-hidden">
                    ${member.photo_path ? 
                        `<img src="/storage/${member.photo_path}" alt="${member.full_name}" class="w-full h-full object-cover">` :
                        '<i class="fas fa-user text-white text-sm"></i>'
                    }
                </div>
                <div>
                    <p class="font-medium text-gray-900">${member.full_name}</p>
                    <p class="text-sm text-gray-600">${member.email || 'No email'}</p>
                    <p class="text-xs text-gray-500">${member.membership_type || 'Member'}</p>
                </div>
            </div>
            <button onclick="addMemberToFamily(${currentFamilyId}, ${member.id})" 
                    class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-plus"></i>
            </button>
        `;
        container.appendChild(memberDiv);
    });
}

// Search members
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('memberSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredMembers = allMembers.filter(member => 
                member.full_name.toLowerCase().includes(searchTerm) ||
                (member.email && member.email.toLowerCase().includes(searchTerm))
            );
            displayAvailableMembers(filteredMembers);
        });
    }
});

// Add member to family
async function addMemberToFamily(familyId, memberId) {
    try {
        const response = await fetch(`/api/families/${familyId}/members`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ member_id: memberId })
        });
        
        if (response.ok) {
            loadFamilyMembers(familyId);
            loadAvailableMembers();
            showNotification('Member added to family successfully!', 'success');
            // Refresh the page to update the family cards
            setTimeout(() => location.reload(), 1000);
        } else {
            const error = await response.json();
            showNotification(error.message || 'Failed to add member to family', 'error');
        }
    } catch (error) {
        console.error('Error adding member to family:', error);
        showNotification('Error adding member to family', 'error');
    }
}

// Remove member from family
async function removeMemberFromFamily(familyId, memberId) {
    if (!confirm('Are you sure you want to remove this member from the family?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/families/${familyId}/members/${memberId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            loadFamilyMembers(familyId);
            loadAvailableMembers();
            showNotification('Member removed from family successfully!', 'success');
            // Refresh the page to update the family cards
            setTimeout(() => location.reload(), 1000);
        } else {
            const error = await response.json();
            showNotification(error.message || 'Failed to remove member from family', 'error');
        }
    } catch (error) {
        console.error('Error removing member from family:', error);
        showNotification('Error removing member from family', 'error');
    }
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
        ${message}
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('memberModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMemberModal();
    }
});
</script>
@endpush
@endsection
