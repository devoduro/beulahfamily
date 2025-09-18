@extends('member.layouts.app')

@section('title', 'Family & Connections')

@section('content')
<!-- Enhanced Background with Animated Elements -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-purple-400/10 to-blue-400/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 4s"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Enhanced Header with Gradient Background -->
        <div class="relative mb-16">
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl shadow-2xl overflow-hidden">
                <!-- Animated Header Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                
                <div class="relative p-10">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                        <div class="mb-8 lg:mb-0">
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mr-6 shadow-xl">
                                    <i class="fas fa-users text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-4xl lg:text-5xl font-bold text-white mb-2">Family & Connections</h1>
                                    <p class="text-xl text-blue-100">Manage your family members and church connections</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-6 text-white/80">
                                <div class="flex items-center">
                                    <i class="fas fa-home mr-2"></i>
                                    <span>{{ $family ? $family->family_name : auth('member')->user()->last_name . ' Family' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <span>Member since {{ auth('member')->user()->membership_date ? auth('member')->user()->membership_date->format('Y') : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-heart mr-2"></i>
                                    <span>{{ $familyStats['total_members'] }} Member{{ $familyStats['total_members'] !== 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col space-y-4">
                            <a href="{{ route('member.family.add-member') }}" class="group bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center border border-white/30">
                                <i class="fas fa-plus mr-3 group-hover:scale-110 transition-transform"></i>
                                Add Family Member
                                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                            <a href="{{ route('member.family.export') }}" class="group bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-8 py-3 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                                <i class="fas fa-download mr-2 group-hover:scale-110 transition-transform"></i>
                                Export Family Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Family Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Family Members Card -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-blue-100 p-8 hover:shadow-2xl hover:border-blue-200 transition-all duration-500 transform hover:-translate-y-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
                </div>
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Family Members</p>
                    <h3 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">{{ $familyStats['total_members'] }}</h3>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Active
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">Total registered</p>
                    <div class="flex items-center text-green-600 text-sm font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>Active</span>
                    </div>
                </div>
            </div>

            <!-- Church Members Card -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-green-100 p-8 hover:shadow-2xl hover:border-green-200 transition-all duration-500 transform hover:-translate-y-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-church text-white text-2xl"></i>
                    </div>
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                </div>
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Church Members</p>
                    <h3 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-2">{{ $familyStats['church_members'] }}</h3>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-star mr-1"></i>
                            Verified
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">Baptized members</p>
                    <div class="flex items-center text-blue-600 text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span>Verified</span>
                    </div>
                </div>
            </div>

            <!-- Connections Card -->
            <div class="group bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-2 border-purple-100 p-8 hover:shadow-2xl hover:border-purple-200 transition-all duration-500 transform hover:-translate-y-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-violet-600 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <div class="w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
                </div>
                <div class="mb-4">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Connections</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-violet-600 bg-clip-text text-transparent">12</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">Church relationships</p>
                    <div class="flex items-center text-orange-600 text-sm font-medium">
                        <i class="fas fa-users mr-1"></i>
                        <span>Growing</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Enhanced Family Members Section -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-gray-100 overflow-hidden">
                    <!-- Enhanced Header -->
                    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 p-8">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="absolute -top-5 -right-5 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
                        <div class="absolute -bottom-5 -left-5 w-16 h-16 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                        
                        <div class="relative flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold text-white mb-1">My Family</h2>
                                    <p class="text-indigo-100">Manage your family members</p>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <select class="bg-white/20 backdrop-blur-sm border border-white/30 text-white px-4 py-3 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-white/50 focus:border-white/50">
                                    <option class="text-gray-900">All Members</option>
                                    <option class="text-gray-900">Church Members</option>
                                    <option class="text-gray-900">Non-Members</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 space-y-8">
                        @forelse($familyMembers as $index => $member)
                            @php
                                $gradientColors = [
                                    0 => ['from' => 'from-green-50', 'to' => 'to-emerald-50', 'border' => 'border-green-100', 'hover-border' => 'hover:border-green-200', 'icon-bg' => 'from-green-500 to-emerald-600', 'badge-bg' => 'bg-green-500', 'hover-text' => 'group-hover:text-green-600'],
                                    1 => ['from' => 'from-blue-50', 'to' => 'to-indigo-50', 'border' => 'border-blue-100', 'hover-border' => 'hover:border-blue-200', 'icon-bg' => 'from-blue-500 to-indigo-600', 'badge-bg' => 'bg-blue-500', 'hover-text' => 'group-hover:text-blue-600'],
                                    2 => ['from' => 'from-yellow-50', 'to' => 'to-orange-50', 'border' => 'border-yellow-100', 'hover-border' => 'hover:border-yellow-200', 'icon-bg' => 'from-yellow-500 to-orange-600', 'badge-bg' => 'bg-yellow-500', 'hover-text' => 'group-hover:text-yellow-600'],
                                    3 => ['from' => 'from-purple-50', 'to' => 'to-pink-50', 'border' => 'border-purple-100', 'hover-border' => 'hover:border-purple-200', 'icon-bg' => 'from-purple-500 to-pink-600', 'badge-bg' => 'bg-purple-500', 'hover-text' => 'group-hover:text-purple-600'],
                                ];
                                $colorIndex = $index % 4;
                                $colors = $gradientColors[$colorIndex];
                                
                                $statusIcon = match($member->membership_status) {
                                    'active' => 'fas fa-check',
                                    'inactive' => 'fas fa-clock',
                                    'pending' => 'fas fa-hourglass-half',
                                    default => 'fas fa-user'
                                };
                            @endphp
                            
                            <!-- Enhanced Family Member Card -->
                            <div class="group bg-gradient-to-r {{ $colors['from'] }} {{ $colors['to'] }} rounded-3xl p-8 border-2 {{ $colors['border'] }} {{ $colors['hover-border'] }} hover:shadow-xl transition-all duration-500 transform hover:-translate-y-1">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex items-start">
                                        <div class="relative">
                                            <div class="w-20 h-20 bg-gradient-to-br {{ $colors['icon-bg'] }} rounded-3xl flex items-center justify-center mr-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                @if($member->photo_path)
                                                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full rounded-3xl object-cover">
                                                @else
                                                    <i class="fas fa-user text-white text-2xl"></i>
                                                @endif
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-6 h-6 {{ $colors['badge-bg'] }} rounded-full flex items-center justify-center border-2 border-white">
                                                <i class="{{ $statusIcon }} text-white text-xs"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 mb-1 {{ $colors['hover-text'] }} transition-colors">{{ $member->full_name }}</h3>
                                            <p class="text-lg text-gray-600 mb-4">{{ ucfirst($member->relationship_to_head ?? 'Member') }}</p>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @if($member->phone)
                                            <div class="flex items-center bg-white/60 backdrop-blur-sm rounded-2xl p-3">
                                                <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                                    <i class="fas fa-phone text-blue-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Phone</p>
                                                    <p class="font-semibold text-gray-900 text-sm">{{ $member->phone }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($member->email)
                                            <div class="flex items-center bg-white/60 backdrop-blur-sm rounded-2xl p-3">
                                                <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                                                    <i class="fas fa-envelope text-green-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                                                    <p class="font-semibold text-gray-900 text-sm">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            @if($member->date_of_birth)
                                            <div class="flex items-center bg-white/60 backdrop-blur-sm rounded-2xl p-3">
                                                <div class="w-8 h-8 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                                                    <i class="fas fa-birthday-cake text-purple-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Age</p>
                                                    <p class="font-semibold text-gray-900 text-sm">{{ $member->age ?? 'N/A' }} years</p>
                                                </div>
                                            </div>
                                            @endif
                                            
                                            <div class="flex items-center bg-white/60 backdrop-blur-sm rounded-2xl p-3">
                                                <div class="w-8 h-8 bg-orange-100 rounded-xl flex items-center justify-center mr-3">
                                                    <i class="fas fa-id-card text-orange-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                                                    <p class="font-semibold text-gray-900 text-sm">{{ ucfirst($member->membership_status ?? 'Unknown') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                            @if($member->membership_status === 'active') bg-green-100 text-green-800
                                            @elseif($member->membership_status === 'inactive') bg-red-100 text-red-800
                                            @elseif($member->membership_status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            <i class="{{ $statusIcon }} mr-1"></i>
                                            {{ ucfirst($member->membership_status ?? 'Unknown') }}
                                        </span>
                                        @if($member->membership_type)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-certificate mr-1"></i>
                                            {{ ucfirst($member->membership_type) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('member.family.show-member', $member) }}" class="group flex items-center bg-white/80 backdrop-blur-sm hover:bg-white text-gray-700 hover:text-blue-600 px-6 py-3 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    <i class="fas fa-eye mr-2 group-hover:scale-110 transition-transform"></i>
                                    View Profile
                                </a>
                                <button class="group flex items-center bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white px-6 py-3 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    <i class="fas fa-edit mr-2 group-hover:scale-110 transition-transform"></i>
                                    Edit Member
                                </button>
                                @if($member->membership_status !== 'active')
                                <button class="group flex items-center bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-6 py-3 rounded-2xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                    <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform"></i>
                                    Register as Member
                                </button>
                                @endif
                            </div>
                        </div>
                        @empty
                        <!-- Add Family Member Card -->
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 border-2 border-dashed border-blue-300 rounded-3xl p-12 text-center hover:border-blue-400 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                                <i class="fas fa-user-plus text-white text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">No Family Members Yet</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">Start building your family tree by adding your first family member. Connect with your loved ones and manage your family information all in one place.</p>
                            <a href="{{ route('member.family.add-member') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                                <i class="fas fa-plus mr-3"></i>
                                Add Your First Family Member
                                <i class="fas fa-arrow-right ml-3"></i>
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Enhanced Family Activities -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-green-100 overflow-hidden">
                    <div class="relative bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="absolute -top-3 -right-3 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                        <div class="absolute -bottom-3 -left-3 w-12 h-12 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s"></div>
                        
                        <div class="relative flex items-center">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-1">Family Activities</h3>
                                <p class="text-green-100">Recent events and programs</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        @forelse($familyActivities as $activity)
                        <div class="group flex items-center bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            <div class="w-4 h-4 bg-blue-500 rounded-full mr-4 animate-pulse"></div>
                            <div class="flex-1">
                                <div class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $activity['title'] }}</div>
                                <div class="text-sm text-gray-600 flex items-center mt-1">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $activity['date'] }}
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-blue-500 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-400 text-3xl mb-4"></i>
                            <p class="text-gray-500">No recent family activities</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Enhanced Church Connections -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-blue-100 overflow-hidden">
                    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="absolute -top-3 -right-3 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                        <div class="absolute -bottom-3 -left-3 w-12 h-12 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1.5s"></div>
                        
                        <div class="relative flex items-center">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-1">Church Connections</h3>
                                <p class="text-blue-100">Your spiritual network</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        @forelse($churchConnections as $connection)
                        <div class="group flex items-center bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ $connection['name'] }}</div>
                                <div class="text-sm text-gray-600 flex items-center mt-1">
                                    <i class="fas fa-church mr-2"></i>
                                    {{ $connection['role'] }}
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-purple-500 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-400 text-3xl mb-4"></i>
                            <p class="text-gray-500">No church connections found</p>
                        </div>
                        @endforelse
                        
                        <button class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 px-6 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-users mr-3"></i>
                            View All Connections
                            <i class="fas fa-arrow-right ml-3"></i>
                        </button>
                    </div>
                </div>

                <!-- Enhanced Quick Actions -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border-2 border-yellow-100 overflow-hidden">
                    <div class="relative bg-gradient-to-r from-yellow-600 to-orange-600 p-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                        <div class="absolute -top-3 -right-3 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                        <div class="absolute -bottom-3 -left-3 w-12 h-12 bg-white/10 rounded-full animate-pulse" style="animation-delay: 2.5s"></div>
                        
                        <div class="relative flex items-center">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4">
                                <i class="fas fa-bolt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-1">Quick Actions</h3>
                                <p class="text-yellow-100">Manage your family</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-4">
                        <a href="{{ route('member.family.add-member') }}" class="group w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 px-6 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-plus mr-3 group-hover:scale-110 transition-transform"></i>
                            Add Family Member
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('member.family.export') }}" class="group w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-4 px-6 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-download mr-3 group-hover:scale-110 transition-transform"></i>
                            Export Family Data
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="{{ route('member.profile') }}" class="group w-full bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 text-white py-4 px-6 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-user mr-3 group-hover:scale-110 transition-transform"></i>
                            Update Profile
                            <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
