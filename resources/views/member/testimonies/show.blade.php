@extends('member.layouts.app')

@section('title', $testimony->title)
@section('subtitle', 'Testimony by ' . $testimony->member->full_name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Back Navigation -->
        <div class="flex items-center space-x-4 mb-6">
            <a href="{{ route('member.testimonies.index') }}" class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-xl hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md border border-gray-200/50">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Testimonies
            </a>
            
            @if($testimony->member_id === auth('member')->id())
                <div class="flex space-x-2">
                    <a href="{{ route('member.testimonies.edit', $testimony) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white font-medium rounded-xl hover:bg-amber-600 transition-all duration-300 shadow-sm hover:shadow-md">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                </div>
            @endif
        </div>

        <!-- Main Testimony Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 p-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-white/20 text-white backdrop-blur-sm">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $testimony->category_display }}
                            </span>
                            @if(!$testimony->is_approved)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pending Approval
                                </span>
                            @endif
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight">
                            {{ $testimony->title }}
                        </h1>
                        <div class="flex items-center space-x-4 text-amber-100">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $testimony->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-clock"></i>
                                <span>{{ $testimony->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Author Info -->
            <div class="p-8 border-b border-gray-200/50">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        @if($testimony->member->photo_path)
                            <img src="{{ asset('storage/' . $testimony->member->photo_path) }}" alt="{{ $testimony->member->full_name }}" class="w-full h-full object-cover rounded-2xl">
                        @else
                            <i class="fas fa-user text-white text-xl"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $testimony->member->full_name }}</h3>
                        <p class="text-gray-600">{{ $testimony->member->chapter ?? 'ACCRA' }} Chapter</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-crown mr-1"></i>
                                {{ ucfirst($testimony->member->membership_type ?? 'member') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimony Content -->
            <div class="p-8">
                <div class="prose prose-lg max-w-none">
                    <div class="text-gray-800 leading-relaxed text-lg whitespace-pre-line">{{ $testimony->content }}</div>
                </div>
            </div>

            <!-- Tags -->
            @if($testimony->tags && count($testimony->tags) > 0)
                <div class="px-8 pb-6">
                    <h4 class="text-sm font-bold text-gray-800 mb-3">Tags</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($testimony->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                <i class="fas fa-hashtag mr-1 text-xs"></i>{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="px-8 pb-8">
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200/50">
                    <div class="flex items-center space-x-4">
                        <button onclick="shareTestimony()" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            <i class="fas fa-share-alt mr-2"></i>
                            Share
                        </button>
                        <button onclick="copyLink()" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas fa-link mr-2"></i>
                            Copy Link
                        </button>
                    </div>
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-eye mr-1"></i>
                        Inspiring others since {{ $testimony->created_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Testimonies -->
        @if($relatedTestimonies->count() > 0)
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-heart text-amber-500 mr-3"></i>
                    More {{ $testimony->category_display }} Testimonies
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedTestimonies as $related)
                        <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/30 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center">
                                    @if($related->member->photo_path)
                                        <img src="{{ asset('storage/' . $related->member->photo_path) }}" alt="{{ $related->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <i class="fas fa-user text-white text-sm"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $related->member->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $related->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <h4 class="font-bold text-gray-900 mb-2 group-hover:text-amber-700 transition-colors duration-200 line-clamp-2">
                                {{ $related->title }}
                            </h4>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $related->excerpt }}
                            </p>
                            
                            <a href="{{ route('member.testimonies.show', $related) }}" class="inline-flex items-center text-amber-600 hover:text-amber-700 font-medium text-sm">
                                Read Testimony
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 rounded-3xl p-8 text-center shadow-2xl">
            <div class="max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold text-white mb-4">Inspired by this testimony?</h3>
                <p class="text-amber-100 mb-6 text-lg">
                    God is working in your life too! Share your own story and encourage others in their faith journey.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('member.testimonies.create') }}" class="inline-flex items-center px-8 py-4 bg-white text-amber-600 font-bold rounded-xl hover:bg-amber-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-plus mr-3"></i>
                        Share Your Testimony
                    </a>
                    <a href="{{ route('member.prayer-requests.index') }}" class="inline-flex items-center px-8 py-4 bg-white/20 text-white font-bold rounded-xl hover:bg-white/30 transition-all duration-300 backdrop-blur-sm border border-white/30">
                        <i class="fas fa-praying-hands mr-3"></i>
                        Prayer Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shareTestimony() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $testimony->title }}',
            text: 'Check out this inspiring testimony: {{ Str::limit($testimony->content, 100) }}',
            url: window.location.href
        });
    } else {
        copyLink();
    }
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
        button.classList.add('bg-green-500', 'hover:bg-green-600');
        button.classList.remove('bg-gray-500', 'hover:bg-gray-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-500', 'hover:bg-green-600');
            button.classList.add('bg-gray-500', 'hover:bg-gray-600');
        }, 2000);
    });
}
</script>

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
