@extends('components.app-layout')

@section('title', 'Edit Announcement')

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
                            <i class="fas fa-edit text-3xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-white">Edit Announcement</h1>
                            <p class="text-blue-100 text-lg">Update announcement details</p>
                        </div>
                    </div>
                    <a href="{{ route('announcements.show', $announcement) }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50">
            <div class="p-8">
                <form method="POST" action="{{ route('announcements.update', $announcement) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter announcement title">
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select id="type" name="type" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="general" {{ old('type', $announcement->type) == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="event" {{ old('type', $announcement->type) == 'event' ? 'selected' : '' }}>Event</option>
                                    <option value="prayer_request" {{ old('type', $announcement->type) == 'prayer_request' ? 'selected' : '' }}>Prayer Request</option>
                                    <option value="urgent" {{ old('type', $announcement->type) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    <option value="celebration" {{ old('type', $announcement->type) == 'celebration' ? 'selected' : '' }}>Celebration</option>
                                    <option value="ministry" {{ old('type', $announcement->type) == 'ministry' ? 'selected' : '' }}>Ministry</option>
                                </select>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                                <select id="priority" name="priority" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', $announcement->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>

                            <!-- Content -->
                            <div class="md:col-span-2">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                                <textarea id="content" name="content" rows="6" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Enter announcement content">{{ old('content', $announcement->content) }}</textarea>
                            </div>

                            <!-- Publish Date -->
                            <div>
                                <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-2">Publish Date *</label>
                                <input type="datetime-local" id="publish_date" name="publish_date" 
                                       value="{{ old('publish_date', \Carbon\Carbon::parse($announcement->publish_date)->format('Y-m-d\TH:i')) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Expire Date -->
                            <div>
                                <label for="expire_date" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date (Optional)</label>
                                <input type="datetime-local" id="expire_date" name="expire_date" 
                                       value="{{ old('expire_date', $announcement->expire_date ? \Carbon\Carbon::parse($announcement->expire_date)->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Status -->
                            <div class="md:col-span-2">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select id="status" name="status" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="draft" {{ old('status', $announcement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $announcement->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $announcement->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
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
                                    <input type="checkbox" name="display_on_website" value="1" {{ old('display_on_website', $announcement->display_on_website) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-gray-700">Display on website</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="display_on_screens" value="1" {{ old('display_on_screens', $announcement->display_on_screens) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-gray-700">Display on church screens</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Internal Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Internal notes (not visible to members)">{{ old('notes', $announcement->notes) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between border-t pt-6">
                        <a href="{{ route('announcements.show', $announcement) }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-medium transition-colors shadow-lg">
                            <i class="fas fa-save mr-2"></i>Update Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
