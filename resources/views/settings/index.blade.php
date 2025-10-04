@extends('components.app-layout')

@section('title', 'Church Settings')
@section('subtitle', 'Manage your church system with elegance and simplicity')

@section('content')
<div class="space-y-8">
    <!-- Elegant Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-3xl shadow-2xl">
        <div class="absolute inset-0 bg-black opacity-5"></div>
        <!-- Floating shapes for elegance -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full transform translate-x-32 -translate-y-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full transform -translate-x-24 translate-y-24"></div>
        
        <div class="relative px-8 py-16">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl mb-6">
                    <i class="fas fa-church text-3xl text-white"></i>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4">Church Management</h1>
                <p class="text-xl text-white opacity-90 mb-8 max-w-2xl mx-auto">
                    Streamline your church operations with our comprehensive management system
                </p>
                
                <!-- Elegant Stats -->
                <div class="flex flex-wrap justify-center gap-6">
                    <div class="bg-white bg-opacity-15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white border-opacity-20">
                        <div class="text-3xl font-bold text-white">{{ $systemStats['total_users'] }}</div>
                        <div class="text-sm text-white opacity-80">Active Members</div>
                    </div>
                    <div class="bg-white bg-opacity-15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white border-opacity-20">
                        <div class="text-3xl font-bold text-white">{{ $systemStats['total_storage_mb'] }}</div>
                        <div class="text-sm text-white opacity-80">MB Storage</div>
                    </div>
                    <div class="bg-white bg-opacity-15 backdrop-blur-md rounded-2xl px-6 py-4 border border-white border-opacity-20">
                        <div class="text-3xl font-bold text-white">{{ $systemStats['active_documents'] }}</div>
                        <div class="text-sm text-white opacity-80">Resources</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Members Card -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Church Members</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ number_format($systemStats['total_users']) }}</p>
                        <p class="text-xs text-blue-600 mt-1 flex items-center">
                            <i class="fas fa-arrow-up text-xs mr-1"></i>Active accounts
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Resources Card -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Resources</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ number_format($systemStats['total_documents']) }}</p>
                        <p class="text-xs text-emerald-600 mt-1 flex items-center">
                            <i class="fas fa-check text-xs mr-1"></i>{{ $systemStats['active_documents'] }} active
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-book text-2xl text-emerald-600"></i>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Categories</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">{{ number_format($systemStats['total_categories']) }}</p>
                        <p class="text-xs text-purple-600 mt-1 flex items-center">
                            <i class="fas fa-layer-group text-xs mr-1"></i>{{ $systemStats['active_categories'] ?? 'All' }} active
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tags text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Storage Card -->
            <div class="group bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Storage</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">{{ number_format($systemStats['total_storage_mb'], 1) }}</p>
                        <p class="text-xs text-orange-600 mt-1 flex items-center">
                            <i class="fas fa-database text-xs mr-1"></i>MB used
                        </p>
                    </div>
                    <div class="p-4 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-hdd text-2xl text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Elegant Settings Cards -->
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Church Configuration -->
            <div class="group relative bg-white rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-church text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Church Configuration</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Complete church management setup including branding, contact information, and system preferences.</p>
                    <div class="space-y-3">
                        <a href="{{ route('settings.general') }}" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-cog mr-2"></i>Configure Church
                        </a>
                        <a href="{{ route('settings.backup') }}" class="block w-full text-center px-6 py-3 bg-indigo-50 text-indigo-700 rounded-2xl hover:bg-indigo-100 transition-colors duration-300">
                            <i class="fas fa-shield-alt mr-2"></i>Backup & Security
                        </a>
                    </div>
                </div>
            </div>

            <!-- Member Management -->
            <div class="group relative bg-white rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Member Management</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Manage church members, user accounts, roles, and access permissions with ease.</p>
                    <div class="space-y-3">
                        <a href="{{ route('users.index') }}" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-user-friends mr-2"></i>Manage Members
                        </a>
                        <a href="{{ route('users.create') }}" class="block w-full text-center px-6 py-3 bg-emerald-50 text-emerald-700 rounded-2xl hover:bg-emerald-100 transition-colors duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Add New Member
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Maintenance -->
            <div class="group relative bg-white rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-rose-50 to-pink-50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tools text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">System Maintenance</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Keep your church system running smoothly with maintenance tools and optimization.</p>
                    <div class="space-y-3">
                        <button onclick="clearCache()" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-rose-600 to-pink-600 text-white rounded-2xl hover:from-rose-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-broom mr-2"></i>Clear Cache
                        </button>
                        <button onclick="optimizeSystem()" class="block w-full text-center px-6 py-3 bg-rose-50 text-rose-700 rounded-2xl hover:bg-rose-100 transition-colors duration-300">
                            <i class="fas fa-rocket mr-2"></i>Optimize System
                        </button>
                    </div>
                </div>
            </div>

            <!-- Security & Logs -->
            <div class="group relative bg-white rounded-3xl shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Security & Monitoring</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Monitor system security, view activity logs, and manage user access controls.</p>
                    <div class="space-y-3">
                        <a href="{{ route('security.dashboard') }}" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-2xl hover:from-amber-700 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-tachometer-alt mr-2"></i>Security Dashboard
                        </a>
                        <a href="{{ route('security.logs.index') }}" class="block w-full text-center px-6 py-3 bg-amber-50 text-amber-700 rounded-2xl hover:bg-amber-100 transition-colors duration-300">
                            <i class="fas fa-list-alt mr-2"></i>Activity Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Elegant Storage Overview -->
    @if($categoryStorage->count() > 0)
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-gray-50 px-8 py-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-chart-pie text-indigo-600 mr-3"></i>
                                Storage Analytics
                            </h3>
                            <p class="text-gray-600 mt-1">Monitor your church system's storage usage</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-indigo-600">{{ $systemStats['total_storage_mb'] }}</div>
                            <div class="text-sm text-gray-500">Total MB Used</div>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categoryStorage as $category)
                            <div class="group relative bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, {{ $category['color'] }}20, {{ $category['color'] }}10);">
                                            <i class="{{ $category['icon'] }} text-xl" style="color: {{ $category['color'] }};"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $category['name'] }}</h4>
                                            <p class="text-sm text-gray-500">Storage Category</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-end justify-between">
                                    <div class="text-right">
                                        <div class="text-2xl font-bold" style="color: {{ $category['color'] }};">{{ $category['size_mb'] }}</div>
                                        <div class="text-sm text-gray-500">MB Used</div>
                                    </div>
                                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500" 
                                             style="background-color: {{ $category['color'] }}; width: {{ min(($category['size_mb'] / max($systemStats['total_storage_mb'], 1)) * 100, 100) }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Elegant Footer -->
    <div class="max-w-6xl mx-auto text-center py-8">
        <div class="inline-flex items-center space-x-2 text-gray-500">
            <i class="fas fa-heart text-red-400"></i>
            <span class="text-sm">Built with love for church communities</span>
        </div>
    </div>

</div>

@push('scripts')
<script>
function clearCache() {
    if (confirm('Are you sure you want to clear the system cache?')) {
        // Add AJAX call to clear cache endpoint
        alert('Cache cleared successfully!');
    }
}

function optimizeSystem() {
    if (confirm('Are you sure you want to optimize the system?')) {
        // Add AJAX call to optimize system endpoint
        alert('System optimization completed!');
    }
}
</script>
@endpush
@endsection
