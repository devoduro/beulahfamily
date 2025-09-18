<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.testimonies.index') }}" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold gradient-text">Testimony Details</h1>
                            <p class="text-gray-600 mt-2">Review and manage this testimony</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if(!$testimony->is_approved)
                            <form method="POST" action="{{ route('admin.testimonies.approve', $testimony) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Approve
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.testimonies.reject', $testimony) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    Revoke Approval
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.testimonies.destroy', $testimony) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimony? This action cannot be undone.')">
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
                <!-- Testimony Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Status Header -->
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($testimony->is_approved)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-2"></i>Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-2"></i>Pending Approval
                                        </span>
                                    @endif
                                    
                                    @if($testimony->is_public)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-eye mr-2"></i>Public
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-eye-slash mr-2"></i>Private
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $testimony->created_at->format('M d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Category -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-tag mr-2"></i>{{ $testimony->category_display }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $testimony->title }}</h2>

                            <!-- Content -->
                            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($testimony->content)) !!}
                            </div>

                            <!-- Tags -->
                            @if($testimony->tags && count($testimony->tags) > 0)
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Tags</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($testimony->tags as $tag)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                #{{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Approval Info -->
                            @if($testimony->is_approved && $testimony->approvedBy)
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check text-green-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                Approved by {{ $testimony->approvedBy->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $testimony->approved_at->format('M d, Y \a\t g:i A') }}
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Member Information</h3>
                        
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center">
                                @if($testimony->member->photo_path)
                                    <img src="{{ asset('storage/' . $testimony->member->photo_path) }}" alt="{{ $testimony->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                @else
                                    <i class="fas fa-user text-white text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $testimony->member->full_name }}</h4>
                                <p class="text-sm text-gray-600">Member ID: {{ $testimony->member->member_id }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Chapter</span>
                                <span class="text-sm font-medium text-gray-900">{{ $testimony->member->chapter ?? 'ACCRA' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Email</span>
                                <span class="text-sm font-medium text-gray-900">{{ $testimony->member->email }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Phone</span>
                                <span class="text-sm font-medium text-gray-900">{{ $testimony->member->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Member Since</span>
                                <span class="text-sm font-medium text-gray-900">{{ $testimony->member->created_at->format('M Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="text-gray-500 text-sm">
                                <i class="fas fa-info-circle mr-1"></i>Member Profile: {{ $testimony->member->member_id }}
                            </span>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-thumbs-up text-blue-600"></i>
                                    <span class="text-sm text-gray-600">Likes</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($testimony->likes_count) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-eye text-green-600"></i>
                                    <span class="text-sm text-gray-600">Views</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($testimony->views_count ?? 0) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-share text-purple-600"></i>
                                    <span class="text-sm text-gray-600">Shares</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($testimony->shares_count ?? 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                        
                        <div class="space-y-3">
                            @if($testimony->is_public)
                                <a href="{{ route('member.testimonies.show', $testimony) }}" target="_blank" class="block w-full px-4 py-2 bg-blue-600 text-white text-center font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-external-link-alt mr-2"></i>View Public Page
                                </a>
                            @endif
                            
                            <button onclick="copyToClipboard('{{ route('admin.testimonies.show', $testimony) }}')" class="block w-full px-4 py-2 bg-gray-600 text-white text-center font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-copy mr-2"></i>Copy Admin Link
                            </button>
                            
                            <a href="{{ route('admin.testimonies.export') }}?ids={{ $testimony->id }}" class="block w-full px-4 py-2 bg-green-600 text-white text-center font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>Export as CSV
                            </a>
                        </div>
                    </div>

                    <!-- Related Testimonies -->
                    @if($relatedTestimonies->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Testimonies</h3>
                            
                            <div class="space-y-3">
                                @foreach($relatedTestimonies as $related)
                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                                        <h4 class="font-medium text-gray-900 text-sm line-clamp-2 mb-1">{{ $related->title }}</h4>
                                        <p class="text-xs text-gray-600 mb-2">{{ $related->member->full_name }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">{{ $related->created_at->format('M d, Y') }}</span>
                                            <a href="{{ route('admin.testimonies.show', $related) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
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
    </style>
</x-app-layout>
