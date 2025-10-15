@extends('member.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Welcome Header with Glassmorphism -->
        <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl shadow-2xl mb-8 overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-4 -left-4 w-24 h-24 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
                <div class="absolute top-10 right-10 w-16 h-16 bg-white bg-opacity-5 rounded-full animate-bounce"></div>
                <div class="absolute bottom-4 left-1/3 w-20 h-20 bg-white bg-opacity-5 rounded-full animate-pulse delay-1000"></div>
            </div>
            
            <div class="relative px-8 py-8">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                    <div class="flex items-center mb-4 lg:mb-0">
                        <div class="relative">
                            <div class="w-20 h-20 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-6 shadow-lg border border-white border-opacity-20 overflow-hidden">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}?v={{ time() }}" alt="{{ $member->first_name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-white text-2xl"></i>
                                @endif
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 rounded-full border-2 border-white shadow-sm"></div>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ $member->first_name }}! 👋</h1>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20 text-white backdrop-blur-sm">
                                    <i class="fas fa-crown mr-2 text-yellow-300"></i>{{ ucfirst($member->membership_type) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20 text-white backdrop-blur-sm">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-300"></i>{{ $member->chapter }} Chapter
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-left lg:text-right">
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4 border border-white border-opacity-20">
                            <p class="text-blue-100 text-sm mb-1">Last login</p>
                            <p class="text-white font-semibold text-lg">{{ now()->format('M d, Y') }}</p>
                            <p class="text-blue-200 text-sm">{{ now()->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards - Real Data from Database -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Donations Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-hand-holding-heart text-green-600 text-2xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Total Donations</p>
                <p class="text-2xl font-bold text-gray-900">₵{{ number_format($stats['total_donations'], 2) }}</p>
            </div>

            <!-- This Year Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-calendar text-orange-600 text-2xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">{{ now()->year }} Donations</p>
                <p class="text-2xl font-bold text-gray-900">₵{{ number_format($stats['yearly_donations'], 2) }}</p>
            </div>

            <!-- Events Attended Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Events Attended</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['events_attended'] }}</p>
            </div>

            <!-- Active Ministries Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-users text-purple-600 text-2xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-1">Active Ministries</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active_ministries'] }}</p>
            </div>
        </div>

        <!-- Enhanced Main Content Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Upcoming Events with Modern Design -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Upcoming Events
                            </h2>
                            <a href="{{ route('member.events.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($upcomingEvents->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingEvents as $event)
                                <div class="group relative bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 border border-blue-100 hover:border-blue-200">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar text-blue-600 text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h3 class="font-semibold text-gray-900 mb-1">{{ $event->title }}</h3>
                                            <div class="text-sm text-gray-600">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($event->start_datetime)->format('M d, Y g:i A') }}
                                            </div>
                                            @if($event->location)
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $event->location }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="relative">
                                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                                        <i class="fas fa-calendar-alt text-blue-600 text-4xl"></i>
                                    </div>
                                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full animate-bounce"></div>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-3">No upcoming events</h3>
                                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Stay tuned! New exciting events and programs are coming soon.</p>
                                <a href="{{ route('member.events.index') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-search mr-2"></i>Browse All Events
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Donations - Real Data from Database -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-hand-holding-heart text-green-600 mr-2"></i>Recent Donations
                            </h2>
                            <a href="{{ route('member.donations.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentDonations->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentDonations as $donation)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-heart text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">₵{{ number_format($donation->amount, 2) }}</p>
                                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($donation->donation_date)->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-medium px-3 py-1 bg-green-100 text-green-700 rounded-full">
                                        {{ $donation->payment_method ?? 'Cash' }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-hand-holding-heart text-green-600 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Donations Yet</h3>
                                <p class="text-gray-500 mb-6">Start your giving journey today</p>
                                <a href="{{ route('member.donations.create') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Make a Donation
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>Quick Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('member.donations.create') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors border border-green-100">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-hand-holding-heart text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Make Donation</p>
                                <p class="text-xs text-gray-600">Support the church</p>
                            </div>
                            <i class="fas fa-arrow-right text-green-600"></i>
                        </a>

                        <a href="{{ route('member.profile') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-100">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user-edit text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Update Profile</p>
                                <p class="text-xs text-gray-600">Edit your information</p>
                            </div>
                            <i class="fas fa-arrow-right text-blue-600"></i>
                        </a>

                        <a href="{{ route('member.events.index') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors border border-purple-100">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">View Events</p>
                                <p class="text-xs text-gray-600">Browse upcoming activities</p>
                            </div>
                            <i class="fas fa-arrow-right text-purple-600"></i>
                        </a>
                    </div>
                </div>

                <!-- Member Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-id-card text-indigo-600 mr-2"></i>Member Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Member Since</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $member->membership_date ? $member->membership_date->format('M Y') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($member->membership_status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Chapter</span>
                            <span class="text-sm font-medium text-gray-900">{{ $member->chapter }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Member ID</span>
                            <span class="text-sm font-medium text-gray-900">{{ $member->member_id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
