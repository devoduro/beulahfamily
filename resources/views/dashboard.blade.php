@extends('components.app-layout')

@section('title', 'Church Dashboard')
@section('subtitle', 'Welcome to Beulah Family Church Management System')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden church-gradient rounded-3xl shadow-2xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0">
            <div class="absolute top-10 right-10 w-32 h-32 bg-white opacity-5 rounded-full"></div>
            <div class="absolute bottom-10 left-10 w-20 h-20 bg-white opacity-10 rounded-full"></div>
            <div class="absolute top-1/2 right-1/4 w-16 h-16 bg-white opacity-5 rounded-full"></div>
        </div>
        <div class="relative px-8 py-12">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                <div class="text-white">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-church text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold">Welcome to Beulah Family</h1>
                            <p class="text-lg opacity-90">Church Management System</p>
                        </div>
                    </div>
                    <p class="text-xl opacity-90 mb-8 max-w-2xl">Empowering our congregation through digital ministry management and community connection</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="glass-effect rounded-xl px-4 py-3 text-center">
                            <div class="text-2xl font-bold text-white">{{ $churchStats['total_members'] ?? 0 }}</div>
                            <div class="text-sm opacity-90">Members</div>
                        </div>
                        <div class="glass-effect rounded-xl px-4 py-3 text-center">
                            <div class="text-2xl font-bold text-white">{{ $churchStats['total_families'] ?? 0 }}</div>
                            <div class="text-sm opacity-90">Families</div>
                        </div>
                        <div class="glass-effect rounded-xl px-4 py-3 text-center">
                            <div class="text-2xl font-bold text-white">{{ $churchStats['upcoming_events'] ?? 0 }}</div>
                            <div class="text-sm opacity-90">Events</div>
                        </div>
                        <div class="glass-effect rounded-xl px-4 py-3 text-center">
                            <div class="text-2xl font-bold text-white">${{ number_format($churchStats['total_donations_this_year'] ?? 0) }}</div>
                            <div class="text-sm opacity-90">Donations</div>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block mt-8 lg:mt-0">
                    <div class="w-40 h-40 glass-effect rounded-full flex items-center justify-center">
                        <i class="fas fa-hands-praying text-6xl text-white opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Church Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Members -->
        <div class="card-hover bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Members</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $churchStats['active_members'] ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-blue-600 font-medium">{{ $churchStats['total_members'] ?? 0 }} total</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Total Families -->
        <div class="card-hover bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Church Families</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $churchStats['total_families'] ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-600 font-medium">Growing community</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-home text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Active Ministries -->
        <div class="card-hover bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Ministries</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $churchStats['total_ministries'] ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-purple-600 font-medium">Serving together</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hands-praying text-white text-lg"></i>
                </div>
            </div>
        </div>

        <!-- This Year's Donations -->
        <div class="card-hover bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Donations {{ date('Y') }}</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($churchStats['total_donations_this_year'] ?? 0) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-yellow-600 font-medium">Faithful giving</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hand-holding-heart text-white text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Church Activities -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Recent Church Activities</h3>
                    </div>
                    <span class="text-sm text-gray-500">Latest updates</span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-80 overflow-y-auto">
                    @if(isset($recentMembers) && $recentMembers->count() > 0)
                        @foreach($recentMembers as $member)
                            <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-blue-50 transition-colors border border-transparent hover:border-blue-200">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                                        <i class="fas fa-user-plus text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">New member joined</p>
                                    <p class="text-sm text-gray-600">{{ $member->full_name }} from {{ $member->family->family_name ?? 'Unknown' }} family</p>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="text-xs text-blue-600 font-medium">Member ID: {{ $member->member_id }}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">{{ $member->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    @if(isset($recentDonations) && $recentDonations->count() > 0)
                        @foreach($recentDonations->take(3) as $donation)
                            <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-green-50 transition-colors border border-transparent hover:border-green-200">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                        <i class="fas fa-hand-holding-heart text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">New donation received</p>
                                    <p class="text-sm text-gray-600">${{ number_format($donation->amount, 2) }} - {{ ucfirst($donation->type) }}</p>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="text-xs text-green-600 font-medium">{{ $donation->member->full_name ?? 'Anonymous' }}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">{{ $donation->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    @if((!isset($recentMembers) || $recentMembers->count() == 0) && (!isset($recentDonations) || $recentDonations->count() == 0))
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-church text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No recent church activities</p>
                            <p class="text-sm text-gray-400 mt-1">Activities will appear here as they happen</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Church Quick Actions & Upcoming Events -->
        <div class="space-y-6">
            <!-- Quick Church Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('members.create') }}" class="flex items-center p-4 rounded-xl hover:bg-blue-50 transition-all duration-200 group border border-transparent hover:border-blue-200">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Add New Member</p>
                            <p class="text-xs text-gray-500">Register new church member</p>
                        </div>
                    </a>
                    <a href="{{ route('events.create') }}" class="flex items-center p-4 rounded-xl hover:bg-orange-50 transition-all duration-200 group border border-transparent hover:border-orange-200">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-calendar-plus text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Create Event</p>
                            <p class="text-xs text-gray-500">Schedule church activities</p>
                        </div>
                    </a>
                    <a href="{{ route('donations.create') }}" class="flex items-center p-4 rounded-xl hover:bg-green-50 transition-all duration-200 group border border-transparent hover:border-green-200">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-hand-holding-heart text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Record Donation</p>
                            <p class="text-xs text-gray-500">Track tithes and offerings</p>
                        </div>
                    </a>
                    <a href="{{ route('announcements.create') }}" class="flex items-center p-4 rounded-xl hover:bg-purple-50 transition-all duration-200 group border border-transparent hover:border-purple-200">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-bullhorn text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">New Announcement</p>
                            <p class="text-xs text-gray-500">Share church updates</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                        </div>
                        <a href="{{ route('events.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View all</a>
                    </div>
                </div>
                <div class="p-6">
                    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingEvents as $event)
                                <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-orange-50 transition-colors border border-transparent hover:border-orange-200">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-calendar text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $event->title }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $event->ministry->name ?? 'General' }}</p>
                                        <div class="flex items-center mt-2 space-x-2">
                                            <span class="text-xs text-orange-600 font-medium">{{ $event->start_datetime->format('M j, Y') }}</span>
                                            <span class="text-xs text-gray-400">•</span>
                                            <span class="text-xs text-gray-500">{{ $event->start_datetime->format('g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-alt text-2xl text-orange-400"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No upcoming events</p>
                            <p class="text-sm text-gray-400 mt-1">Schedule your next church event</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Church Analytics Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Membership Growth Chart -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Membership Growth</h3>
                        <p class="text-sm text-gray-500">{{ date('Y') }} Overview</p>
                    </div>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-area text-white text-xl"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Membership Analytics</p>
                    <p class="text-sm text-gray-500 mt-1">Chart visualization coming soon</p>
                </div>
            </div>
        </div>

        <!-- Donation Trends -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Donation Trends</h3>
                        <p class="text-sm text-gray-500">Monthly Overview</p>
                    </div>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-pie text-white text-xl"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Financial Analytics</p>
                    <p class="text-sm text-gray-500 mt-1">Chart visualization coming soon</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Demographics -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Congregation Demographics</h3>
                    <p class="text-sm text-gray-500">Age distribution overview</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center p-6 rounded-xl bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-100">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-child text-white"></i>
                </div>
                <div class="text-2xl font-bold text-blue-600">{{ $ageDemographics['children'] ?? 0 }}</div>
                <div class="text-sm text-blue-600 font-medium">Children</div>
                <div class="text-xs text-gray-500 mt-1">Ages 0-12</div>
            </div>
            <div class="text-center p-6 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-running text-white"></i>
                </div>
                <div class="text-2xl font-bold text-green-600">{{ $ageDemographics['youth'] ?? 0 }}</div>
                <div class="text-sm text-green-600 font-medium">Youth</div>
                <div class="text-xs text-gray-500 mt-1">Ages 13-25</div>
            </div>
            <div class="text-center p-6 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-100">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="text-2xl font-bold text-purple-600">{{ $ageDemographics['adults'] ?? 0 }}</div>
                <div class="text-sm text-purple-600 font-medium">Adults</div>
                <div class="text-xs text-gray-500 mt-1">Ages 26-59</div>
            </div>
            <div class="text-center p-6 rounded-xl bg-gradient-to-br from-orange-50 to-red-50 border border-orange-100">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-tie text-white"></i>
                </div>
                <div class="text-2xl font-bold text-orange-600">{{ $ageDemographics['seniors'] ?? 0 }}</div>
                <div class="text-sm text-orange-600 font-medium">Seniors</div>
                <div class="text-xs text-gray-500 mt-1">Ages 60+</div>
            </div>
        </div>
    </div>
</div>
@endsection



 