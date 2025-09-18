<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.prayer-requests.index') }}" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold gradient-text">Prayer Request Details</h1>
                            <p class="text-gray-600 mt-2">Review and manage this prayer request</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($prayerRequest->status === 'active')
                            <form method="POST" action="{{ route('admin.prayer-requests.mark-answered', $prayerRequest) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Mark Answered
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.prayer-requests.close', $prayerRequest) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    Close Request
                                </button>
                            </form>
                        @elseif($prayerRequest->status === 'closed')
                            <form method="POST" action="{{ route('admin.prayer-requests.reopen', $prayerRequest) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-redo mr-2"></i>
                                    Reopen Request
                                </button>
                            </form>
                        @elseif($prayerRequest->status === 'answered')
                            <form method="POST" action="{{ route('admin.prayer-requests.reopen', $prayerRequest) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-redo mr-2"></i>
                                    Reopen Request
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.prayer-requests.destroy', $prayerRequest) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this prayer request? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Prayer Request Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Status Header -->
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $prayerRequest->urgency_color }}">
                                        @if($prayerRequest->urgency === 'urgent')
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                        @elseif($prayerRequest->urgency === 'high')
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                        @elseif($prayerRequest->urgency === 'medium')
                                            <i class="fas fa-circle mr-2"></i>
                                        @else
                                            <i class="fas fa-dot-circle mr-2"></i>
                                        @endif
                                        {{ ucfirst($prayerRequest->urgency) }} Priority
                                    </span>
                                    
                                    @if($prayerRequest->status === 'answered')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-2"></i>Answered
                                        </span>
                                    @elseif($prayerRequest->status === 'closed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-times-circle mr-2"></i>Closed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-circle mr-2 animate-pulse"></i>Active
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $prayerRequest->created_at->format('M d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Category -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-tag mr-2"></i>{{ $prayerRequest->category_display }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $prayerRequest->title }}</h2>

                            <!-- Description -->
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-8">
                                {!! nl2br(e($prayerRequest->description)) !!}
                            </div>

                            <!-- Prayer Count -->
                            <div class="bg-purple-50 rounded-lg p-6 mb-8">
                                <div class="flex items-center justify-center space-x-4">
                                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-praying-hands text-purple-600 text-2xl"></i>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-3xl font-bold text-purple-900">{{ number_format($prayerRequest->total_prayers) }}</p>
                                        <p class="text-sm text-purple-700">{{ Str::plural('prayer', $prayerRequest->total_prayers) }} offered</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Expiration Info -->
                            @if($prayerRequest->expires_at)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-yellow-600 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-yellow-800">Expires on {{ $prayerRequest->expires_at->format('M d, Y') }}</p>
                                            <p class="text-sm text-yellow-700">
                                                @if($prayerRequest->expires_at->isPast())
                                                    This request has expired {{ $prayerRequest->expires_at->diffForHumans() }}
                                                @else
                                                    {{ $prayerRequest->days_remaining }} days remaining
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Privacy Indicators -->
                            <div class="flex items-center space-x-3 mb-6">
                                @if($prayerRequest->is_private)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-600">
                                        <i class="fas fa-lock mr-2"></i>Private Request
                                    </span>
                                @endif
                                @if($prayerRequest->is_anonymous)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-yellow-100 text-yellow-600">
                                        <i class="fas fa-user-secret mr-2"></i>Anonymous Submission
                                    </span>
                                @endif
                            </div>

                            <!-- Answer Testimony -->
                            @if($prayerRequest->answer_testimony)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                            <i class="fas fa-check text-green-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-green-900 mb-2">Prayer Answered!</h4>
                                            <p class="text-green-800 leading-relaxed">{{ $prayerRequest->answer_testimony }}</p>
                                            <p class="text-sm text-green-700 mt-2">
                                                Answered on {{ $prayerRequest->answered_at->format('M d, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Member Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $prayerRequest->is_anonymous ? 'Anonymous Request' : 'Member Information' }}
                        </h3>
                        
                        @if(!$prayerRequest->is_anonymous)
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center">
                                    @if($prayerRequest->member->photo_path)
                                        <img src="{{ asset('storage/' . $prayerRequest->member->photo_path) }}" alt="{{ $prayerRequest->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                    @else
                                        <i class="fas fa-user text-white text-lg"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $prayerRequest->member->full_name }}</h4>
                                    <p class="text-sm text-gray-600">Member ID: {{ $prayerRequest->member->member_id }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Chapter</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $prayerRequest->member->chapter ?? 'ACCRA' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Email</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $prayerRequest->member->email }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Phone</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $prayerRequest->member->phone ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Member Since</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $prayerRequest->member->created_at->format('M Y') }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <span class="text-gray-500 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>Member Profile: {{ $prayerRequest->member->member_id }}
                                </span>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user-secret text-yellow-600 text-2xl"></i>
                                </div>
                                <p class="text-gray-600">This prayer request was submitted anonymously to protect the member's privacy.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Request Statistics -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Statistics</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-praying-hands text-purple-600"></i>
                                    <span class="text-sm text-gray-600">Total Prayers</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($prayerRequest->total_prayers) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-users text-blue-600"></i>
                                    <span class="text-sm text-gray-600">Unique Prayers</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($prayerRequest->unique_prayers ?? 0) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar-day text-green-600"></i>
                                    <span class="text-sm text-gray-600">Days Active</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $prayerRequest->created_at->diffInDays(now()) + 1 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        
                        <div class="space-y-3">
                            @if(!$prayerRequest->is_private)
                                <a href="{{ route('member.prayer-requests.show', $prayerRequest) }}" target="_blank" class="block w-full px-4 py-2 bg-blue-600 text-white text-center font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-external-link-alt mr-2"></i>View Public Page
                                </a>
                            @endif
                            
                            <button onclick="copyToClipboard('{{ route('admin.prayer-requests.show', $prayerRequest) }}')" class="block w-full px-4 py-2 bg-gray-600 text-white text-center font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-copy mr-2"></i>Copy Admin Link
                            </button>
                            
                            <a href="{{ route('admin.prayer-requests.export') }}?ids={{ $prayerRequest->id }}" class="block w-full px-4 py-2 bg-green-600 text-white text-center font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>Export as CSV
                            </a>
                        </div>
                    </div>

                    <!-- Related Prayer Requests -->
                    @if($relatedRequests->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Prayer Requests</h3>
                            
                            <div class="space-y-3">
                                @foreach($relatedRequests as $related)
                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                                        <h4 class="font-medium text-gray-900 text-sm line-clamp-2 mb-1">{{ $related->title }}</h4>
                                        <p class="text-xs text-gray-600 mb-2">
                                            {{ $related->is_anonymous ? 'Anonymous' : $related->member->full_name }}
                                        </p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">{{ $related->created_at->format('M d, Y') }}</span>
                                            <a href="{{ route('admin.prayer-requests.show', $related) }}" class="text-purple-600 hover:text-purple-800 text-xs font-medium">
                                                View <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
            button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            
            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-gray-600', 'hover:bg-gray-700');
            }, 2000);
        }, 2000);
    }
    </script>

    <style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
</x-app-layout>
