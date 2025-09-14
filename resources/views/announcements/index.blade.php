@extends('components.app-layout')

@section('title', 'Announcements')
@section('subtitle', 'Manage church announcements and communications')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Church Announcements</h1>
            <p class="text-gray-600 mt-1">Create and manage church announcements and communications</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-bullhorn mr-2"></i>
                New Announcement
            </a>
            <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-paper-plane mr-2"></i>
                Send Newsletter
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Announcements</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search announcements...">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Categories</option>
                    <option value="general">General</option>
                    <option value="events">Events</option>
                    <option value="ministry">Ministry</option>
                    <option value="urgent">Urgent</option>
                    <option value="prayer">Prayer Request</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="archived">Archived</option>
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
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title ?? 'Welcome New Members' }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($announcement->category ?? 'general') === 'urgent' ? 'bg-red-100 text-red-800' : (($announcement->category ?? 'general') === 'events' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($announcement->category ?? 'General') }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($announcement->status ?? 'published') === 'published' ? 'bg-green-100 text-green-800' : (($announcement->status ?? 'published') === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst($announcement->status ?? 'Published') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-600 mb-4 line-clamp-3">
                            {{ $announcement->content ?? 'We are excited to welcome our new church members who joined us this month. Please take a moment to introduce yourselves and make them feel at home in our church family.' }}
                        </p>

                        <!-- Announcement Meta -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-user w-4 mr-2 text-gray-400"></i>
                                <span>By {{ $announcement->author->full_name ?? 'Pastor John' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar w-4 mr-2 text-gray-400"></i>
                                <span>{{ $announcement->published_at ? $announcement->published_at->format('M j, Y g:i A') : 'Jan 15, 2025 10:00 AM' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-eye w-4 mr-2 text-gray-400"></i>
                                <span>{{ $announcement->views_count ?? 156 }} views</span>
                            </div>
                        </div>

                        <!-- Announcement Targets -->
                        @if($announcement->target_groups ?? false)
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users w-4 mr-2 text-gray-400"></i>
                                    <span>Targeted to: {{ implode(', ', $announcement->target_groups ?? ['All Members']) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Announcement Actions -->
                    <div class="flex flex-row lg:flex-col gap-2 lg:w-32">
                        <a href="{{ route('announcements.show', $announcement->id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            View
                        </a>
                        <a href="{{ route('announcements.edit', $announcement->id ?? 1) }}" class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                        <button class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send
                        </button>
                        <button class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                            <i class="fas fa-archive mr-2"></i>
                            Archive
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bullhorn text-2xl text-indigo-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No announcements found</h3>
                <p class="text-gray-500 mb-6">Create your first announcement to communicate with your congregation.</p>
                <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                    <i class="fas fa-bullhorn mr-2"></i>
                    Create First Announcement
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($announcements) && $announcements->hasPages())
        <div class="flex justify-center">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
