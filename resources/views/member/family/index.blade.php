@extends('member.layouts.app')

@section('title', 'Family & Connections')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Family & Connections</h1>
            <p class="text-gray-600">Manage your family members and church connections</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Family Member
        </button>
    </div>

    <!-- Family Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Family Members</p>
                    <p class="text-2xl font-bold text-gray-900">4</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-church text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Church Members</p>
                    <p class="text-2xl font-bold text-gray-900">3</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-heart text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Connections</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Family Members -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">My Family</h2>
                        <div class="flex space-x-2">
                            <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>All Members</option>
                                <option>Church Members</option>
                                <option>Non-Members</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Family Member Card -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-gray-500 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Jane Doe</h3>
                                    <p class="text-sm text-gray-600">Spouse</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500 mt-2">
                                        <span><i class="fas fa-phone mr-1"></i>+233 24 123 4568</span>
                                        <span><i class="fas fa-envelope mr-1"></i>jane.doe@example.com</span>
                                        <span><i class="fas fa-birthday-cake mr-1"></i>June 20</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                    Church Member
                                </span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">Member ID: <span class="font-medium">20240002</span></span>
                                <span class="text-sm text-gray-600">Active since: <span class="font-medium">Jan 2020</span></span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Profile
                                </button>
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Family Member Card -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-child text-gray-500 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Michael Doe</h3>
                                    <p class="text-sm text-gray-600">Son</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500 mt-2">
                                        <span><i class="fas fa-birthday-cake mr-1"></i>Age 12</span>
                                        <span><i class="fas fa-graduation-cap mr-1"></i>Grade 7</span>
                                        <span><i class="fas fa-calendar mr-1"></i>March 10</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                    Youth Member
                                </span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">Attends: <span class="font-medium">Children's Church</span></span>
                                <span class="text-sm text-gray-600">Active since: <span class="font-medium">Jan 2022</span></span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Activities
                                </button>
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Family Member Card -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-female text-gray-500 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Sarah Doe</h3>
                                    <p class="text-sm text-gray-600">Daughter</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500 mt-2">
                                        <span><i class="fas fa-birthday-cake mr-1"></i>Age 8</span>
                                        <span><i class="fas fa-graduation-cap mr-1"></i>Grade 3</span>
                                        <span><i class="fas fa-calendar mr-1"></i>September 5</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded">
                                    Not Member
                                </span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-600">Attends: <span class="font-medium">Sunday School</span></span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Register as Member
                                </button>
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Family Member Card -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-plus text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-2">Add Family Member</h3>
                        <p class="text-sm text-gray-600 mb-4">Add spouse, children, or other family members to your profile</p>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Add Member
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Family Activities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-calendar-alt text-green-600 mr-2"></i>Family Activities
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Family Service Day</div>
                            <div class="text-xs text-gray-500">Sunday after service</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Children's Program</div>
                            <div class="text-xs text-gray-500">Every Sunday 9:00 AM</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Family Fellowship</div>
                            <div class="text-xs text-gray-500">First Saturday monthly</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Church Connections -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-users text-blue-600 mr-2"></i>Church Connections
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gray-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Pastor Emmanuel</div>
                            <div class="text-xs text-gray-500">Senior Pastor</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gray-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Sister Grace</div>
                            <div class="text-xs text-gray-500">Children's Ministry Leader</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-gray-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Brother David</div>
                            <div class="text-xs text-gray-500">Worship Leader</div>
                        </div>
                    </div>
                    <button class="w-full text-blue-600 hover:text-blue-800 text-sm font-medium mt-3">
                        View All Connections
                    </button>
                </div>
            </div>

            <!-- Family Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>Family Stats
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Family Donations</span>
                        <span class="font-medium">₵2,450.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Events Attended</span>
                        <span class="font-medium">28</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Ministry Involvement</span>
                        <span class="font-medium">5 Ministries</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Years as Members</span>
                        <span class="font-medium">4 Years</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>Quick Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Family Member
                    </button>
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-calendar-plus mr-2"></i>Schedule Family Event
                    </button>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-heart mr-2"></i>Family Donation
                    </button>
                </div>
            </div>

            <!-- Family Directory -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-address-book text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900 mb-2">Church Directory</h4>
                        <p class="text-sm text-blue-700 mb-3">Connect with other church families and build meaningful relationships.</p>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Browse Directory
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
