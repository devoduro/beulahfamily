@extends('components.public-layout')

@section('title', $announcement->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('announcements.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Announcements
            </a>
        </div>

        <!-- Announcement Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-8 py-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $announcement->type === 'urgent' ? 'bg-red-100 text-red-800' : ($announcement->type === 'event' ? 'bg-blue-100 text-blue-800' : 'bg-white/20 text-white') }}">
                                {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}
                            </span>
                            @if($announcement->priority === 'urgent' || $announcement->priority === 'high')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ ucfirst($announcement->priority) }} Priority
                                </span>
                            @endif
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $announcement->title }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                <span>By {{ $announcement->creator->name ?? 'Admin' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>{{ \Carbon\Carbon::parse($announcement->publish_date)->format('F j, Y') }}</span>
                            </div>
                            @if($announcement->expire_date && \Carbon\Carbon::parse($announcement->expire_date)->isFuture())
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Valid until {{ \Carbon\Carbon::parse($announcement->expire_date)->format('M j, Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('announcements.edit', $announcement) }}" class="ml-4 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Image -->
            @if($announcement->image_path)
                <div class="w-full h-64 bg-gray-100">
                    <img src="{{ asset('storage/' . $announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Content -->
            <div class="p-8">
                <div class="prose max-w-none">
                    {!! nl2br(e($announcement->content)) !!}
                </div>

                <!-- Target Audience -->
                @if($announcement->target_audience && is_array($announcement->target_audience) && count($announcement->target_audience) > 0)
                    <div class="mt-8 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                        <div class="flex items-center text-blue-800">
                            <i class="fas fa-users mr-2"></i>
                            <span class="font-medium">This announcement is for: </span>
                            <span class="ml-2">{{ implode(', ', array_map('ucfirst', $announcement->target_audience)) }}</span>
                        </div>
                    </div>
                @endif

                <!-- Attachment -->
                @if($announcement->attachment_path)
                    <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-paperclip text-gray-500 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Attachment Available</p>
                                    <p class="text-sm text-gray-600">Click to download</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $announcement->attachment_path) }}" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Meta Information -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                            <span>Category: <strong class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $announcement->type)) }}</strong></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-flag mr-2 text-gray-400"></i>
                            <span>Priority: <strong class="text-gray-900">{{ ucfirst($announcement->priority) }}</strong></span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-gray-400"></i>
                            <span>Published: <strong class="text-gray-900">{{ \Carbon\Carbon::parse($announcement->publish_date)->diffForHumans() }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Share Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">Share this announcement:</p>
                    <div class="flex items-center gap-3">
                        <button onclick="shareOnFacebook()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fab fa-facebook-f mr-2"></i>Facebook
                        </button>
                        <button onclick="shareOnTwitter()" class="inline-flex items-center px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition-colors">
                            <i class="fab fa-twitter mr-2"></i>Twitter
                        </button>
                        <button onclick="shareOnWhatsApp()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                        </button>
                        <button onclick="copyLink()" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-link mr-2"></i>Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const pageUrl = window.location.href;
const pageTitle = "{{ $announcement->title }}";

function shareOnFacebook() {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`, '_blank');
}

function shareOnTwitter() {
    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent(pageTitle)}`, '_blank');
}

function shareOnWhatsApp() {
    window.open(`https://wa.me/?text=${encodeURIComponent(pageTitle + ' ' + pageUrl)}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(pageUrl).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endsection
