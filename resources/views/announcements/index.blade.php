@extends('components.public-layout')

@section('title', 'Announcements')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl mb-4">
                <i class="fas fa-bullhorn text-white text-2xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Church Announcements</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">Stay updated with the latest news and announcements from our church community</p>
        </div>

        @auth
            @if(auth()->user()->role === 'admin')
                <!-- Admin Actions -->
                <div class="mb-6 text-center">
                    <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Announcement
                    </a>
                </div>
            @endif
        @endauth

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
            <form method="GET" action="{{ route('announcements.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search announcements...">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Events</option>
                        <option value="ministry" {{ request('type') == 'ministry' ? 'selected' : '' }}>Ministry</option>
                        <option value="urgent" {{ request('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="prayer_request" {{ request('type') == 'prayer_request' ? 'selected' : '' }}>Prayer Request</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

    <!-- Announcements List -->
    <div class="space-y-4">
        @forelse($announcements ?? [] as $announcement)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                    <!-- Announcement Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $announcement->type === 'urgent' ? 'bg-red-100 text-red-800' : ($announcement->type === 'event' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}
                                        </span>
                                        @if($announcement->priority === 'urgent' || $announcement->priority === 'high')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ ucfirst($announcement->priority) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($announcement->content), 200) }}
                        </p>

                        <!-- Announcement Meta -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-user w-4 mr-2 text-gray-400"></i>
                                <span>By {{ $announcement->creator->name ?? 'Admin' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar w-4 mr-2 text-gray-400"></i>
                                <span>{{ $announcement->publish_date ? \Carbon\Carbon::parse($announcement->publish_date)->format('M j, Y') : 'N/A' }}</span>
                            </div>
                            @if($announcement->expire_date && \Carbon\Carbon::parse($announcement->expire_date)->isFuture())
                                <div class="flex items-center">
                                    <i class="fas fa-clock w-4 mr-2 text-amber-400"></i>
                                    <span class="text-amber-600">Expires {{ \Carbon\Carbon::parse($announcement->expire_date)->format('M j') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Announcement Targets -->
                        @if($announcement->target_audience && is_array($announcement->target_audience) && count($announcement->target_audience) > 0)
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users w-4 mr-2 text-gray-400"></i>
                                    <span>For: {{ implode(', ', array_map('ucfirst', $announcement->target_audience)) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Announcement Actions -->
                    <div class="flex flex-row lg:flex-col gap-2 lg:w-32">
                        <a href="{{ route('announcements.show', $announcement->id ?? 1) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-colors shadow-md">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </a>
                        
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('announcements.edit', $announcement->id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-bullhorn text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">No Announcements Available</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Check back soon for the latest updates and announcements from our church.
                </p>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Create First Announcement
                        </a>
                    @endif
                @endauth
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($announcements) && $announcements->hasPages())
        <div class="flex justify-center mt-8">
            {{ $announcements->links() }}
        </div>
    @endif
    </div>
</div>
@endsection
