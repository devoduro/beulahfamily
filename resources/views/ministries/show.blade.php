@extends('components.app-layout')

@section('title', $ministry->name)
@section('subtitle', 'Ministry Details and Members')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('ministries.index') }}" class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Ministries
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">{{ $ministry->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ ucfirst($ministry->ministry_type) }} Ministry</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('ministries.members.manage', $ministry) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200">
                        <i class="fas fa-users mr-2"></i>
                        Manage Members
                    </a>
                    @if($ministry->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-pause-circle mr-1"></i>
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ministry Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Members</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministryStats['total_members'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-check text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Members</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministryStats['active_members'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-crown text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Leaders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministryStats['leaders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Avg Age</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministryStats['average_age'] ? round($ministryStats['average_age']) : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ministry Information -->
            <div class="lg:col-span-1">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Ministry Information</h2>
                    
                    <div class="space-y-4">
                        @if($ministry->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-gray-900">{{ $ministry->description }}</p>
                            </div>
                        @endif

                        @if($ministry->leader)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ministry Leader</label>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $ministry->leader->full_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $ministry->leader->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($ministry->meeting_day || $ministry->meeting_time)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Schedule</label>
                                <div class="flex items-center space-x-2 text-gray-900">
                                    <i class="fas fa-calendar text-purple-500"></i>
                                    <span>
                                        @if($ministry->meeting_day)
                                            {{ ucfirst($ministry->meeting_day) }}s
                                        @endif
                                        @if($ministry->meeting_time)
                                            at {{ $ministry->meeting_time->format('g:i A') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($ministry->meeting_location)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Location</label>
                                <div class="flex items-center space-x-2 text-gray-900">
                                    <i class="fas fa-map-marker-alt text-purple-500"></i>
                                    <span>{{ $ministry->meeting_location }}</span>
                                </div>
                            </div>
                        @endif

                        @if($ministry->target_age_min || $ministry->target_age_max)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target Age Range</label>
                                <div class="flex items-center space-x-2 text-gray-900">
                                    <i class="fas fa-users text-purple-500"></i>
                                    <span>
                                        @if($ministry->target_age_min && $ministry->target_age_max)
                                            {{ $ministry->target_age_min }} - {{ $ministry->target_age_max }} years
                                        @elseif($ministry->target_age_min)
                                            {{ $ministry->target_age_min }}+ years
                                        @elseif($ministry->target_age_max)
                                            Up to {{ $ministry->target_age_max }} years
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($ministry->target_gender !== 'all')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target Gender</label>
                                <div class="flex items-center space-x-2 text-gray-900">
                                    <i class="fas fa-user text-purple-500"></i>
                                    <span>{{ ucfirst($ministry->target_gender) }}</span>
                                </div>
                            </div>
                        @endif

                        @if($ministry->budget)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Annual Budget</label>
                                <div class="flex items-center space-x-2 text-gray-900">
                                    <i class="fas fa-dollar-sign text-purple-500"></i>
                                    <span>GHS {{ number_format($ministry->budget, 2) }}</span>
                                </div>
                            </div>
                        @endif

                        @if($ministry->requirements)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                                <p class="text-gray-900">{{ $ministry->requirements }}</p>
                            </div>
                        @endif

                        @if($ministry->goals)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Goals</label>
                                <p class="text-gray-900">{{ $ministry->goals }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ministry Members -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Ministry Members</h2>
                        <span class="text-sm text-gray-500">{{ $ministry->members->count() }} members</span>
                    </div>

                    @if($ministry->members->count() > 0)
                        <div class="space-y-4">
                            @foreach($ministry->members as $member)
                                <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-xl hover:bg-gray-100/50 transition-colors duration-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                            @if($member->photo)
                                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <i class="fas fa-user text-white"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $member->full_name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $member->email }}</p>
                                            @if($member->pivot->joined_date)
                                                <p class="text-xs text-gray-400">Joined: {{ \Carbon\Carbon::parse($member->pivot->joined_date)->format('M j, Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($member->pivot->role === 'leader') bg-purple-100 text-purple-800
                                            @elseif($member->pivot->role === 'assistant_leader') bg-blue-100 text-blue-800
                                            @elseif($member->pivot->role === 'coordinator') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($member->pivot->role === 'leader')
                                                <i class="fas fa-crown mr-1"></i>
                                            @elseif($member->pivot->role === 'assistant_leader')
                                                <i class="fas fa-user-tie mr-1"></i>
                                            @elseif($member->pivot->role === 'coordinator')
                                                <i class="fas fa-cog mr-1"></i>
                                            @else
                                                <i class="fas fa-user mr-1"></i>
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $member->pivot->role)) }}
                                        </span>
                                        @if($member->pivot->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-pause-circle mr-1"></i>
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No members yet</h3>
                            <p class="text-gray-500">This ministry doesn't have any members assigned yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
