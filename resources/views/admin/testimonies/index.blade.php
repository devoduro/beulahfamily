<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold gradient-text">Testimonies Management</h1>
                        <p class="text-gray-600 mt-2">Manage and moderate member testimonies</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.testimonies.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            Export CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Testimonies</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Public</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['public'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <form method="GET" action="{{ route('admin.testimonies.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search testimonies...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                            <select name="visibility" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Visibility</option>
                                <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8" id="bulk-actions" style="display: none;">
                <form method="POST" action="{{ route('admin.testimonies.bulk-action') }}" id="bulk-form">
                    @csrf
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700">
                                <span id="selected-count">0</span> testimonies selected
                            </span>
                            <select name="action" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select Action</option>
                                <option value="approve">Approve Selected</option>
                                <option value="reject">Reject Selected</option>
                                <option value="delete">Delete Selected</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                Apply Action
                            </button>
                            <button type="button" onclick="clearSelection()" class="px-4 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition-colors">
                                Clear Selection
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Testimonies Grid -->
            @if($testimonies->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($testimonies as $testimony)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <!-- Selection Checkbox -->
                                <div class="flex items-start justify-between mb-4">
                                    <input type="checkbox" name="testimonies[]" value="{{ $testimony->id }}" class="testimony-checkbox mt-1" onchange="updateBulkActions()">
                                    <div class="flex items-center space-x-2">
                                        @if($testimony->is_approved)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @endif
                                        
                                        @if($testimony->is_public)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-eye mr-1"></i>Public
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-eye-slash mr-1"></i>Private
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-amber-100 text-amber-800">
                                        <i class="fas fa-tag mr-1"></i>{{ $testimony->category_display }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2">{{ $testimony->title }}</h3>

                                <!-- Content Preview -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($testimony->content, 120) }}</p>

                                <!-- Member Info -->
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center">
                                        @if($testimony->member->photo_path)
                                            <img src="{{ asset('storage/' . $testimony->member->photo_path) }}" alt="{{ $testimony->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            <i class="fas fa-user text-white text-xs"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $testimony->member->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $testimony->member->chapter ?? 'ACCRA' }} Chapter</p>
                                    </div>
                                </div>

                                <!-- Meta Info -->
                                <div class="text-xs text-gray-500 mb-4">
                                    <div class="flex items-center justify-between">
                                        <span><i class="fas fa-calendar-alt mr-1"></i>{{ $testimony->created_at->format('M d, Y') }}</span>
                                        <span><i class="fas fa-clock mr-1"></i>{{ $testimony->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <a href="{{ route('admin.testimonies.show', $testimony) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        <i class="fas fa-eye mr-1"></i>View Details
                                    </a>
                                    
                                    <div class="flex items-center space-x-2">
                                        @if(!$testimony->is_approved)
                                            <form method="POST" action="{{ route('admin.testimonies.approve', $testimony) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm" title="Approve">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.testimonies.reject', $testimony) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm" title="Revoke Approval">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.testimonies.destroy', $testimony) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this testimony?')">
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
                @if($testimonies->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $testimonies->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No testimonies found</h3>
                    <p class="text-gray-500">
                        @if(request()->hasAny(['search', 'category', 'status', 'visibility']))
                            No testimonies match your current filters.
                        @else
                            No testimonies have been submitted yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'category', 'status', 'visibility']))
                        <a href="{{ route('admin.testimonies.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-2 inline-block">
                            Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.testimony-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        
        selectedCount.textContent = checkboxes.length;
        
        if (checkboxes.length > 0) {
            bulkActions.style.display = 'block';
            
            // Add selected IDs to bulk form
            const bulkForm = document.getElementById('bulk-form');
            const existingInputs = bulkForm.querySelectorAll('input[name="testimonies[]"]');
            existingInputs.forEach(input => input.remove());
            
            checkboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'testimonies[]';
                input.value = checkbox.value;
                bulkForm.appendChild(input);
            });
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.testimony-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        updateBulkActions();
    }

    // Confirm bulk actions
    document.getElementById('bulk-form').addEventListener('submit', function(e) {
        const action = this.querySelector('select[name="action"]').value;
        const count = document.querySelectorAll('.testimony-checkbox:checked').length;
        
        if (!action) {
            e.preventDefault();
            alert('Please select an action.');
            return;
        }
        
        const actionText = action === 'delete' ? 'delete' : action;
        if (!confirm(`Are you sure you want to ${actionText} ${count} selected testimonies?`)) {
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
