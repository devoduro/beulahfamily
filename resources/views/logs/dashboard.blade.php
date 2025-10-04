@extends('components.app-layout')

@section('title', 'Security Dashboard')
@section('subtitle', 'Monitor system security and user activity')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 rounded-3xl shadow-2xl">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative px-8 py-16">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl mb-6">
                    <i class="fas fa-shield-alt text-3xl text-white"></i>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4">Security Dashboard</h1>
                <p class="text-xl text-white opacity-90 mb-8 max-w-2xl mx-auto">
                    Monitor your church system's security status and user activities
                </p>
            </div>
        </div>
    </div>

    <!-- Security Stats -->
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Users -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-blue-600 mt-1 flex items-center">
                            <i class="fas fa-users text-xs mr-1"></i>Registered
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Active Users</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $stats['active_users'] }}</p>
                        <p class="text-xs text-emerald-600 mt-1 flex items-center">
                            <i class="fas fa-check-circle text-xs mr-1"></i>Enabled
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-check text-2xl text-emerald-600"></i>
                    </div>
                </div>
            </div>

            <!-- Security Alerts -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Security Alerts</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $stats['security_alerts'] }}</p>
                        <p class="text-xs text-red-600 mt-1 flex items-center">
                            <i class="fas fa-exclamation-triangle text-xs mr-1"></i>Last 7 days
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-red-100 to-red-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>

            <!-- Failed Logins -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Failed Logins</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">{{ $stats['failed_logins_today'] }}</p>
                        <p class="text-xs text-orange-600 mt-1 flex items-center">
                            <i class="fas fa-clock text-xs mr-1"></i>Today
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-times text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-50 to-gray-50 px-8 py-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-history text-indigo-600 mr-3"></i>
                            Recent Activities
                        </h3>
                        <p class="text-gray-600 mt-1">Latest system activities and user actions</p>
                    </div>
                    <a href="{{ route('security.logs.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>View All Logs
                    </a>
                </div>
            </div>
            <div class="p-8">
                @if($stats['recent_activities']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['recent_activities'] as $activity)
                            <div class="flex items-start space-x-4 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        @if($activity->severity === 'critical') bg-red-100 text-red-600
                                        @elseif($activity->severity === 'high') bg-orange-100 text-orange-600
                                        @elseif($activity->severity === 'medium') bg-yellow-100 text-yellow-600
                                        @else bg-green-100 text-green-600 @endif">
                                        <i class="fas fa-{{ $activity->severity === 'critical' ? 'exclamation-triangle' : ($activity->severity === 'high' ? 'exclamation' : ($activity->severity === 'medium' ? 'info' : 'check')) }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $activity->user ? $activity->user->name : 'System' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                        @if($activity->ip_address)
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-globe mr-1"></i>
                                                {{ $activity->ip_address }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($activity->severity === 'high') bg-orange-100 text-orange-800
                                        @elseif($activity->severity === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($activity->severity) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-history text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No recent activities to display</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- View All Logs -->
            <a href="{{ route('security.logs.index') }}" class="group relative bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-list text-xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Activity Logs</h3>
                        <p class="text-sm text-gray-500">View detailed activity logs</p>
                    </div>
                </div>
            </a>

            <!-- User Management -->
            <a href="{{ route('users.index') }}" class="group relative bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users-cog text-xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">User Management</h3>
                        <p class="text-sm text-gray-500">Manage user accounts</p>
                    </div>
                </div>
            </a>

            <!-- Settings -->
            <a href="{{ route('settings.index') }}" class="group relative bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cog text-xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">System Settings</h3>
                        <p class="text-sm text-gray-500">Configure system settings</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
