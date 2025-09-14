@extends('components.app-layout')

@section('title', 'SMS Templates')
@section('subtitle', 'Manage reusable SMS message templates')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">SMS Templates</h1>
                <p class="text-gray-600 mt-2">Create and manage reusable SMS message templates</p>
            </div>
            <a href="{{ route('sms.templates.create') }}" 
               class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Create Template
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <input type="text" name="search" placeholder="Search templates..." 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 bg-white/50 border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                </div>

                <!-- Category Filter -->
                <div>
                    <select name="category" class="w-full px-4 py-2 bg-white/50 border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                        <option value="">All Categories</option>
                        <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>General</option>
                        <option value="birthday" {{ request('category') === 'birthday' ? 'selected' : '' }}>Birthday</option>
                        <option value="event" {{ request('category') === 'event' ? 'selected' : '' }}>Event</option>
                        <option value="announcement" {{ request('category') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                        <option value="reminder" {{ request('category') === 'reminder' ? 'selected' : '' }}>Reminder</option>
                        <option value="welcome" {{ request('category') === 'welcome' ? 'selected' : '' }}>Welcome</option>
                        <option value="thank_you" {{ request('category') === 'thank_you' ? 'selected' : '' }}>Thank You</option>
                        <option value="invitation" {{ request('category') === 'invitation' ? 'selected' : '' }}>Invitation</option>
                        <option value="emergency" {{ request('category') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="custom" {{ request('category') === 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="w-full px-4 py-2 bg-white/50 border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($templates as $template)
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                    <!-- Template Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $template->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $template->description }}</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            
                            <!-- Actions Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-10">
                                    <a href="{{ route('sms.templates.show', $template) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-eye mr-2"></i>View
                                    </a>
                                    <a href="{{ route('sms.templates.edit', $template) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </a>
                                    <button onclick="duplicateTemplate({{ $template->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-copy mr-2"></i>Duplicate
                                    </button>
                                    <button onclick="toggleStatus({{ $template->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-{{ $template->is_active ? 'pause' : 'play' }} mr-2"></i>
                                        {{ $template->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <div class="border-t"></div>
                                    <button onclick="deleteTemplate({{ $template->id }})" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-trash mr-2"></i>Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Badge -->
                    <div class="mb-4">
                        @php
                            $categoryColors = [
                                'general' => 'bg-blue-100 text-blue-800',
                                'birthday' => 'bg-pink-100 text-pink-800',
                                'event' => 'bg-purple-100 text-purple-800',
                                'announcement' => 'bg-yellow-100 text-yellow-800',
                                'reminder' => 'bg-orange-100 text-orange-800',
                                'welcome' => 'bg-green-100 text-green-800',
                                'thank_you' => 'bg-indigo-100 text-indigo-800',
                                'invitation' => 'bg-cyan-100 text-cyan-800',
                                'emergency' => 'bg-red-100 text-red-800',
                                'custom' => 'bg-gray-100 text-gray-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $categoryColors[$template->category] ?? $categoryColors['custom'] }}">
                            {{ ucwords(str_replace('_', ' ', $template->category)) }}
                        </span>
                    </div>

                    <!-- Message Preview -->
                    <div class="bg-gray-50 rounded-xl p-3 mb-4">
                        <p class="text-sm text-gray-700 line-clamp-3">{{ $template->message }}</p>
                    </div>

                    <!-- Variables -->
                    @if($template->variables && count($template->variables) > 0)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Variables:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($template->variables as $variable)
                                <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md">
                                    {{ $variable }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Footer Stats -->
                    <div class="flex justify-between items-center text-xs text-gray-500 pt-4 border-t border-gray-100">
                        <span>Used {{ $template->usage_count ?? 0 }} times</span>
                        <span>{{ $template->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No templates found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first SMS template.</p>
                    <a href="{{ route('sms.templates.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        Create Template
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($templates->hasPages())
        <div class="mt-8">
            {{ $templates->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function toggleStatus(templateId) {
    fetch(`/sms/templates/${templateId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to update template status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function duplicateTemplate(templateId) {
    fetch(`/sms/templates/${templateId}/duplicate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to duplicate template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function deleteTemplate(templateId) {
    if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        fetch(`/sms/templates/${templateId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete template');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}
</script>
@endsection
