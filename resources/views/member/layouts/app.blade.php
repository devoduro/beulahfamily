<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Portal') - Beulah Family</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile menu overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full lg:translate-x-0 sidebar-transition">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-20 px-6 gradient-bg">
            <div class="flex items-center space-x-3">
                @php
                    $orgLogo = \App\Models\Setting::getValue('organization_logo', 'general');
                    $orgName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family');
                @endphp
                @if($orgLogo)
                    <img src="{{ asset('storage/' . $orgLogo) }}" alt="{{ $orgName }}" class="h-12 w-12 rounded-lg object-cover">
                @else
                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-church text-indigo-600 text-xl"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-white font-bold text-base leading-tight">{{ $orgName }}</h1>
                    <p class="text-white/80 text-xs">Member Portal</p>
                </div>
            </div>
            <button id="close-sidebar" class="text-white lg:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Member Info -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                    @if(auth('member')->user()->photo)
                        <img src="{{ asset('storage/' . auth('member')->user()->photo) }}?v={{ time() }}" alt="{{ auth('member')->user()->full_name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-gray-500"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth('member')->user()->full_name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth('member')->user()->member_id }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 px-3">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('member.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>

                <!-- Profile -->
                <a href="{{ route('member.profile') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.profile*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-user mr-3"></i>
                    My Profile
                </a>

                <!-- Donations -->
                <a href="{{ route('member.donations.create') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.donations*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-hand-holding-heart mr-3"></i>
                    My Donations
                </a>

                <!-- Events -->
                <a href="{{ route('member.events.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.events*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    Events
                </a>

                <!-- Programs -->
                <a href="{{ route('programs.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('programs*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    Programs
                </a>

                <!-- Testimonies -->
                <a href="{{ route('member.testimonies.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.testimonies*') ? 'bg-amber-100 text-amber-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-heart mr-3"></i>
                    Testimonies
                </a>

                <!-- Prayer Requests -->
                <a href="{{ route('member.prayer-requests.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.prayer-requests*') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-praying-hands mr-3"></i>
                    Prayer Line
                </a>

                <!-- Divider -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Settings -->
                <a href="{{ route('member.settings.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.settings*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
                </a>

                <!-- Help -->
                <a href="{{ route('member.help.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('member.help*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">
                    <i class="fas fa-question-circle mr-3"></i>
                    Help & Support
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('member.logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="lg:pl-64">
        <!-- Top Navigation -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="text-gray-500 hover:text-gray-700 lg:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Page Title -->
                <div class="flex-1 lg:flex-none">
                    <h1 class="text-xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                </div>

                <!-- Top Navigation Items -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button id="profile-dropdown-button" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                @if(auth('member')->user()->photo_path)
                                    <img src="{{ asset('storage/' . auth('member')->user()->photo_path) }}" alt="{{ auth('member')->user()->full_name }}" class="w-6 h-6 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-gray-500 text-sm"></i>
                                @endif
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                            <div class="py-2">
                                <a href="{{ route('member.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-3"></i>Profile
                                </a>
                                <a href="{{ route('member.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-3"></i>Settings
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form method="POST" action="{{ route('member.logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main>
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('close-sidebar');
        const overlay = document.getElementById('mobile-menu-overlay');

        mobileMenuButton?.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        closeSidebar?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Profile dropdown functionality
        const profileDropdownButton = document.getElementById('profile-dropdown-button');
        const profileDropdown = document.getElementById('profile-dropdown');

        profileDropdownButton?.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            profileDropdown?.classList.add('hidden');
        });

        // Auto-hide flash messages
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
