@extends('member.layouts.app')

@section('title', 'My Ministries')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Ministries</h1>
            <p class="text-gray-600">Serve God and the community through various ministry opportunities</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Join Ministry
        </button>
    </div>

    <!-- Ministry Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-hands-helping text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Active Ministries</p>
                    <p class="text-2xl font-bold text-gray-900">3</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Hours Served</p>
                    <p class="text-2xl font-bold text-gray-900">48</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-star text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Leadership Roles</p>
                    <p class="text-2xl font-bold text-gray-900">1</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-users text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">People Impacted</p>
                    <p class="text-2xl font-bold text-gray-900">125</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- My Active Ministries -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">My Active Ministries</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Ministry Item -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-music text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Worship Team</h3>
                                    <p class="text-sm text-gray-600 mb-2">Lead worship through music and song</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span><i class="fas fa-calendar mr-1"></i>Joined: Jan 2023</span>
                                        <span><i class="fas fa-users mr-1"></i>15 members</span>
                                        <span><i class="fas fa-clock mr-1"></i>4 hrs/week</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                    Active
                                </span>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                    Leader
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-sm text-gray-600">
                                    Next Service: <span class="font-medium">Sunday 9:00 AM</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Schedule
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Ministry Item -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-child text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Children's Ministry</h3>
                                    <p class="text-sm text-gray-600 mb-2">Teaching and caring for children during services</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span><i class="fas fa-calendar mr-1"></i>Joined: Mar 2023</span>
                                        <span><i class="fas fa-users mr-1"></i>8 members</span>
                                        <span><i class="fas fa-clock mr-1"></i>3 hrs/week</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                    Active
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-sm text-gray-600">
                                    Next Duty: <span class="font-medium">Sunday 9:00 AM</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Schedule
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Ministry Item -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-hands-helping text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Community Outreach</h3>
                                    <p class="text-sm text-gray-600 mb-2">Serving the local community through various programs</p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span><i class="fas fa-calendar mr-1"></i>Joined: Jun 2023</span>
                                        <span><i class="fas fa-users mr-1"></i>12 members</span>
                                        <span><i class="fas fa-clock mr-1"></i>2 hrs/week</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                    Active
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-sm text-gray-600">
                                    Next Event: <span class="font-medium">Dec 21 Food Drive</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Details
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Ministries -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Available Ministry Opportunities</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Available Ministry -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-heart text-red-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Prayer Ministry</h4>
                                    <p class="text-sm text-gray-600 mb-2">Intercede for church and community needs</p>
                                    <div class="text-xs text-gray-500 mb-3">
                                        <span><i class="fas fa-users mr-1"></i>6 members needed</span>
                                    </div>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Join Ministry
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Available Ministry -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-car text-yellow-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Transport Ministry</h4>
                                    <p class="text-sm text-gray-600 mb-2">Help members get to church services</p>
                                    <div class="text-xs text-gray-500 mb-3">
                                        <span><i class="fas fa-users mr-1"></i>4 members needed</span>
                                    </div>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Join Ministry
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Available Ministry -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-microphone text-indigo-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Media Ministry</h4>
                                    <p class="text-sm text-gray-600 mb-2">Audio/visual support for services</p>
                                    <div class="text-xs text-gray-500 mb-3">
                                        <span><i class="fas fa-users mr-1"></i>3 members needed</span>
                                    </div>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Join Ministry
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Available Ministry -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-female text-pink-600"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Women's Ministry</h4>
                                    <p class="text-sm text-gray-600 mb-2">Fellowship and support for women</p>
                                    <div class="text-xs text-gray-500 mb-3">
                                        <span><i class="fas fa-users mr-1"></i>Open enrollment</span>
                                    </div>
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                        Join Ministry
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Ministry Schedule -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>This Week's Schedule
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Worship Team Practice</div>
                            <div class="text-xs text-gray-500">Thursday 7:00 PM</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Sunday Service</div>
                            <div class="text-xs text-gray-500">Sunday 9:00 AM</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Children's Ministry</div>
                            <div class="text-xs text-gray-500">Sunday 9:00 AM</div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">Food Drive Setup</div>
                            <div class="text-xs text-gray-500">Saturday 8:00 AM</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ministry Impact -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>Your Impact
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">125</div>
                        <div class="text-sm text-gray-600">People Served</div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Worship Services Led</span>
                            <span class="font-medium">24</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Children Taught</span>
                            <span class="font-medium">45</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Community Events</span>
                            <span class="font-medium">8</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Volunteer Hours</span>
                            <span class="font-medium">48</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ministry Resources -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-book text-purple-600 mr-2"></i>Resources
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-file-pdf mr-2"></i>Ministry Handbook
                    </a>
                    <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-video mr-2"></i>Training Videos
                    </a>
                    <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-calendar mr-2"></i>Ministry Calendar
                    </a>
                    <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-users mr-2"></i>Team Directory
                    </a>
                    <a href="#" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-envelope mr-2"></i>Contact Leaders
                    </a>
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
                        <i class="fas fa-plus mr-2"></i>Join New Ministry
                    </button>
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-calendar-plus mr-2"></i>Schedule Availability
                    </button>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-comment mr-2"></i>Contact Coordinator
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
