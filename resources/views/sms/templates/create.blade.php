@extends('components.app-layout')

@section('title', 'Create SMS Template')
@section('subtitle', 'Create a new reusable SMS message template')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-file-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Create SMS Template</h1>
            <p class="text-gray-600 text-lg mt-2">Design a reusable message template with dynamic variables</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('message.templates.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-info-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Basic Information</h2>
                        <p class="text-gray-500 text-sm">Template name and description</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Template Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-800 mb-2">Template Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               placeholder="Enter template name" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-800 mb-2">Category <span class="text-red-500">*</span></label>
                        <select name="category" id="category" required 
                                class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select Category</option>
                            <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
                            <option value="birthday" {{ old('category') === 'birthday' ? 'selected' : '' }}>Birthday</option>
                            <option value="event" {{ old('category') === 'event' ? 'selected' : '' }}>Event</option>
                            <option value="announcement" {{ old('category') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="reminder" {{ old('category') === 'reminder' ? 'selected' : '' }}>Reminder</option>
                            <option value="welcome" {{ old('category') === 'welcome' ? 'selected' : '' }}>Welcome</option>
                            <option value="thank_you" {{ old('category') === 'thank_you' ? 'selected' : '' }}>Thank You</option>
                            <option value="invitation" {{ old('category') === 'invitation' ? 'selected' : '' }}>Invitation</option>
                            <option value="emergency" {{ old('category') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                            <option value="custom" {{ old('category') === 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none" 
                                  placeholder="Describe when and how this template should be used">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-comment-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Message Content</h2>
                        <p class="text-gray-500 text-sm">Write your SMS message with dynamic variables</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Message Text -->
                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-800 mb-2">Message Text <span class="text-red-500">*</span></label>
                        <textarea name="message" id="message" rows="6" required 
                                  class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none" 
                                  placeholder="Enter your message. Use {${'{'}variable_name${'}'}} for dynamic content">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Character Counter -->
                        <div class="flex justify-between items-center mt-2 text-sm">
                            <span class="text-gray-500">Use {${'{'}variable_name${'}'}} for dynamic content</span>
                            <div class="flex items-center space-x-4">
                                <span id="charCount" class="text-gray-600">0 characters</span>
                                <span id="smsCount" class="text-blue-600">1 SMS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Variables -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Quick Insert Variables</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button type="button" onclick="insertVariable('first_name')" class="px-3 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                                {${'{'}first_name${'}'}
                            </button>
                            <button type="button" onclick="insertVariable('last_name')" class="px-3 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                                {${'{'}last_name${'}'}
                            </button>
                            <button type="button" onclick="insertVariable('full_name')" class="px-3 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                                {${'{'}full_name${'}'}
                            </button>
                            <button type="button" onclick="insertVariable('church_name')" class="px-3 py-2 bg-blue-100 text-blue-800 rounded-lg text-sm hover:bg-blue-200 transition-colors">
                                {${'{'}church_name${'}'}
                            </button>
                            <button type="button" onclick="insertVariable('event_name')" class="px-3 py-2 bg-green-100 text-green-800 rounded-lg text-sm hover:bg-green-200 transition-colors">
                                {${'{'}event_name${'}'}
                            </button>
                            <button type="button" onclick="insertVariable('event_date')" class="px-3 py-2 bg-green-100 text-green-800 rounded-lg text-sm hover:bg-green-200 transition-colors">
                                {${'{'}event_date${'}'}
                            </button>
                            <button type="button" onclick="insertVariable('event_time')" class="px-3 py-2 bg-green-100 text-green-800 rounded-lg text-sm hover:bg-green-200 transition-colors">
                                {${'{'}event_time${'}'}
                            </button>
                            <button type="button" onclick="insertVariable('age')" class="px-3 py-2 bg-pink-100 text-pink-800 rounded-lg text-sm hover:bg-pink-200 transition-colors">
                                {${'{'}age${'}'}
                            </button>
                        </div>
                    </div>

                    <!-- Custom Variables -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-3">Custom Variables</label>
                        <div id="variablesContainer">
                            <div class="flex items-center space-x-2 mb-2">
                                <input type="text" name="variables[]" placeholder="Variable name (e.g., amount, location)" 
                                       class="flex-1 px-3 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" onclick="addVariable()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Add custom variables that can be used in your message template</p>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-eye text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Preview</h2>
                        <p class="text-gray-500 text-sm">See how your message will look</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-200">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-2xl p-4 shadow-sm">
                                <p id="messagePreview" class="text-gray-700">Your message preview will appear here...</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Sample SMS message</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('message.templates.index') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 border-2 border-gray-300 rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                    <i class="fas fa-save mr-3"></i>
                    Create Template
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const smsCount = document.getElementById('smsCount');
    const messagePreview = document.getElementById('messagePreview');

    function updateCounters() {
        const text = messageTextarea.value;
        const charLength = text.length;
        const smsLength = Math.ceil(charLength / 160) || 1;
        
        charCount.textContent = `${charLength} characters`;
        smsCount.textContent = `${smsLength} SMS`;
        
        // Update preview
        if (text.trim()) {
            // Replace variables with sample data for preview
            let preview = text
                .replace(/\{\{first_name\}\}/g, 'John')
                .replace(/\{\{last_name\}\}/g, 'Doe')
                .replace(/\{\{full_name\}\}/g, 'John Doe')
                .replace(/\{\{church_name\}\}/g, 'Beulah Family Church')
                .replace(/\{\{event_name\}\}/g, 'Sunday Service')
                .replace(/\{\{event_date\}\}/g, 'December 25, 2024')
                .replace(/\{\{event_time\}\}/g, '9:00 AM')
                .replace(/\{\{age\}\}/g, '25');
            
            messagePreview.textContent = preview;
        } else {
            messagePreview.textContent = 'Your message preview will appear here...';
        }
    }

    messageTextarea.addEventListener('input', updateCounters);
    
    // Initial update
    updateCounters();
});

function insertVariable(variableName) {
    const messageTextarea = document.getElementById('message');
    const cursorPos = messageTextarea.selectionStart;
    const textBefore = messageTextarea.value.substring(0, cursorPos);
    const textAfter = messageTextarea.value.substring(cursorPos);
    
    const variableText = `{${'{'}${variableName}${'}'}${'}'}`;
    messageTextarea.value = textBefore + variableText + textAfter;
    
    // Move cursor after inserted variable
    messageTextarea.selectionStart = messageTextarea.selectionEnd = cursorPos + variableText.length;
    messageTextarea.focus();
    
    // Update counters
    messageTextarea.dispatchEvent(new Event('input'));
}

function addVariable() {
    const container = document.getElementById('variablesContainer');
    const newVariableDiv = document.createElement('div');
    newVariableDiv.className = 'flex items-center space-x-2 mb-2';
    newVariableDiv.innerHTML = `
        <input type="text" name="variables[]" placeholder="Variable name (e.g., amount, location)" 
               class="flex-1 px-3 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="button" onclick="removeVariable(this)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
            <i class="fas fa-minus"></i>
        </button>
    `;
    container.appendChild(newVariableDiv);
}

function removeVariable(button) {
    button.parentElement.remove();
}
</script>
@endsection
