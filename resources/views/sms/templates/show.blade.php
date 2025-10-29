@extends('components.app-layout')

@section('title', 'SMS Template Details')
@section('subtitle', 'View and manage SMS template')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-file-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">{{ $template->name }}</h1>
            <p class="text-gray-600 text-lg mt-2">SMS Template Details</p>
        </div>

        <!-- Template Info -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8 mb-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-info-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Template Information</h2>
                        <p class="text-gray-500 text-sm">Basic template details and status</p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('sms.templates.edit', $template) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <button onclick="duplicateTemplate()" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors shadow-lg">
                        <i class="fas fa-copy mr-2"></i>
                        Duplicate
                    </button>
                    <button onclick="toggleStatus()" 
                            class="inline-flex items-center px-4 py-2 {{ $template->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-xl transition-colors shadow-lg">
                        <i class="fas fa-{{ $template->is_active ? 'pause' : 'play' }} mr-2"></i>
                        {{ $template->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Template Name</label>
                        <p class="text-lg font-medium text-gray-900">{{ $template->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Category</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $template->category === 'birthday' ? 'bg-pink-100 text-pink-800' : 
                               ($template->category === 'event' ? 'bg-green-100 text-green-800' : 
                               ($template->category === 'announcement' ? 'bg-blue-100 text-blue-800' : 
                               ($template->category === 'emergency' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                            <i class="fas fa-{{ $template->category === 'birthday' ? 'birthday-cake' : 
                                              ($template->category === 'event' ? 'calendar' : 
                                              ($template->category === 'announcement' ? 'bullhorn' : 
                                              ($template->category === 'emergency' ? 'exclamation-triangle' : 'tag'))) }} mr-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $template->category)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas fa-{{ $template->is_active ? 'check-circle' : 'times-circle' }} mr-2"></i>
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Created</label>
                        <p class="text-gray-900">{{ $template->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $template->updated_at->format('M d, Y g:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Usage Count</label>
                        <p class="text-2xl font-bold text-blue-600">{{ $template->usage_count ?? 0 }}</p>
                    </div>
                </div>
            </div>

            @if($template->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                <p class="text-gray-700 leading-relaxed">{{ $template->description }}</p>
            </div>
            @endif
        </div>

        <!-- Message Content -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8 mb-8">
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                    <i class="fas fa-comment-alt text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Message Content</h2>
                    <p class="text-gray-500 text-sm">Template message and variables</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Message Text -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-3">Message Text</label>
                    <div class="bg-gray-50 rounded-2xl p-6 border-2 border-gray-200">
                        <p class="text-gray-900 leading-relaxed whitespace-pre-wrap">{{ $template->message }}</p>
                    </div>
                    
                    <!-- Message Stats -->
                    <div class="flex justify-between items-center mt-3 text-sm text-gray-500">
                        <span>{{ strlen($template->message) }} characters</span>
                        <span>{{ ceil(strlen($template->message) / 160) }} SMS</span>
                    </div>
                </div>

                <!-- Variables -->
                @if($template->variables && count($template->variables) > 0)
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-3">Template Variables</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($template->variables as $variable)
                            <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                <i class="fas fa-code mr-2"></i>
                                {{{{ $variable }}}}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">These variables will be replaced with actual values when sending SMS</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Preview -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                    <i class="fas fa-eye text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Preview</h2>
                    <p class="text-gray-500 text-sm">How the message will look with sample data</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-200">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-white"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <p class="text-gray-700" id="messagePreview"></p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Sample SMS message with placeholder data</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('sms.templates.index') }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 border-2 border-gray-300 rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-arrow-left mr-3"></i>
                Back to Templates
            </a>
            <a href="{{ route('sms.templates.edit', $template) }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                <i class="fas fa-edit mr-3"></i>
                Edit Template
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate preview with sample data
    const message = @json($template->message);
    let preview = message
        .replace(/\{\{first_name\}\}/g, 'John')
        .replace(/\{\{last_name\}\}/g, 'Doe')
        .replace(/\{\{full_name\}\}/g, 'John Doe')
        .replace(/\{\{church_name\}\}/g, 'Beulah Family')
        .replace(/\{\{event_name\}\}/g, 'Sunday Service')
        .replace(/\{\{event_date\}\}/g, 'December 25, 2024')
        .replace(/\{\{event_time\}\}/g, '9:00 AM')
        .replace(/\{\{age\}\}/g, '25');
    
    document.getElementById('messagePreview').textContent = preview;
});

function toggleStatus() {
    const templateId = {{ $template->id }};
    const currentStatus = {{ $template->is_active ? 'true' : 'false' }};
    
    fetch(`/sms/templates/${templateId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
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

function duplicateTemplate() {
    const templateId = {{ $template->id }};
    
    fetch(`/sms/templates/${templateId}/duplicate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `/sms/templates/${data.template.id}`;
        } else {
            alert('Failed to duplicate template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
@endsection
