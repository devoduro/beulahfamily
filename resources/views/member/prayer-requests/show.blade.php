@extends('member.layouts.app')

@section('title', $prayerRequest->title)
@section('subtitle', 'Prayer Request')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Back Navigation -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('member.prayer-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-white/70 backdrop-blur-sm text-gray-700 font-medium rounded-xl hover:bg-white/90 transition-all duration-300 shadow-sm hover:shadow-md border border-gray-200/50">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Prayer Requests
            </a>
            
            @if($prayerRequest->member_id === auth('member')->id())
                <div class="flex space-x-2">
                    @if($prayerRequest->status !== 'answered')
                        <a href="{{ route('member.prayer-requests.edit', $prayerRequest) }}" class="inline-flex items-center px-4 py-2 bg-purple-500 text-white font-medium rounded-xl hover:bg-purple-600 transition-all duration-300 shadow-sm hover:shadow-md">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                    @endif
                    
                    @if($prayerRequest->status === 'active')
                        <button onclick="showAnsweredModal()" class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-medium rounded-xl hover:bg-green-600 transition-all duration-300 shadow-sm hover:shadow-md">
                            <i class="fas fa-check-circle mr-2"></i>
                            Mark as Answered
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Main Prayer Request Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $prayerRequest->urgency_color }} bg-white/90">
                                @if($prayerRequest->urgency === 'urgent')
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                @elseif($prayerRequest->urgency === 'high')
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                @elseif($prayerRequest->urgency === 'medium')
                                    <i class="fas fa-circle mr-1"></i>
                                @else
                                    <i class="fas fa-dot-circle mr-1"></i>
                                @endif
                                {{ ucfirst($prayerRequest->urgency) }} Priority
                            </span>
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-white/20 text-white backdrop-blur-sm">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $prayerRequest->category_display }}
                            </span>
                            
                            @if($prayerRequest->status === 'answered')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Answered
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight">
                            {{ $prayerRequest->title }}
                        </h1>
                        
                        <div class="flex items-center space-x-4 text-purple-100">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $prayerRequest->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-clock"></i>
                                <span>{{ $prayerRequest->created_at->diffForHumans() }}</span>
                            </div>
                            @if($prayerRequest->expires_at)
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-hourglass-half"></i>
                                    <span>{{ $prayerRequest->days_remaining }} days remaining</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requester Info -->
            @if(!$prayerRequest->is_anonymous)
                <div class="p-8 border-b border-gray-200/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            @if($prayerRequest->member->photo_path)
                                <img src="{{ asset('storage/' . $prayerRequest->member->photo_path) }}" alt="{{ $prayerRequest->member->full_name }}" class="w-full h-full object-cover rounded-2xl">
                            @else
                                <i class="fas fa-user text-white text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $prayerRequest->member->full_name }}</h3>
                            <p class="text-gray-600">{{ $prayerRequest->member->chapter ?? 'ACCRA' }} Chapter</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-crown mr-1"></i>
                                    {{ ucfirst($prayerRequest->member->membership_type ?? 'member') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-8 border-b border-gray-200/50">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-secret text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Anonymous Request</h3>
                            <p class="text-gray-600">A member of Beulah family</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Prayer Request Content -->
            <div class="p-8">
                <div class="prose prose-lg max-w-none">
                    <div class="text-gray-800 leading-relaxed text-lg whitespace-pre-line">{{ $prayerRequest->description }}</div>
                </div>
            </div>

            <!-- Prayer Stats and Action -->
            <div class="px-8 pb-8">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-700">{{ $prayerRequest->total_prayers }}</div>
                                <div class="text-sm text-gray-600">{{ Str::plural('Prayer', $prayerRequest->total_prayers) }}</div>
                            </div>
                            
                            @if($prayerRequest->status === 'answered' && $prayerRequest->answered_at)
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-700">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="text-sm text-gray-600">Answered {{ $prayerRequest->answered_at->diffForHumans() }}</div>
                                </div>
                            @endif
                        </div>
                        
                        @if($prayerRequest->status === 'active')
                            @if(!$hasPrayed)
                                <button onclick="prayForRequest()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-praying-hands mr-3"></i>
                                    I'll Pray for This
                                </button>
                            @else
                                <div class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 font-bold rounded-xl border border-green-200">
                                    <i class="fas fa-check-circle mr-3"></i>
                                    You've Prayed for This
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Answer Testimony (if answered) -->
            @if($prayerRequest->status === 'answered' && $prayerRequest->answer_testimony)
                <div class="px-8 pb-8">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200/50">
                        <h4 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Prayer Answered - Praise Report!
                        </h4>
                        <div class="text-green-700 leading-relaxed whitespace-pre-line">{{ $prayerRequest->answer_testimony }}</div>
                        <div class="mt-4 text-sm text-green-600">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Answered on {{ $prayerRequest->answered_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Related Prayer Requests -->
        @if($relatedRequests->count() > 0)
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-praying-hands text-purple-500 mr-3"></i>
                    More {{ $prayerRequest->category_display }} Requests
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedRequests as $related)
                        <div class="group bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/30 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $related->urgency_color }}">
                                    {{ ucfirst($related->urgency) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $related->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <h4 class="font-bold text-gray-900 mb-2 group-hover:text-purple-700 transition-colors duration-200 line-clamp-2">
                                {{ $related->title }}
                            </h4>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ Str::limit($related->description, 100) }}
                            </p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-praying-hands text-purple-500 text-xs"></i>
                                    <span class="text-xs text-gray-600">{{ $related->total_prayers }} prayers</span>
                                </div>
                                <a href="{{ route('member.prayer-requests.show', $related) }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-medium text-sm">
                                    Pray Now
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-3xl p-8 text-center shadow-2xl">
            <div class="max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold text-white mb-4">Need prayer too?</h3>
                <p class="text-purple-100 mb-6 text-lg">
                    Beulah family is here to support you. Don't carry your burdens alone - let us pray with you.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('member.prayer-requests.create') }}" class="inline-flex items-center px-8 py-4 bg-white text-purple-600 font-bold rounded-xl hover:bg-purple-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-plus mr-3"></i>
                        Submit Prayer Request
                    </a>
                    <a href="{{ route('member.testimonies.index') }}" class="inline-flex items-center px-8 py-4 bg-white/20 text-white font-bold rounded-xl hover:bg-white/30 transition-all duration-300 backdrop-blur-sm border border-white/30">
                        <i class="fas fa-heart mr-3"></i>
                        Read Testimonies
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Answered Modal -->
<div id="answeredModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Prayer Answered!</h3>
                <p class="text-gray-600">Share how God answered your prayer to encourage others.</p>
            </div>
            
            <form action="{{ route('member.prayer-requests.mark-answered', $prayerRequest) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="answer_testimony" class="block text-sm font-bold text-gray-700 mb-2">
                        How did God answer your prayer? <span class="text-red-500">*</span>
                    </label>
                    <textarea id="answer_testimony" name="answer_testimony" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Share your praise report..." required></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors">
                        Mark as Answered
                    </button>
                    <button type="button" onclick="hideAnsweredModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prayForRequest() {
    fetch('{{ route("member.prayer-requests.pray", $prayerRequest) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the UI
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function showAnsweredModal() {
    document.getElementById('answeredModal').classList.remove('hidden');
}

function hideAnsweredModal() {
    document.getElementById('answeredModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('answeredModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideAnsweredModal();
    }
});
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
