@extends('member.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-600">Manage your account preferences and privacy settings</p>
    </div>

    <div class="space-y-6">
        <!-- Account Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user-cog text-blue-600 mr-2"></i>Account Settings
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Profile Photo -->
                <div class="flex items-center">
                    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mr-6">
                        <i class="fas fa-user text-gray-500 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Profile Photo</h3>
                        <p class="text-sm text-gray-600 mb-3">Update your profile picture</p>
                        <div class="flex space-x-3">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Upload Photo
                            </button>
                            <button class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                        <input type="text" value="John Doe" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" value="john.doe@example.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" value="+233 24 123 4567" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                        <input type="tel" value="+233 24 123 4567" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Communication Preferences -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-bell text-green-600 mr-2"></i>Communication Preferences
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Email Notifications -->
                <div>
                    <h3 class="font-medium text-gray-900 mb-4">Email Notifications</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Church announcements and updates</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Event invitations and reminders</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Ministry updates and opportunities</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Donation receipts and confirmations</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Newsletter and devotionals</span>
                        </label>
                    </div>
                </div>

                <!-- SMS Notifications -->
                <div>
                    <h3 class="font-medium text-gray-900 mb-4">SMS Notifications</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Emergency alerts and urgent announcements</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Event reminders (24 hours before)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Ministry schedule changes</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Prayer requests</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Update Preferences
                    </button>
                </div>
            </div>
        </div>

        <!-- Privacy Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-shield-alt text-purple-600 mr-2"></i>Privacy Settings
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Profile Visibility -->
                <div>
                    <h3 class="font-medium text-gray-900 mb-4">Profile Visibility</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="profile_visibility" value="public" checked class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Public - Visible to all church members</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="profile_visibility" value="limited" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-gray-700">Limited - Visible to ministry members only</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="profile_visibility" value="private" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Private - Visible to leadership only</span>
                        </label>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <h3 class="font-medium text-gray-900 mb-4">Contact Information Sharing</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Allow other members to see my phone number</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Allow other members to see my email address</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Include me in the church directory</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Allow ministry leaders to contact me directly</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Save Privacy Settings
                    </button>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-lock text-red-600 mr-2"></i>Security Settings
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Password -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">Password</h3>
                        <p class="text-sm text-gray-600">Last changed 3 months ago</p>
                    </div>
                    <a href="{{ route('member.profile') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Change Password
                    </a>
                </div>

                <!-- Two-Factor Authentication -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">Two-Factor Authentication</h3>
                        <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                    </div>
                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Enable 2FA
                    </button>
                </div>

                <!-- Login Activity -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">Login Activity</h3>
                        <p class="text-sm text-gray-600">View recent login attempts and active sessions</p>
                    </div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View Activity
                    </button>
                </div>
            </div>
        </div>

        <!-- Data & Export -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-download text-indigo-600 mr-2"></i>Data & Export
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Data Export -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">Export My Data</h3>
                        <p class="text-sm text-gray-600">Download a copy of your personal data and activity</p>
                    </div>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Request Export
                    </button>
                </div>

                <!-- Donation History -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">Donation History</h3>
                        <p class="text-sm text-gray-600">Export your complete donation records for tax purposes</p>
                    </div>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Export Donations
                    </button>
                </div>
            </div>
        </div>

        <!-- Account Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>Account Actions
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Deactivate Account -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">Deactivate Account</h3>
                        <p class="text-sm text-gray-600">Temporarily disable your account access</p>
                    </div>
                    <button class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Deactivate
                    </button>
                </div>

                <!-- Delete Account -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">Delete Account</h3>
                        <p class="text-sm text-gray-600">Permanently delete your account and all associated data</p>
                    </div>
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                const successDiv = document.createElement('div');
                successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                successDiv.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Settings updated successfully!';
                
                this.insertBefore(successDiv, this.firstChild);
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Remove success message after 3 seconds
                setTimeout(() => {
                    successDiv.remove();
                }, 3000);
            }, 1000);
        });
    });
    
    // Handle dangerous actions with confirmation
    const dangerousButtons = document.querySelectorAll('.bg-red-600, .bg-orange-600');
    dangerousButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const action = this.textContent.trim();
            const confirmed = confirm(`Are you sure you want to ${action.toLowerCase()}? This action cannot be undone.`);
            
            if (confirmed) {
                // Handle the action
                alert(`${action} functionality would be implemented here with proper backend integration.`);
            }
        });
    });
});
</script>
@endsection
