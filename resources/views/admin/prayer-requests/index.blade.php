<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold gradient-text">Prayer Requests Management</h1>
                        <p class="text-gray-600 mt-2">Manage and monitor prayer requests from church members</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.prayer-requests.statistics') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Statistics
                        </a>
                        <a href="{{ route('admin.prayer-requests.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            Export CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-praying-hands text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Requests</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-circle text-green-600 text-xl animate-pulse"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Answered</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['answered'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Urgent</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['urgent'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hands text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Prayers</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_prayers']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('admin.prayer-requests.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Search requests...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urgency</label>
                            <select name="urgency" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">All Urgency</option>
                                @foreach($urgencyLevels as $key => $label)
                                    <option value="{{ $key }}" {{ request('urgency') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Privacy</label>
                            <select name="privacy" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">All Privacy</option>
                                <option value="public" {{ request('privacy') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ request('privacy') == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8" id="bulk-actions" style="display: none;">
                <form method="POST" action="{{ route('admin.prayer-requests.bulk-action') }}" id="bulk-form">
                    @csrf
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">
                                <span id="selected-count">0</span> prayer requests selected
                            </span>
                            <select name="action" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                                <option value="">Select Action</option>
                                <option value="close">Close Selected</option>
                                <option value="reopen">Reopen Selected</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                Apply Action
                            </button>
                            <button type="button" onclick="clearSelection()" class="px-4 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition-colors">
                                Clear Selection
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Prayer Requests Grid -->
            @if($prayerRequests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($prayerRequests as $request)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <!-- Selection Checkbox -->
                                <div class="flex items-start justify-between mb-4">
                                    <input type="checkbox" name="prayer_requests[]" value="{{ $request->id }}" class="request-checkbox mt-1" onchange="updateBulkActions()">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $request->urgency_color }}">
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
                                        
                                        @if($request->status === 'answered')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Answered
                                            </span>
                                        @elseif($request->status === 'closed')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-times-circle mr-1"></i>Closed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-circle mr-1 animate-pulse"></i>Active
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-tag mr-1"></i>{{ $request->category_display }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">{{ $request->title }}</h3>

                                <!-- Description Preview -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($request->description, 120) }}</p>

                                <!-- Prayer Count -->
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-praying-hands text-purple-600 text-xs"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $request->total_prayers }} {{ Str::plural('prayer', $request->total_prayers) }}</span>
                                    </div>
                                    
                                    @if($request->expires_at)
                                        <div class="flex items-center space-x-1 text-xs text-gray-500">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $request->days_remaining }} days left</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Member Info -->
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        @if(!$request->is_anonymous && $request->member->photo_path)
                                            <img src="{{ asset('storage/' . $request->member->photo_path) }}" alt="{{ $request->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <i class="fas fa-user text-white text-xs"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $request->is_anonymous ? 'Anonymous' : $request->member->full_name }}
                                        </p>
                                        @if(!$request->is_anonymous)
                                            <p class="text-xs text-gray-500">{{ $request->member->chapter ?? 'ACCRA' }} Chapter</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Privacy Indicators -->
                                <div class="flex items-center space-x-2 mb-4">
                                    @if($request->is_private)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600">
                                            <i class="fas fa-lock mr-1"></i>Private
                                        </span>
                                    @endif
                                    @if($request->is_anonymous)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-yellow-100 text-yellow-600">
                                            <i class="fas fa-user-secret mr-1"></i>Anonymous
                                        </span>
                                    @endif
                                </div>

                                <!-- Meta Info -->
                                <div class="text-xs text-gray-500 mb-4">
                                    <div class="flex items-center justify-between">
                                        <span><i class="fas fa-calendar-alt mr-1"></i>{{ $request->created_at->format('M d, Y') }}</span>
                                        <span><i class="fas fa-clock mr-1"></i>{{ $request->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <a href="{{ route('admin.prayer-requests.show', $request) }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                        <i class="fas fa-eye mr-1"></i>View Details
                                    </a>
                                    
                                    <div class="flex items-center space-x-2">
                                        @if($request->status === 'active')
                                            <form method="POST" action="{{ route('admin.prayer-requests.close', $request) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm" title="Close Request">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @elseif($request->status === 'closed')
                                            <form method="POST" action="{{ route('admin.prayer-requests.reopen', $request) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm" title="Reopen Request">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.prayer-requests.destroy', $request) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this prayer request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($prayerRequests->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $prayerRequests->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-praying-hands text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No prayer requests found</h3>
                    <p class="text-gray-500">
                        @if(request()->hasAny(['search', 'category', 'urgency', 'status', 'privacy']))
                            No prayer requests match your current filters.
                        @else
                            No prayer requests have been submitted yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'category', 'urgency', 'status', 'privacy']))
                        <a href="{{ route('admin.prayer-requests.index') }}" class="text-purple-600 hover:text-purple-800 font-medium mt-2 inline-block">
                            Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.request-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        
        selectedCount.textContent = checkboxes.length;
        
        if (checkboxes.length > 0) {
            bulkActions.style.display = 'block';
            
            // Add selected IDs to bulk form
            const bulkForm = document.getElementById('bulk-form');
            const existingInputs = bulkForm.querySelectorAll('input[name="prayer_requests[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'prayer_requests[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.request-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        updateBulkActions();
    }

    // Confirm bulk actions
    document.getElementById('bulk-form').addEventListener('submit', function(e) {
        const action = this.querySelector('select[name="action"]').value;
        const count = document.querySelectorAll('.request-checkbox:checked').length;
        
        if (!action) {
            e.preventDefault();
            alert('Please select an action.');
            return;
        }
        
        const actionText = action === 'delete' ? 'delete' : action;
        if (!confirm(`Are you sure you want to ${actionText} ${count} selected prayer requests?`)) {
            e.preventDefault();
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
</x-app-layout>
