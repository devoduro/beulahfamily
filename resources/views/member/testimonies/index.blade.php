@extends('member.layouts.app')

@section('title', 'Testimonies')
@section('subtitle', 'Share and read inspiring testimonies from Beulah family')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Section -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-heart text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-5xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent mb-4">
                Testimonies of Faith
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Witness the amazing works of God in the lives of Beulah family. Be inspired, encouraged, and strengthened in your faith.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
            <a href="{{ route('member.testimonies.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 text-white font-bold rounded-2xl hover:from-amber-600 hover:via-orange-600 hover:to-red-600 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                <i class="fas fa-plus mr-3 text-lg"></i>
                Share Your Testimony
                <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
            </a>
            <a href="{{ route('member.testimonies.my-testimonies') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                <i class="fas fa-user mr-3 text-lg"></i>
                My Testimonies
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-6 mb-8">
            <form method="GET" action="{{ route('member.testimonies.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Search Testimonies</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-12 pr-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md placeholder-gray-400" placeholder="Search testimonies...">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Category</label>
                        <select name="category" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md appearance-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-search mr-2"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Testimonies Grid -->
        @if($testimonies->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonies as $testimony)
                    <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8 hover:shadow-2xl transition-all duration-500 hover:bg-white/90 hover:-translate-y-2 hover:scale-105">
                        <!-- Category Badge -->
                        <div class="flex items-center justify-between mb-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 border border-amber-200">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $testimony->category_display }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $testimony->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold text-gray-900 mb-4 group-hover:text-amber-700 transition-colors duration-300 line-clamp-2">
                            {{ $testimony->title }}
                        </h3>

                        <!-- Excerpt -->
                        <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                            {{ $testimony->excerpt }}
                        </p>

                        <!-- Author -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center">
                                    @if($testimony->member->photo_path)
                                        <img src="{{ asset('storage/' . $testimony->member->photo_path) }}" alt="{{ $testimony->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <i class="fas fa-user text-white text-sm"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $testimony->member->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $testimony->member->chapter ?? 'ACCRA' }} Chapter</p>
                                </div>
                            </div>
                            <a href="{{ route('member.testimonies.show', $testimony) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                Read More
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>

                        <!-- Tags -->
                        @if($testimony->tags && count($testimony->tags) > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200/50">
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($testimony->tags, 0, 3) as $tag)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                            #{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($testimonies->hasPages())
                <div class="flex justify-center mt-12">
                    <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-4">
                        {{ $testimonies->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16 bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20">
                <div class="w-24 h-24 bg-gradient-to-br from-amber-100 via-orange-100 to-red-100 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-heart text-4xl text-amber-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Testimonies Found</h3>
                <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                    @if(request()->hasAny(['search', 'category']))
                        No testimonies match your search criteria. Try adjusting your filters.
                    @else
                        Be the first to share your testimony and inspire others with God's goodness in your life.
                    @endif
                </p>
                <div class="space-y-4">
                    <a href="{{ route('member.testimonies.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 text-white font-bold rounded-2xl hover:from-amber-600 hover:via-orange-600 hover:to-red-600 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                        <i class="fas fa-plus mr-3"></i>
                        Share Your First Testimony
                    </a>
                    @if(request()->hasAny(['search', 'category']))
                        <div>
                            <a href="{{ route('member.testimonies.index') }}" class="text-amber-600 hover:text-amber-700 font-medium">
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
