@extends('components.app-layout')

@section('title', 'Edit Event')

@section('content')
@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="successToast">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="errorToast">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
@endif

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
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
                            <h1 class="text-4xl font-bold text-white">Edit Event</h1>
                            <p class="text-blue-100 text-lg">Update event information</p>
                        </div>
                    </div>
                    <a href="{{ route('events.show', $event) }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Event
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50">
            <div class="p-8">
                <form method="POST" action="{{ route('events.update', $event) }}">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Event Details</h3>
                                <p class="text-gray-600">Basic information about your event</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                       placeholder="Enter event title">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Type -->
                            <div>
                                <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">Event Type *</label>
                                <select id="event_type" name="event_type" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Event Type</option>
                                    <option value="service" {{ old('event_type', $event->event_type) == 'service' ? 'selected' : '' }}>Church Service</option>
                                    <option value="meeting" {{ old('event_type', $event->event_type) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                    <option value="conference" {{ old('event_type', $event->event_type) == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="workshop" {{ old('event_type', $event->event_type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="social" {{ old('event_type', $event->event_type) == 'social' ? 'selected' : '' }}>Social Event</option>
                                    <option value="outreach" {{ old('event_type', $event->event_type) == 'outreach' ? 'selected' : '' }}>Outreach</option>
                                    <option value="fundraising" {{ old('event_type', $event->event_type) == 'fundraising' ? 'selected' : '' }}>Fundraising</option>
                                    <option value="other" {{ old('event_type', $event->event_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select id="status" name="status" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="description" name="description" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Event description">{{ old('description', $event->description) }}</textarea>
                            </div>

                            <!-- Start Date & Time -->
                            <div>
                                <label for="start_datetime" class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time *</label>
                                <input type="datetime-local" id="start_datetime" name="start_datetime" 
                                       value="{{ old('start_datetime', $event->start_datetime ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- End Date & Time -->
                            <div>
                                <label for="end_datetime" class="block text-sm font-medium text-gray-700 mb-2">End Date & Time *</label>
                                <input type="datetime-local" id="end_datetime" name="end_datetime" 
                                       value="{{ old('end_datetime', $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Event venue or address">
                            </div>

                            <!-- Ministry -->
                            <div>
                                <label for="ministry_id" class="block text-sm font-medium text-gray-700 mb-2">Ministry</label>
                                <select id="ministry_id" name="ministry_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Ministry (Optional)</option>
                                    @foreach($ministries as $ministry)
                                        <option value="{{ $ministry->id }}" {{ old('ministry_id', $event->ministry_id) == $ministry->id ? 'selected' : '' }}>
                                            {{ $ministry->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Organizer -->
                            <div>
                                <label for="organizer_id" class="block text-sm font-medium text-gray-700 mb-2">Organizer</label>
                                <select id="organizer_id" name="organizer_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Organizer (Optional)</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('organizer_id', $event->organizer_id) == $member->id ? 'selected' : '' }}>
                                            {{ $member->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Max Attendees -->
                            <div>
                                <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">Max Attendees</label>
                                <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Leave empty for unlimited">
                            </div>

                            <!-- Registration Fee -->
                            <div>
                                <label for="registration_fee" class="block text-sm font-medium text-gray-700 mb-2">Registration Fee (₵)</label>
                                <input type="number" step="0.01" id="registration_fee" name="registration_fee" value="{{ old('registration_fee', $event->registration_fee) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0.00">
                            </div>

                            <!-- Special Instructions -->
                            <div class="md:col-span-2">
                                <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">Special Instructions</label>
                                <textarea id="special_instructions" name="special_instructions" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Any special instructions for attendees">{{ old('special_instructions', $event->special_instructions) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between border-t pt-6">
                        <a href="{{ route('events.show', $event) }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-colors">
                            Cancel
                        </a>
                        <div class="flex space-x-3">
                            <button type="submit" name="status" value="draft" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-save mr-2"></i>Save as Draft
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-medium transition-colors shadow-lg">
                                <i class="fas fa-check mr-2"></i>Update Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto hide success/error toasts after 5 seconds
setTimeout(function() {
    const successToast = document.getElementById('successToast');
    const errorToast = document.getElementById('errorToast');
    if (successToast) successToast.style.display = 'none';
    if (errorToast) errorToast.style.display = 'none';
}, 5000);
</script>
@endsection
