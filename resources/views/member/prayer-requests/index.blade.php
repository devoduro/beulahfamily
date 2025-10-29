@extends('member.layouts.app')

@section('title', 'Prayer Requests')
@section('subtitle', 'Join Beulah family in prayer and submit your own requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-praying-hands text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-purple-900 to-indigo-900 bg-clip-text text-transparent mb-4">
                Prayer Line
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                "Therefore confess your sins to each other and pray for each other so that you may be healed. The prayer of a righteous person is powerful and effective." - James 5:16
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
            <a href="{{ route('member.prayer-requests.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white font-bold rounded-2xl hover:from-purple-700 hover:via-indigo-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                <i class="fas fa-plus mr-3 text-lg"></i>
                Submit Prayer Request
                <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
            </a>
            <a href="{{ route('member.prayer-requests.my-requests') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 text-white font-bold rounded-2xl hover:from-emerald-700 hover:via-teal-700 hover:to-cyan-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                <i class="fas fa-user mr-3 text-lg"></i>
                My Prayer Requests
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-6 mb-8">
            <form method="GET" action="{{ route('member.prayer-requests.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Search Requests</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-12 pr-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md placeholder-gray-400" placeholder="Search prayer requests...">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Category</label>
                        <select name="category" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md appearance-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Urgency</label>
                        <select name="urgency" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md appearance-none">
                            <option value="">All Urgency Levels</option>
                            @foreach($urgencyLevels as $key => $label)
                                <option value="{{ $key }}" {{ request('urgency') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-search mr-2"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Prayer Requests Grid -->
        @if($prayerRequests->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($prayerRequests as $request)
                    <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8 hover:shadow-2xl transition-all duration-500 hover:bg-white/90 hover:-translate-y-2 hover:scale-105">
                        <!-- Header with Urgency and Category -->
                        <div class="flex items-center justify-between mb-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $request->urgency_color }}">
                                @if($request->urgency === 'urgent')
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                @elseif($request->urgency === 'high')
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                @elseif($request->urgency === 'medium')
                                    <i class="fas fa-circle mr-1"></i>
                                @else
                                    <i class="fas fa-dot-circle mr-1"></i>
                                @endif
                                {{ ucfirst($request->urgency) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Category Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-800 border border-purple-200">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $request->category_display }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-purple-700 transition-colors duration-300 line-clamp-2">
                            {{ $request->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                            {{ Str::limit($request->description, 120) }}
                        </p>

                        <!-- Prayer Count -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-praying-hands text-white text-xs"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ $request->total_prayers }} {{ Str::plural('prayer', $request->total_prayers) }}
                                </span>
                            </div>
                            
                            @if($request->expires_at)
                                <div class="flex items-center space-x-1 text-xs text-gray-500">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $request->days_remaining }} days left</span>
                                </div>
                            @endif
                        </div>

                        <!-- Requester Info -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center">
                                    @if(!$request->is_anonymous && $request->member->photo_path)
                                        <img src="{{ asset('storage/' . $request->member->photo_path) }}" alt="{{ $request->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <i class="fas fa-user text-white text-sm"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $request->is_anonymous ? 'Anonymous' : $request->member->full_name }}
                                    </p>
                                    @if(!$request->is_anonymous)
                                        <p class="text-xs text-gray-500">{{ $request->member->chapter ?? 'ACCRA' }} Chapter</p>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('member.prayer-requests.show', $request) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-sm font-semibold rounded-xl hover:from-purple-600 hover:to-indigo-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                Pray Now
                                <i class="fas fa-praying-hands ml-2"></i>
                            </a>
                        </div>

                        <!-- Status Indicators -->
                        <div class="mt-4 pt-4 border-t border-gray-200/50">
                            <div class="flex items-center justify-between text-xs">
                                @if($request->status === 'answered')
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-green-100 text-green-800 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Answered
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-100 text-blue-800 font-medium">
                                        <i class="fas fa-circle mr-1 animate-pulse"></i>
                                        Active
                                    </span>
                                @endif
                                
                                @if($request->is_private)
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-gray-100 text-gray-600 font-medium">
                                        <i class="fas fa-lock mr-1"></i>
                                        Private
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($prayerRequests->hasPages())
                <div class="flex justify-center mt-12">
                    <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-4">
                        {{ $prayerRequests->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-100 via-indigo-100 to-blue-100 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-praying-hands text-4xl text-purple-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Prayer Requests Found</h3>
                <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                    @if(request()->hasAny(['search', 'category', 'urgency']))
                        No prayer requests match your search criteria. Try adjusting your filters.
                    @else
                        Be the first to submit a prayer request and let Beulah family pray with you.
                    @endif
                </p>
                <div class="space-y-4">
                    <a href="{{ route('member.prayer-requests.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white font-bold rounded-2xl hover:from-purple-700 hover:via-indigo-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                        <i class="fas fa-plus mr-3"></i>
                        Submit Your First Request
                    </a>
                    @if(request()->hasAny(['search', 'category', 'urgency']))
                        <div>
                            <a href="{{ route('member.prayer-requests.index') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                                <i class="fas fa-times-circle mr-1"></i>Clear Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
