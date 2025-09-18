@extends('member.layouts.app')

@section('title', 'Submit Prayer Request')
@section('subtitle', 'Let our church family pray with you')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-praying-hands text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-purple-700 to-indigo-700 bg-clip-text text-transparent mb-2">
                Submit Prayer Request
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                "Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God." - Philippians 4:6
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-hands text-white text-xl"></i>
                    <h2 class="text-2xl font-bold text-white">Share Your Prayer Need</h2>
                </div>
                <p class="text-purple-100 mt-2">Our church family is here to pray with you. Share your request below.</p>
            </div>

            <form action="{{ route('member.prayer-requests.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Title -->
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-bold text-gray-800">
                        Prayer Request Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md @error('title') border-red-300 @enderror" 
                           placeholder="Give your prayer request a brief title..."
                           required>
                    @error('title')
                        <p class="text-red-600 text-sm flex items-center mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Category and Urgency Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div class="space-y-2">
                        <label for="category" class="block text-sm font-bold text-gray-800">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" 
                                name="category" 
                                class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md @error('category') border-red-300 @enderror appearance-none"
                                required>
                            <option value="">Select a category...</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-red-600 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Urgency -->
                    <div class="space-y-2">
                        <label for="urgency" class="block text-sm font-bold text-gray-800">
                            Urgency Level <span class="text-red-500">*</span>
                        </label>
                        <select id="urgency" 
                                name="urgency" 
                                class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md @error('urgency') border-red-300 @enderror appearance-none"
                                required>
                            <option value="">Select urgency...</option>
                            @foreach($urgencyLevels as $key => $label)
                                <option value="{{ $key }}" {{ old('urgency', 'medium') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('urgency')
                            <p class="text-red-600 text-sm flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-bold text-gray-800">
                        Prayer Request Details <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <textarea id="description" 
                                  name="description" 
                                  rows="8" 
                                  class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none @error('description') border-red-300 @enderror" 
                                  placeholder="Please share the details of your prayer request. Be as specific as you feel comfortable sharing so our church family can pray effectively for your situation."
                                  required>{{ old('description') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400" id="char-count">
                            <span id="current-count">0</span> / 20 minimum
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Minimum 20 characters required. Share enough details for meaningful prayer.
                    </p>
                    @error('description')
                        <p class="text-red-600 text-sm flex items-center mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Expiration Date -->
                <div class="space-y-2">
                    <label for="expires_at" class="block text-sm font-bold text-gray-800">
                        Prayer Duration <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <input type="date" 
                           id="expires_at" 
                           name="expires_at" 
                           value="{{ old('expires_at') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Set an end date for this prayer request (leave blank for ongoing prayer).
                    </p>
                </div>

                <!-- Privacy Settings -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200/50">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                        Privacy Settings
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" 
                                   id="is_anonymous" 
                                   name="is_anonymous" 
                                   value="1" 
                                   {{ old('is_anonymous') ? 'checked' : '' }}
                                   class="mt-1 w-4 h-4 text-purple-600 bg-white border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                            <div>
                                <label for="is_anonymous" class="text-sm font-semibold text-gray-800">Submit anonymously</label>
                                <p class="text-xs text-gray-600 mt-1">Your name will not be shown with this prayer request.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" 
                                   id="is_private" 
                                   name="is_private" 
                                   value="1" 
                                   {{ old('is_private') ? 'checked' : '' }}
                                   class="mt-1 w-4 h-4 text-purple-600 bg-white border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                            <div>
                                <label for="is_private" class="text-sm font-semibold text-gray-800">Keep this request private</label>
                                <p class="text-xs text-gray-600 mt-1">Only you and church leadership will see this request.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Encouragement Section -->
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200/50">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-heart text-white text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-purple-800 mb-2">You Are Not Alone</h4>
                            <p class="text-sm text-purple-700 leading-relaxed">
                                Our church family is here to support you in prayer. Remember that God hears every prayer and cares deeply about your needs. "Cast all your anxiety on him because he cares for you." - 1 Peter 5:7
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200/50">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white font-bold rounded-xl hover:from-purple-700 hover:via-indigo-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                        <i class="fas fa-praying-hands mr-3"></i>
                        Submit Prayer Request
                        <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
                    </button>
                    <a href="{{ route('member.prayer-requests.index') }}" class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-left mr-3"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    const descriptionTextarea = document.getElementById('description');
    const currentCount = document.getElementById('current-count');
    
    function updateCharCount() {
        const count = descriptionTextarea.value.length;
        currentCount.textContent = count;
        
        if (count < 20) {
            currentCount.parentElement.className = 'absolute bottom-3 right-3 text-xs text-red-400';
        } else {
            currentCount.parentElement.className = 'absolute bottom-3 right-3 text-xs text-green-400';
        }
    }
    
    descriptionTextarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
    
    // Auto-resize textarea
    descriptionTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 300) + 'px';
    });
    
    // Form validation enhancement
    const form = descriptionTextarea.closest('form');
    form.addEventListener('submit', function(e) {
        const description = descriptionTextarea.value.trim();
        if (description.length < 20) {
            e.preventDefault();
            descriptionTextarea.focus();
            alert('Please provide at least 20 characters for your prayer request details.');
        }
    });
    
    // Privacy settings interaction
    const isPrivate = document.getElementById('is_private');
    const isAnonymous = document.getElementById('is_anonymous');
    
    isPrivate.addEventListener('change', function() {
        if (this.checked) {
            // If private, suggest anonymous as well
            if (!isAnonymous.checked) {
                setTimeout(() => {
                    if (confirm('Since this is a private request, would you also like to make it anonymous?')) {
                        isAnonymous.checked = true;
                    }
                }, 100);
            }
        }
    });
});
</script>
@endsection
