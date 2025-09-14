@extends('components.app-layout')

@section('title', 'Create Ministry')
@section('subtitle', 'Add a new ministry to your church')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-hands-praying text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Create New Ministry</h1>
            <p class="text-gray-600 text-lg mt-2">Establish a new ministry to serve your church community</p>
        </div>

        <form action="{{ route('ministries.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-info-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Basic Information</h2>
                        <p class="text-gray-500 text-sm">Essential details about the ministry</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ministry Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-800 mb-2">Ministry Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               placeholder="Enter ministry name" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ministry Type -->
                    <div>
                        <label for="ministry_type" class="block text-sm font-semibold text-gray-800 mb-2">Ministry Type <span class="text-red-500">*</span></label>
                        <select name="ministry_type" id="ministry_type" required 
                                class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select Type</option>
                            <option value="worship" {{ old('ministry_type') === 'worship' ? 'selected' : '' }}>Worship</option>
                            <option value="youth" {{ old('ministry_type') === 'youth' ? 'selected' : '' }}>Youth</option>
                            <option value="children" {{ old('ministry_type') === 'children' ? 'selected' : '' }}>Children</option>
                            <option value="men" {{ old('ministry_type') === 'men' ? 'selected' : '' }}>Men</option>
                            <option value="women" {{ old('ministry_type') === 'women' ? 'selected' : '' }}>Women</option>
                            <option value="seniors" {{ old('ministry_type') === 'seniors' ? 'selected' : '' }}>Seniors</option>
                            <option value="outreach" {{ old('ministry_type') === 'outreach' ? 'selected' : '' }}>Outreach</option>
                            <option value="education" {{ old('ministry_type') === 'education' ? 'selected' : '' }}>Education</option>
                            <option value="administration" {{ old('ministry_type') === 'administration' ? 'selected' : '' }}>Administration</option>
                            <option value="other" {{ old('ministry_type') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('ministry_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Leader -->
                    <div>
                        <label for="leader_id" class="block text-sm font-semibold text-gray-800 mb-2">Ministry Leader</label>
                        <select name="leader_id" id="leader_id" 
                                class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select Leader (Optional)</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('leader_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->first_name }} {{ $member->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('leader_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none" 
                                  placeholder="Describe the ministry's purpose and activities">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Meeting Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Meeting Information</h2>
                        <p class="text-gray-500 text-sm">When and where the ministry meets</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Meeting Day -->
                    <div>
                        <label for="meeting_day" class="block text-sm font-semibold text-gray-800 mb-2">Meeting Day</label>
                        <select name="meeting_day" id="meeting_day" 
                                class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select Day</option>
                            <option value="Sunday" {{ old('meeting_day') === 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            <option value="Monday" {{ old('meeting_day') === 'Monday' ? 'selected' : '' }}>Monday</option>
                            <option value="Tuesday" {{ old('meeting_day') === 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                            <option value="Wednesday" {{ old('meeting_day') === 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                            <option value="Thursday" {{ old('meeting_day') === 'Thursday' ? 'selected' : '' }}>Thursday</option>
                            <option value="Friday" {{ old('meeting_day') === 'Friday' ? 'selected' : '' }}>Friday</option>
                            <option value="Saturday" {{ old('meeting_day') === 'Saturday' ? 'selected' : '' }}>Saturday</option>
                        </select>
                        @error('meeting_day')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meeting Time -->
                    <div>
                        <label for="meeting_time" class="block text-sm font-semibold text-gray-800 mb-2">Meeting Time</label>
                        <input type="time" name="meeting_time" id="meeting_time" 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               value="{{ old('meeting_time') }}">
                        @error('meeting_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Budget -->
                    <div>
                        <label for="budget" class="block text-sm font-semibold text-gray-800 mb-2">Budget (GHS)</label>
                        <input type="number" name="budget" id="budget" min="0" step="0.01" 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               placeholder="0.00" value="{{ old('budget') }}">
                        @error('budget')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meeting Location -->
                    <div class="md:col-span-3">
                        <label for="meeting_location" class="block text-sm font-semibold text-gray-800 mb-2">Meeting Location</label>
                        <input type="text" name="meeting_location" id="meeting_location" 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               placeholder="e.g., Main Sanctuary, Fellowship Hall, Room 201" value="{{ old('meeting_location') }}">
                        @error('meeting_location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Target Demographics -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Target Demographics</h2>
                        <p class="text-gray-500 text-sm">Who this ministry is designed for</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Target Gender -->
                    <div>
                        <label for="target_gender" class="block text-sm font-semibold text-gray-800 mb-2">Target Gender <span class="text-red-500">*</span></label>
                        <select name="target_gender" id="target_gender" required 
                                class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="all" {{ old('target_gender') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="male" {{ old('target_gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('target_gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('target_gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Minimum Age -->
                    <div>
                        <label for="target_age_min" class="block text-sm font-semibold text-gray-800 mb-2">Minimum Age</label>
                        <input type="number" name="target_age_min" id="target_age_min" min="0" max="120" 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               placeholder="e.g., 18" value="{{ old('target_age_min') }}">
                        @error('target_age_min')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Maximum Age -->
                    <div>
                        <label for="target_age_max" class="block text-sm font-semibold text-gray-800 mb-2">Maximum Age</label>
                        <input type="number" name="target_age_max" id="target_age_max" min="0" max="120" 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               placeholder="e.g., 65" value="{{ old('target_age_max') }}">
                        @error('target_age_max')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-clipboard-list text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Additional Information</h2>
                        <p class="text-gray-500 text-sm">Requirements, goals, and other details</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Requirements -->
                    <div>
                        <label for="requirements" class="block text-sm font-semibold text-gray-800 mb-2">Requirements</label>
                        <textarea name="requirements" id="requirements" rows="3" 
                                  class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none" 
                                  placeholder="Any specific requirements for joining this ministry">{{ old('requirements') }}</textarea>
                        @error('requirements')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Goals -->
                    <div>
                        <label for="goals" class="block text-sm font-semibold text-gray-800 mb-2">Goals & Objectives</label>
                        <textarea name="goals" id="goals" rows="3" 
                                  class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none" 
                                  placeholder="What does this ministry aim to achieve?">{{ old('goals') }}</textarea>
                        @error('goals')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('ministries.index') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 border-2 border-gray-300 rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white font-bold rounded-2xl hover:from-purple-700 hover:via-indigo-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                    <i class="fas fa-plus mr-3"></i>
                    Create Ministry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Age validation
    const minAgeInput = document.getElementById('target_age_min');
    const maxAgeInput = document.getElementById('target_age_max');
    
    function validateAges() {
        const minAge = parseInt(minAgeInput.value) || 0;
        const maxAge = parseInt(maxAgeInput.value) || 0;
        
        if (minAge && maxAge && minAge > maxAge) {
            maxAgeInput.setCustomValidity('Maximum age must be greater than or equal to minimum age');
        } else {
            maxAgeInput.setCustomValidity('');
        }
    }
    
    minAgeInput.addEventListener('input', validateAges);
    maxAgeInput.addEventListener('input', validateAges);
});
</script>
@endsection
