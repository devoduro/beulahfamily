@extends('components.app-layout')

@section('title', 'Ministries')
@section('subtitle', 'Manage church ministries and service groups')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Church Ministries</h1>
            <p class="text-gray-600 mt-1">Organize and manage ministry groups and their activities</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ministries.create.public') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-hands-praying mr-2"></i>
                Add Ministry
            </a>
            <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-download mr-2"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Ministries</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Search ministries...">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Categories</option>
                    <option value="worship">Worship</option>
                    <option value="youth">Youth</option>
                    <option value="children">Children</option>
                    <option value="outreach">Outreach</option>
                    <option value="fellowship">Fellowship</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
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

    <!-- Ministries Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ministries ?? [] as $ministry)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hands-praying text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $ministry->name }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($ministry->ministry_type) }} Ministry</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('ministries.show', $ministry->id) }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                            <i class="fas fa-eye"></i>
                        </a>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('ministries.edit', $ministry->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Ministry Description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                    {{ $ministry->description ?: 'No description available for this ministry.' }}
                </p>

                <!-- Ministry Leader -->
                <div class="mb-4 p-3 bg-purple-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $ministry->leader->full_name ?? 'No Leader Assigned' }}</p>
                            <p class="text-xs text-gray-600">Ministry Leader</p>
                        </div>
                    </div>
                </div>

                <!-- Ministry Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-lg font-bold text-gray-900">{{ $ministry->members_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600">Members</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-lg font-bold text-gray-900">
                            @if($ministry->budget)
                                GHS {{ number_format($ministry->budget, 0) }}
                            @else
                                No Budget
                            @endif
                        </div>
                        <div class="text-xs text-gray-600">Budget</div>
                    </div>
                </div>

                <!-- Meeting Schedule -->
                <div class="mb-4">
                    @if($ministry->meeting_day || $ministry->meeting_time)
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar w-4 mr-3 text-gray-400"></i>
                            <span>
                                @if($ministry->meeting_day)
                                    {{ ucfirst($ministry->meeting_day) }}s
                                @endif
                                @if($ministry->meeting_time)
                                    at {{ \Carbon\Carbon::parse($ministry->meeting_time)->format('g:i A') }}
                                @endif
                            </span>
                        </div>
                    @else
                        <div class="flex items-center text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar w-4 mr-3 text-gray-400"></i>
                            <span>No schedule set</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt w-4 mr-3 text-gray-400"></i>
                        <span>{{ $ministry->meeting_location ?: 'No location set' }}</span>
                    </div>
                </div>

                <!-- Status and Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ministry->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $ministry->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('ministries.members.manage', $ministry->id) }}" class="text-xs text-purple-600 hover:text-purple-800 font-medium">Manage Members</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-2xl shadow-lg border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hands-praying text-2xl text-purple-500"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No ministries found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first ministry group.</p>
                    <a href="{{ route('ministries.create.public') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200">
                        <i class="fas fa-hands-praying mr-2"></i>
                        Create First Ministry
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($ministries) && $ministries->hasPages())
        <div class="flex justify-center">
            {{ $ministries->links() }}
        </div>
    @endif
</div>
@endsection
