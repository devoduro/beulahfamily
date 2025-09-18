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
                            <div class="w-20 h-20 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-6 shadow-lg border border-white border-opacity-20">
                                @if($member->photo_path)
                                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->first_name }}" class="w-16 h-16 rounded-xl object-cover">
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
                                    <i class="fas fa-crown mr-2 text-yellow-300"></i>{{ ucfirst($member->membership_type) }} Member
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

        <!-- Enhanced Quick Stats with Modern Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Donations Card -->
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-200 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full -mr-10 -mt-10 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl shadow-sm">
                            <i class="fas fa-hand-holding-heart text-green-600 text-xl"></i>
                        </div>
                        <div class="text-green-500 text-sm font-medium">+12.5%</div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Donations</p>
                        <p class="text-3xl font-bold text-gray-900 mb-1">₵{{ number_format($stats['total_donations'], 2) }}</p>
                        <p class="text-xs text-gray-400">All time giving</p>
                    </div>
                </div>
            </div>

            <!-- Events Attended Card -->
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full -mr-10 -mt-10 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl shadow-sm">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="text-blue-500 text-sm font-medium">+3 this month</div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Events Attended</p>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['events_attended'] }}</p>
                        <p class="text-xs text-gray-400">Total participation</p>
                    </div>
                </div>
            </div>

            <!-- Active Ministries Card -->
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-purple-200 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full -mr-10 -mt-10 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl shadow-sm">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div class="text-purple-500 text-sm font-medium">Active</div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Active Ministries</p>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_ministries'] }}</p>
                        <p class="text-xs text-gray-400">Current involvement</p>
                    </div>
                </div>
            </div>

            <!-- Yearly Donations Card -->
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-orange-200 overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-orange-400 to-red-500 rounded-full -mr-10 -mt-10 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-orange-100 to-red-100 rounded-xl shadow-sm">
                            <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                        </div>
                        <div class="text-orange-500 text-sm font-medium">{{ now()->year }}</div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">This Year</p>
                        <p class="text-3xl font-bold text-gray-900 mb-1">₵{{ number_format($stats['yearly_donations'], 2) }}</p>
                        <p class="text-xs text-gray-400">Year to date giving</p>
                    </div>
                </div>
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
                                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                                <i class="fas fa-calendar text-white text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="ml-5 flex-1">
                                            <h3 class="font-semibold text-gray-900 text-lg mb-1">{{ $event->title }}</h3>
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                <span class="flex items-center">
                                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                                    {{ $event->start_datetime ? $event->start_datetime->format('M d, Y g:i A') : 'Date TBD' }}
                                                </span>
                                                @if($event->location)
                                                <span class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                                    {{ $event->location }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-blue-600 hover:text-blue-800 hover:shadow-lg transition-all duration-200 group-hover:scale-110">
                                                <i class="fas fa-arrow-right"></i>
                                            </button>
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

                <!-- Interactive Statistics & Charts -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-chart-bar text-purple-600 mr-2"></i>My Activity Overview
                            </h2>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition-colors">
                                    This Month
                                </button>
                                <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-full transition-colors">
                                    This Year
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Donation Trends Chart -->
                            <div class="relative">
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Donation Trends</h3>
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                                    <div class="flex items-end justify-between h-32 space-x-2">
                                        <div class="flex flex-col items-center space-y-2">
                                            <div class="w-6 bg-gradient-to-t from-green-500 to-green-400 rounded-t" style="height: 60%"></div>
                                            <span class="text-xs text-gray-500">Jan</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div class="w-6 bg-gradient-to-t from-green-500 to-green-400 rounded-t" style="height: 80%"></div>
                                            <span class="text-xs text-gray-500">Feb</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div class="w-6 bg-gradient-to-t from-green-500 to-green-400 rounded-t" style="height: 45%"></div>
                                            <span class="text-xs text-gray-500">Mar</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div class="w-6 bg-gradient-to-t from-green-500 to-green-400 rounded-t" style="height: 90%"></div>
                                            <span class="text-xs text-gray-500">Apr</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div class="w-6 bg-gradient-to-t from-green-500 to-green-400 rounded-t" style="height: 70%"></div>
                                            <span class="text-xs text-gray-500">May</span>
                                        </div>
                                        <div class="flex flex-col items-center space-y-2">
                                            <div class="w-6 bg-gradient-to-t from-green-600 to-green-500 rounded-t shadow-lg" style="height: 100%"></div>
                                            <span class="text-xs text-green-600 font-medium">Jun</span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Monthly Average</span>
                                        <span class="font-semibold text-green-600">₵{{ number_format($stats['yearly_donations'] / 12, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Participation Chart -->
                            <div class="relative">
                                <h3 class="text-sm font-medium text-gray-700 mb-4">Event Participation</h3>
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                    <div class="relative w-32 h-32 mx-auto">
                                        <!-- Circular Progress -->
                                        <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 120 120">
                                            <circle cx="60" cy="60" r="50" stroke="#e5e7eb" stroke-width="8" fill="none"></circle>
                                            <circle cx="60" cy="60" r="50" stroke="url(#blueGradient)" stroke-width="8" fill="none" 
                                                    stroke-linecap="round" stroke-dasharray="314" stroke-dashoffset="94.2" class="transition-all duration-1000 ease-out"></circle>
                                            <defs>
                                                <linearGradient id="blueGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                                    <stop offset="100%" style="stop-color:#6366f1;stop-opacity:1" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-gray-900">70%</div>
                                                <div class="text-xs text-gray-500">Attended</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="flex items-center text-gray-600">
                                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                                Attended
                                            </span>
                                            <span class="font-medium">{{ $stats['events_attended'] }} events</span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="flex items-center text-gray-600">
                                                <div class="w-3 h-3 bg-gray-300 rounded-full mr-2"></div>
                                                Missed
                                            </span>
                                            <span class="font-medium">{{ max(0, 10 - $stats['events_attended']) }} events</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ministry Involvement Progress -->
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-4">Ministry Involvement</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Choir Ministry</span>
                                    <span class="text-sm font-medium text-purple-600">Active</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-1000 ease-out" style="width: 85%"></div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-4">
                                    <span class="text-sm text-gray-600">Youth Ministry</span>
                                    <span class="text-sm font-medium text-blue-600">Active</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-1000 ease-out" style="width: 65%"></div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-4">
                                    <span class="text-sm text-gray-600">Ushering Team</span>
                                    <span class="text-sm font-medium text-green-600">Volunteer</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-1000 ease-out" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Programs -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-graduation-cap text-purple-600 mr-2"></i>Active Programs
                            </h2>
                            <a href="{{ route('programs.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($activePrograms->count() > 0)
                            <div class="space-y-4">
                                @foreach($activePrograms as $program)
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-users text-purple-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $program->name }}</h3>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-calendar mr-1"></i>{{ $program->date_range }}
                                        </p>
                                        @if($program->registration_fee > 0)
                                        <p class="text-sm text-green-600 font-medium">
                                            ₵{{ number_format($program->registration_fee, 2) }}
                                        </p>
                                        @else
                                        <p class="text-sm text-green-600 font-medium">Free</p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('programs.show', $program) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-sm transition-colors">
                                            Register
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-graduation-cap text-gray-300 text-4xl mb-4"></i>
                                <p class="text-gray-500">No active programs available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Enhanced Sidebar -->
            <div class="space-y-8">
                <!-- Quick Actions with Modern Design -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>Quick Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('member.donations.create') }}" class="group relative flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 hover:from-green-100 hover:to-emerald-100 rounded-xl transition-all duration-300 border border-green-100 hover:border-green-200 hover:shadow-md">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                    <i class="fas fa-hand-holding-heart text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-green-700">Make Donation</p>
                                <p class="text-sm text-gray-600">Support the church ministry</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-arrow-right text-green-600 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </div>
                        </a>

                        <a href="{{ route('member.profile') }}" class="group relative flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-xl transition-all duration-300 border border-blue-100 hover:border-blue-200 hover:shadow-md">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                    <i class="fas fa-user-edit text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-blue-700">Update Profile</p>
                                <p class="text-sm text-gray-600">Edit your information</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-arrow-right text-blue-600 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </div>
                        </a>

                        <a href="{{ route('member.ministries.index') }}" class="group relative flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 rounded-xl transition-all duration-300 border border-purple-100 hover:border-purple-200 hover:shadow-md">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-purple-700">My Ministries</p>
                                <p class="text-sm text-gray-600">View your involvement</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-arrow-right text-purple-600 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </div>
                        </a>

                        <a href="{{ route('member.events.index') }}" class="group relative flex items-center p-4 bg-gradient-to-r from-orange-50 to-red-50 hover:from-orange-100 hover:to-red-100 rounded-xl transition-all duration-300 border border-orange-100 hover:border-orange-200 hover:shadow-md">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-200">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900 group-hover:text-orange-700">Browse Events</p>
                                <p class="text-sm text-gray-600">Upcoming activities</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-arrow-right text-orange-600 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Donations with Enhanced Design -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-history text-green-600 mr-2"></i>Recent Donations
                            </h2>
                            <a href="{{ route('member.donations.create') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Make Donation <i class="fas fa-plus ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentDonations->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentDonations as $donation)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-heart text-green-600 text-xs"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $donation->donationType->name ?? 'General' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $donation->donation_date ? $donation->donation_date->format('M d, Y') : 'Date N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-green-600">
                                        ₵{{ number_format($donation->amount, 2) }}
                                    </p>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="fas fa-heart text-gray-300 text-3xl mb-3"></i>
                                <p class="text-gray-500 text-sm">No donations yet.</p>
                                <a href="{{ route('member.donations.create') }}" class="text-green-600 hover:text-green-800 text-sm font-medium mt-2 inline-block">
                                    Make your first donation
                                </a>
                            </div>
                        @endif
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
                        @if($member->activeMinistries->count() > 0)
                        <div>
                            <span class="text-sm text-gray-600">Active Ministries</span>
                            <div class="mt-2 space-y-1">
                                @foreach($member->activeMinistries->take(3) as $ministry)
                                <div class="flex items-center text-xs">
                                    <div class="w-2 h-2 bg-purple-600 rounded-full mr-2"></div>
                                    <span class="text-gray-700">{{ $ministry->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Latest Testimonies Section -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-heart text-amber-600 mr-2"></i>Latest Testimonies
                            </h2>
                            <a href="{{ route('member.testimonies.index') }}" class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $latestTestimonies = \App\Models\Testimony::with('member')->published()->latest()->limit(3)->get();
                        @endphp
                        
                        @if($latestTestimonies->count() > 0)
                            <div class="space-y-4">
                                @foreach($latestTestimonies as $testimony)
                                <div class="group p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl hover:from-amber-100 hover:to-orange-100 transition-all duration-300 border border-amber-100">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            @if($testimony->member->photo_path)
                                                <img src="{{ asset('storage/' . $testimony->member->photo_path) }}" alt="{{ $testimony->member->full_name }}" class="w-full h-full object-cover rounded-full">
                                            @else
                                                <i class="fas fa-user text-white text-sm"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-amber-700 transition-colors duration-200 truncate">
                                                    {{ $testimony->title }}
                                                </h4>
                                                <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                                    {{ $testimony->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-600 mb-2 line-clamp-2">
                                                {{ Str::limit($testimony->content, 100) }}
                                            </p>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs font-medium text-amber-700">{{ $testimony->member->full_name }}</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        {{ $testimony->category_display }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('member.testimonies.show', $testimony) }}" class="text-xs text-amber-600 hover:text-amber-800 font-medium">
                                                    Read More →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Share Testimony CTA -->
                            <div class="mt-6 p-4 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl text-center">
                                <h4 class="text-white font-semibold mb-2">Have a testimony to share?</h4>
                                <p class="text-amber-100 text-sm mb-3">Encourage others with your story of God's goodness!</p>
                                <a href="{{ route('member.testimonies.create') }}" class="inline-flex items-center px-4 py-2 bg-white text-amber-600 font-semibold rounded-lg hover:bg-amber-50 transition-colors duration-200">
                                    <i class="fas fa-heart mr-2"></i>
                                    Share Your Testimony
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-heart text-amber-500 text-2xl"></i>
                                </div>
                                <h4 class="text-gray-900 font-semibold mb-2">No testimonies yet</h4>
                                <p class="text-gray-500 text-sm mb-4">Be the first to share how God has worked in your life!</p>
                                <a href="{{ route('member.testimonies.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-plus mr-2"></i>
                                    Share Your First Testimony
                                </a>
                            </div>
                        @endif
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
