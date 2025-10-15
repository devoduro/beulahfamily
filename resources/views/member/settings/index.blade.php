@extends('member.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Settings</h1>
            <p class="text-gray-600">Manage your account and preferences</p>
        </div>

        <!-- Settings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Profile Settings -->
            <a href="{{ route('member.profile') }}" class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-edit text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Profile Settings</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Update your personal information, photo, and contact details</p>
                <div class="flex items-center text-blue-600 text-sm font-medium">
                    <span>Manage Profile</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>

            <!-- Password & Security -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-lock text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Password & Security</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Change your password and manage security settings</p>
                <button onclick="document.getElementById('passwordModal').classList.remove('hidden')" class="flex items-center text-green-600 text-sm font-medium hover:text-green-700">
                    <span>Change Password</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>

            <!-- Notification Preferences -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bell text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Control how you receive updates and announcements</p>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" checked class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-3 text-sm text-gray-700">Email notifications</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" checked class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-3 text-sm text-gray-700">SMS alerts</span>
                    </label>
                </div>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Account Info</h3>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member ID:</span>
                        <span class="font-medium text-gray-900">{{ Auth::guard('member')->user()->member_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ ucfirst(Auth::guard('member')->user()->membership_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Chapter:</span>
                        <span class="font-medium text-gray-900">{{ Auth::guard('member')->user()->chapter }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member Since:</span>
                        <span class="font-medium text-gray-900">
                            {{ Auth::guard('member')->user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Family Management -->
            <a href="{{ route('member.family.index') }}" class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-pink-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Family Management</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Manage your family members and relationships</p>
                <div class="flex items-center text-pink-600 text-sm font-medium">
                    <span>View Family</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>

            <!-- Help & Support -->
            <a href="{{ route('member.help.index') }}" class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-question-circle text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Help & Support</h3>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Get help and contact support</p>
                <div class="flex items-center text-indigo-600 text-sm font-medium">
                    <span>Get Help</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>

        </div>

        <!-- Logout Section -->
        <div class="mt-8 bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Actions</h3>
            <form method="POST" action="{{ route('member.logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div id="passwordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Change Password</h3>
            <button onclick="document.getElementById('passwordModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form method="POST" action="{{ route('member.password.change') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" onclick="document.getElementById('passwordModal').classList.add('hidden')" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Update Password
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
