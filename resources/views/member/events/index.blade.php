@extends('member.layouts.app')

@section('title', 'Events & Programs')

@section('content')
<!-- Enhanced Background with Animated Elements -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full opacity-10 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-400 to-pink-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-1/3 left-1/4 w-32 h-32 bg-gradient-to-r from-green-400 to-blue-500 rounded-full opacity-5 animate-pulse" style="animation-delay: 4s"></div>
        <div class="absolute bottom-1/4 right-1/3 w-24 h-24 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full opacity-5 animate-pulse" style="animation-delay: 6s"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Enhanced Header with Glassmorphism -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl shadow-2xl mb-8 transform hover:scale-110 transition-all duration-300">
                <i class="fas fa-calendar-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-6">
                Events & Programs
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Discover meaningful connections, spiritual growth, and community engagement through our diverse range of church events and programs
            </p>
            
            <!-- Enhanced Filter Controls -->
            <div class="flex flex-wrap justify-center gap-4 max-w-2xl mx-auto">
                <div class="relative group">
                    <select class="appearance-none bg-white/80 backdrop-blur-sm border-2 border-blue-100 rounded-2xl px-6 py-3 pr-10 text-gray-700 font-medium shadow-lg hover:shadow-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-400 transition-all duration-300 cursor-pointer">
                        <option>All Events</option>
                        <option>This Week</option>
                        <option>This Month</option>
                        <option>Upcoming</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-blue-500 group-hover:text-blue-600"></i>
                    </div>
                </div>
                <div class="relative group">
                    <select class="appearance-none bg-white/80 backdrop-blur-sm border-2 border-purple-100 rounded-2xl px-6 py-3 pr-10 text-gray-700 font-medium shadow-lg hover:shadow-xl focus:ring-4 focus:ring-purple-200 focus:border-purple-400 transition-all duration-300 cursor-pointer">
                        <option>All Categories</option>
                        <option>Worship</option>
                        <option>Fellowship</option>
                        <option>Training</option>
                        <option>Outreach</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-purple-500 group-hover:text-purple-600"></i>
                    </div>
                </div>
                <button class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-3 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Search Events
                </button>
            </div>
        </div>

        <!-- Enhanced Event Stats with Glassmorphism -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-blue-100 p-8 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <div class="ml-6">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Upcoming Events</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">8</p>
                        <p class="text-xs text-gray-500 mt-1">This month</p>
                    </div>
                </div>
            </div>

            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-green-100 p-8 hover:shadow-2xl hover:border-green-200 transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                    <div class="ml-6">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Events Attended</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">12</p>
                        <p class="text-xs text-gray-500 mt-1">All time</p>
                    </div>
                </div>
            </div>

            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-purple-100 p-8 hover:shadow-2xl hover:border-purple-200 transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="ml-6">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Registered</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">5</p>
                        <p class="text-xs text-gray-500 mt-1">Active</p>
                    </div>
                </div>
            </div>

            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-orange-100 p-8 hover:shadow-2xl hover:border-orange-200 transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <div class="ml-6">
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Favorites</p>
                        <p class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">3</p>
                        <p class="text-xs text-gray-500 mt-1">Saved</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Featured Event with 3D Effects -->
        <div class="relative group mb-16">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl shadow-2xl overflow-hidden transform group-hover:scale-105 transition-all duration-500">
                <!-- Animated Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/20 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                </div>
                
                <div class="relative p-10 text-white">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                        <div class="flex-1 mb-8 lg:mb-0">
                            <div class="flex items-center mb-6">
                                <span class="inline-flex items-center bg-white/20 backdrop-blur-sm text-white text-sm font-bold px-4 py-2 rounded-full border border-white/30">
                                    <i class="fas fa-star mr-2 text-yellow-300"></i>
                                    Featured Event
                                </span>
                                <div class="ml-4 flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                    <span class="text-green-300 text-sm font-medium">Registration Open</span>
                                </div>
                            </div>
                            
                            <h2 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                                ERGATES Conference 2025
                            </h2>
                            <p class="text-xl text-blue-100 mb-8 max-w-2xl">
                                Join Ghana's premier business and entrepreneurship conference. Network with industry leaders, gain valuable insights, and grow your business.
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                                <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-calendar text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-blue-200 text-xs uppercase tracking-wide">Date</p>
                                        <p class="font-semibold">March 15-17, 2025</p>
                                    </div>
                                </div>
                                <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-blue-200 text-xs uppercase tracking-wide">Venue</p>
                                        <p class="font-semibold">Accra International Conference Centre</p>
                                    </div>
                                </div>
                                <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-users text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-blue-200 text-xs uppercase tracking-wide">Capacity</p>
                                        <p class="font-semibold">500+ Expected</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="lg:ml-8 text-center lg:text-right">
                            <div class="mb-6">
                                <p class="text-blue-200 text-sm mb-2">Early Bird Price</p>
                                <p class="text-4xl font-bold mb-1">₵299</p>
                                <p class="text-blue-200 text-sm line-through">₵499</p>
                            </div>
                            <button class="group bg-white hover:bg-gray-100 text-blue-600 px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center mx-auto lg:mx-0">
                                <i class="fas fa-ticket-alt mr-3 group-hover:scale-110 transition-transform"></i>
                                Register Now
                                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <p class="text-blue-200 text-sm mt-3">
                                <i class="fas fa-clock mr-1"></i>
                                Early bird ends in 15 days
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Events Grid with Modern Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            <!-- Sunday Service -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-blue-100 overflow-hidden hover:shadow-2xl hover:border-blue-200 transition-all duration-500 transform hover:-translate-y-2">
                <div class="relative h-64 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 overflow-hidden">
                    <!-- Animated Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                    
                    <div class="relative flex items-center justify-center h-full">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-church text-white text-3xl"></i>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute top-6 right-6">
                        <button class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white hover:bg-white/30 transition-colors border border-white/30">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-full border border-blue-200">
                            <i class="fas fa-sync-alt mr-2 text-xs"></i>
                            Weekly
                        </span>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="text-green-600 text-sm font-medium">Active</span>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                        Sunday Worship Service
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Join us for inspiring worship, prayer, and biblical teaching every Sunday. Experience community and spiritual growth.
                    </p>
                    
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Schedule</p>
                                <p class="font-semibold text-gray-900">Every Sunday</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Time</p>
                                <p class="font-semibold text-gray-900">9:00 AM - 11:30 AM</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Location</p>
                                <p class="font-semibold text-gray-900">Main Sanctuary</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center bg-green-50 px-4 py-2 rounded-full border border-green-200">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-green-700 font-semibold text-sm">Registered</span>
                        </div>
                        <button class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-2xl font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                            View Details
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Youth Fellowship -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-purple-100 overflow-hidden hover:shadow-2xl hover:border-purple-200 transition-all duration-500 transform hover:-translate-y-2">
                <div class="relative h-64 bg-gradient-to-br from-purple-500 via-purple-600 to-violet-700 overflow-hidden">
                    <!-- Animated Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 2s"></div>
                    
                    <div class="relative flex items-center justify-center h-full">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-white text-3xl"></i>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute top-6 right-6">
                        <button class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white hover:bg-white/30 transition-colors border border-white/30">
                            <i class="fas fa-heart text-red-400"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center bg-purple-100 text-purple-800 text-sm font-bold px-4 py-2 rounded-full border border-purple-200">
                            <i class="fas fa-calendar-check mr-2 text-xs"></i>
                            Monthly
                        </span>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
                            <span class="text-purple-600 text-sm font-medium">Popular</span>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                        Youth Fellowship Night
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Fun, fellowship, and faith-building activities for young adults aged 18-35. Connect, grow, and serve together.
                    </p>
                    
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-alt text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Schedule</p>
                                <p class="font-semibold text-gray-900">First Friday of Month</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Time</p>
                                <p class="font-semibold text-gray-900">7:00 PM - 10:00 PM</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Location</p>
                                <p class="font-semibold text-gray-900">Youth Center</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button class="bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white px-6 py-3 rounded-2xl font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Register
                        </button>
                        <button class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-2xl font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                            View Details
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Community Outreach -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-orange-100 overflow-hidden hover:shadow-2xl hover:border-orange-200 transition-all duration-500 transform hover:-translate-y-2">
                <div class="relative h-64 bg-gradient-to-br from-orange-500 via-orange-600 to-red-600 overflow-hidden">
                    <!-- Animated Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 2.5s"></div>
                    
                    <div class="relative flex items-center justify-center h-full">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-hands-helping text-white text-3xl"></i>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute top-6 right-6">
                        <button class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white hover:bg-white/30 transition-colors border border-white/30">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center bg-orange-100 text-orange-800 text-sm font-bold px-4 py-2 rounded-full border border-orange-200">
                            <i class="fas fa-star mr-2 text-xs"></i>
                            Special
                        </span>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-400 rounded-full animate-pulse"></div>
                            <span class="text-red-600 text-sm font-medium">Urgent</span>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">
                        Community Food Drive
                    </h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Help us serve our community by volunteering at our monthly food distribution. Make a difference in lives.
                    </p>
                    
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-alt text-orange-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Date</p>
                                <p class="font-semibold text-gray-900">December 21, 2024</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Time</p>
                                <p class="font-semibold text-gray-900">8:00 AM - 2:00 PM</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-gray-50 rounded-2xl p-3">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Location</p>
                                <p class="font-semibold text-gray-900">Church Parking Lot</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <button class="bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white px-6 py-3 rounded-2xl font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                            <i class="fas fa-hand-holding-heart mr-2"></i>
                            Volunteer
                        </button>
                        <button class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-2xl font-semibold text-sm shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                            View Details
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
    </div>

        <!-- Enhanced My Registrations Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-gray-100 overflow-hidden">
            <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-8">
                <!-- Animated Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="absolute -top-5 -right-5 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
                <div class="absolute -bottom-5 -left-5 w-16 h-16 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                
                <div class="relative flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">My Event Registrations</h2>
                        <p class="text-indigo-100">Track your upcoming events and activities</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="space-y-6">
                    <!-- Enhanced Registration Item -->
                    <div class="group bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-6 border-2 border-blue-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center mr-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-church text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">Sunday Worship Service</h4>
                                    <p class="text-gray-600 mb-2">Weekly spiritual gathering and fellowship</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-calendar-plus mr-2"></i>
                                        <span>Registered on Dec 1, 2024</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="inline-flex items-center bg-green-100 text-green-800 text-sm font-bold px-4 py-2 rounded-full border border-green-200 mb-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                        Active
                                    </div>
                                    <p class="text-xs text-gray-500">Every Sunday</p>
                                </div>
                                <div class="relative">
                                    <button class="w-12 h-12 bg-white/80 backdrop-blur-sm rounded-2xl flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-white transition-all duration-300 shadow-lg">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Registration Item -->
                    <div class="group bg-gradient-to-r from-purple-50 to-violet-50 rounded-3xl p-6 border-2 border-purple-100 hover:border-purple-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl flex items-center justify-center mr-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-purple-600 transition-colors">Youth Fellowship Night</h4>
                                    <p class="text-gray-600 mb-2">Monthly gathering for young adults</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>Next event: Jan 3, 2025</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="inline-flex items-center bg-blue-100 text-blue-800 text-sm font-bold px-4 py-2 rounded-full border border-blue-200 mb-2">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></div>
                                        Upcoming
                                    </div>
                                    <p class="text-xs text-gray-500">In 12 days</p>
                                </div>
                                <div class="relative">
                                    <button class="w-12 h-12 bg-white/80 backdrop-blur-sm rounded-2xl flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-white transition-all duration-300 shadow-lg">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add More Events Button -->
                    <div class="text-center pt-6">
                        <button class="group bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-8 py-4 rounded-3xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex items-center mx-auto">
                            <i class="fas fa-plus mr-3 group-hover:scale-110 transition-transform"></i>
                            Browse More Events
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
