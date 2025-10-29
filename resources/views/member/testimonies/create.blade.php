@extends('member.layouts.app')

@section('title', 'Share Your Testimony')
@section('subtitle', 'Share how God has worked in your life')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-heart text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-amber-700 to-orange-700 bg-clip-text text-transparent mb-2">
                Share Your Testimony
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Your story matters! Share how God has worked in your life to encourage and inspire others in their faith journey.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 p-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-edit text-white text-xl"></i>
                    <h2 class="text-2xl font-bold text-white">Tell Your Story</h2>
                </div>
                <p class="text-amber-100 mt-2">Fill out the form below to share your testimony with the Beulah family.</p>
            </div>

            <form action="{{ route('member.testimonies.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Title -->
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-bold text-gray-800">
                        Testimony Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md @error('title') border-red-300 @enderror" 
                           placeholder="Give your testimony a meaningful title..."
                           required>
                    @error('title')
                        <p class="text-red-600 text-sm flex items-center mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label for="category" class="block text-sm font-bold text-gray-800">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md @error('category') border-red-300 @enderror appearance-none"
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

                <!-- Content -->
                <div class="space-y-2">
                    <label for="content" class="block text-sm font-bold text-gray-800">
                        Your Testimony <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <textarea id="content" 
                                  name="content" 
                                  rows="12" 
                                  class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none @error('content') border-red-300 @enderror" 
                                  placeholder="Share your testimony here... How has God worked in your life? What challenges did you face? How did God show up? Be specific and encourage others with your story."
                                  required>{{ old('content') }}</textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400" id="char-count">
                            <span id="current-count">0</span> / 50 minimum
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Minimum 50 characters required. Share your story with details that will encourage others.
                    </p>
                    @error('content')
                        <p class="text-red-600 text-sm flex items-center mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Tags -->
                <div class="space-y-2">
                    <label for="tags" class="block text-sm font-bold text-gray-800">
                        Tags <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <input type="text" 
                           id="tags" 
                           name="tags" 
                           value="{{ old('tags') }}"
                           class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                           placeholder="miracle, healing, breakthrough, faith, prayer (separate with commas)">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-tag mr-1"></i>
                        Add relevant tags separated by commas to help others find your testimony.
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
                                   id="is_public" 
                                   name="is_public" 
                                   value="1" 
                                   {{ old('is_public', true) ? 'checked' : '' }}
                                   class="mt-1 w-4 h-4 text-amber-600 bg-white border-2 border-gray-300 rounded focus:ring-amber-500 focus:ring-2">
                            <div>
                                <label for="is_public" class="text-sm font-semibold text-gray-800">Make this testimony public</label>
                                <p class="text-xs text-gray-600 mt-1">Allow all church members to read and be encouraged by your testimony.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-200/50">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-info text-white text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-amber-800 mb-2">Review Process</h4>
                            <p class="text-sm text-amber-700 leading-relaxed">
                                Your testimony will be reviewed by church leadership before being published. This ensures all content is appropriate and encouraging for Beulah family. You'll be notified once it's approved.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200/50">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 text-white font-bold rounded-xl hover:from-amber-600 hover:via-orange-600 hover:to-red-600 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                        <i class="fas fa-heart mr-3"></i>
                        Share My Testimony
                        <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
                    </button>
                    <a href="{{ route('member.testimonies.index') }}" class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-bold rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300 shadow-lg hover:shadow-xl">
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
    // Character counter for content
    const contentTextarea = document.getElementById('content');
    const currentCount = document.getElementById('current-count');
    
    function updateCharCount() {
        const count = contentTextarea.value.length;
        currentCount.textContent = count;
        
        if (count < 50) {
            currentCount.parentElement.className = 'absolute bottom-3 right-3 text-xs text-red-400';
        } else {
            currentCount.parentElement.className = 'absolute bottom-3 right-3 text-xs text-green-400';
        }
    }
    
    contentTextarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
    
    // Auto-resize textarea
    contentTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 400) + 'px';
    });
    
    // Form validation enhancement
    const form = contentTextarea.closest('form');
    form.addEventListener('submit', function(e) {
        const content = contentTextarea.value.trim();
        if (content.length < 50) {
            e.preventDefault();
            contentTextarea.focus();
            alert('Please write at least 50 characters for your testimony.');
        }
    });
});
</script>
@endsection
