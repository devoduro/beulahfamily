@extends('components.app-layout')

@section('title', 'Create Announcement')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl shadow-2xl mb-8">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative px-8 py-12">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i class="fas fa-bullhorn text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-white">Create Announcement</h1>
                            <p class="text-blue-100 text-lg">Share important updates with your congregation</p>
                        </div>
                    </div>
                    <a href="{{ route('announcements.index') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50">
            <div class="p-8">
                <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Email Notification Info -->
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Email Notifications</h3>
                                <p class="mt-1 text-sm text-blue-700">
                                    When you check "Send email notification" and publish this announcement, emails will be automatically sent to:
                                </p>
                                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                                    <li>Active members who opted in to receive newsletters</li>
                                    <li>Members matching your selected target audience (if specified)</li>
                                    <li>Only members with valid email addresses</li>
                                </ul>
                                <p class="mt-2 text-xs text-blue-600">
                                    💡 Emails are sent in the background and won't delay the announcement creation.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Announcement Details</h3>
                                <p class="text-gray-600">Basic information about your announcement</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                       placeholder="Enter announcement title">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select id="type" name="type" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Event</option>
                                    <option value="prayer_request" {{ old('type') == 'prayer_request' ? 'selected' : '' }}>Prayer Request</option>
                                    <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="celebration" {{ old('type') == 'celebration' ? 'selected' : '' }}>Celebration</option>
                                    <option value="ministry" {{ old('type') == 'ministry' ? 'selected' : '' }}>Ministry</option>
                                </select>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                                <select id="priority" name="priority" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>

                            <!-- Content -->
                            <div class="md:col-span-2">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                                <textarea id="content" name="content" rows="6" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Enter announcement content">{{ old('content') }}</textarea>
                            </div>

                            <!-- Publish Date -->
                            <div>
                                <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-2">Publish Date *</label>
                                <input type="datetime-local" id="publish_date" name="publish_date" 
                                       value="{{ old('publish_date', now()->format('Y-m-d\TH:i')) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Expire Date -->
                            <div>
                                <label for="expire_date" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date (Optional)</label>
                                <input type="datetime-local" id="expire_date" name="expire_date" value="{{ old('expire_date') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image (Optional)</label>
                                <input type="file" id="image" name="image" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Max 2MB (JPEG, PNG, JPG, GIF)</p>
                            </div>

                            <!-- Attachment Upload -->
                            <div>
                                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Attachment (Optional)</label>
                                <input type="file" id="attachment" name="attachment"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Max 10MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Distribution Settings -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-share-alt text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Distribution Settings</h3>
                                <p class="text-gray-600">How should this announcement be shared?</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Display Options -->
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="display_on_website" value="1" {{ old('display_on_website', true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-gray-700">Display on website</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="display_on_screens" value="1" {{ old('display_on_screens') ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-gray-700">Display on church screens</span>
                                </label>
                            </div>

                            <!-- Notification Options -->
                            <div class="space-y-3">
                                <label class="flex items-start">
                                    <input type="checkbox" name="send_email" value="1" {{ old('send_email') ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-700">Send email notification</span>
                                        <p class="text-xs text-gray-500">Emails will be sent to members who opted in to receive newsletters</p>
                                    </div>
                                </label>
                                <label class="flex items-start">
                                    <input type="checkbox" name="send_sms" value="1" {{ old('send_sms') ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-700">Send SMS notification</span>
                                        <p class="text-xs text-gray-500">SMS will be sent to members who opted in (Coming soon)</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Target Audience -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience (Optional)</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="target_audience[]" value="all_members"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">All Members</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="target_audience[]" value="youth"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Youth</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="target_audience[]" value="adults"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Adults</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="target_audience[]" value="children"
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Children</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Internal Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Internal notes (not visible to members)">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between border-t pt-6">
                        <a href="{{ route('announcements.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">
                            Cancel
                        </a>
                        <div class="flex space-x-3">
                            <button type="submit" name="status" value="draft" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-save mr-2"></i>Save as Draft
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-medium transition-colors shadow-lg">
                                <i class="fas fa-bullhorn mr-2"></i>Publish Announcement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
