<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Beulah Family Church') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .church-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
        <!-- Sidebar -->
        <aside 
            x-cloak
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:shadow-none md:min-h-screen md:w-64 flex-shrink-0"
        >
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 church-gradient rounded-lg flex items-center justify-center">
                        <i class="fas fa-church text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold gradient-text">Beulah Family</h1>
                        <p class="text-xs text-gray-500">Church Management</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4 space-y-2">
                
                @if(auth()->user()->role === 'admin')
                <!-- Church Management Section -->
                <div class="mb-6">
                    <div class="px-2 mb-3">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Church Management</h3>
                    </div>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-tachometer-alt text-white text-sm"></i>
                        </div>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('members.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 rounded-xl {{ request()->routeIs('members.*') ? 'bg-blue-50 text-blue-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                        <span>Members</span>
                    </a>
                    
                    <a href="{{ route('families.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-green-50 hover:text-green-600 rounded-xl {{ request()->routeIs('families.*') ? 'bg-green-50 text-green-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                            <i class="fas fa-home text-white text-sm"></i>
                        </div>
                        <span>Families</span>
                    </a>
                    
                    <a href="{{ route('ministries.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-purple-50 hover:text-purple-600 rounded-xl {{ request()->routeIs('ministries.*') ? 'bg-purple-50 text-purple-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            <i class="fas fa-hands-praying text-white text-sm"></i>
                        </div>
                        <span>Ministries</span>
                    </a>
                    
                    <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-orange-50 hover:text-orange-600 rounded-xl {{ request()->routeIs('events.*') ? 'bg-orange-50 text-orange-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-sm"></i>
                        </div>
                        <span>Events</span>
                    </a>
                    
                    <a href="{{ route('donations.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-yellow-50 hover:text-yellow-600 rounded-xl {{ request()->routeIs('donations.*') ? 'bg-yellow-50 text-yellow-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart text-white text-sm"></i>
                        </div>
                        <span>Donations</span>
                    </a>
                    
                    <a href="{{ route('donations.create') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl {{ request()->routeIs('donations.create') ? 'bg-emerald-50 text-emerald-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center">
                            <i class="fas fa-credit-card text-white text-sm"></i>
                        </div>
                        <span>Make Donation</span>
                    </a>
                    
                    <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-teal-50 hover:text-teal-600 rounded-xl {{ request()->routeIs('announcements.*') ? 'bg-teal-50 text-teal-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center">
                            <i class="fas fa-bullhorn text-white text-sm"></i>
                        </div>
                        <span>Announcements</span>
                    </a>
                    
                    <a href="{{ route('sms.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-violet-50 hover:text-violet-600 rounded-xl {{ request()->routeIs('sms.*') && !request()->routeIs('sms.templates.*') && !request()->routeIs('sms.credits.*') ? 'bg-violet-50 text-violet-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-sms text-white text-sm"></i>
                        </div>
                        <span>Bulk SMS</span>
                    </a>
                    
                    <a href="{{ route('message.templates.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 rounded-xl {{ request()->routeIs('message.templates.*') || request()->routeIs('sms.templates.*') ? 'bg-pink-50 text-pink-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center">
                            <i class="fas fa-file-alt text-white text-sm"></i>
                        </div>
                        <span>SMS Templates</span>
                    </a>
                    
                    <a href="{{ route('message.credits.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl {{ request()->routeIs('message.credits.*') || request()->routeIs('sms.credits.*') ? 'bg-emerald-50 text-emerald-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center">
                            <i class="fas fa-credit-card text-white text-sm"></i>
                        </div>
                        <span>SMS Credits</span>
                    </a>
                </div>

                <!-- Finance Management Section -->
                <div class="mb-6">
                    <div class="px-2 mb-3">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Finance</h3>
                    </div>
                    
                    <a href="{{ route('finance.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 rounded-xl {{ request()->routeIs('finance.*') ? 'bg-blue-50 text-blue-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-sm"></i>
                        </div>
                        <span>Finance Dashboard</span>
                    </a>
                    
                    <a href="{{ route('finance.transactions') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-purple-50 hover:text-purple-600 rounded-xl {{ request()->routeIs('finance.transactions') ? 'bg-purple-50 text-purple-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-white text-sm"></i>
                        </div>
                        <span>Transactions</span>
                    </a>
                    
                    <a href="{{ route('member-payments.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-green-50 hover:text-green-600 rounded-xl {{ request()->routeIs('member-payments.*') ? 'bg-green-50 text-green-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                            <i class="fas fa-hand-holding-usd text-white text-sm"></i>
                        </div>
                        <span>Member Payments</span>
                    </a>
                </div>

                <!-- Programs & Events Section -->
                <div class="mb-6">
                    <div class="px-2 mb-3">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Programs</h3>
                    </div>
                    
                    <a href="{{ route('admin.programs.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl {{ request()->routeIs('admin.programs.*') ? 'bg-indigo-50 text-indigo-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white text-sm"></i>
                        </div>
                        <span>Manage Programs</span>
                    </a>
                </div>

                <!-- Attendance Section -->
                <div class="mb-6">
                    <div class="px-2 mb-3">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Attendance</h3>
                    </div>
                    
                    <a href="{{ route('attendance.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-cyan-50 hover:text-cyan-600 rounded-xl {{ request()->routeIs('attendance.*') ? 'bg-cyan-50 text-cyan-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-qrcode text-white text-sm"></i>
                        </div>
                        <span>QR Attendance</span>
                    </a>
                </div>

                <!-- System Management Section -->
                <div class="mb-6">
                    <div class="px-2 mb-3">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System Management</h3>
                    </div>
                    
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-gray-50 hover:text-gray-700 rounded-xl {{ request()->routeIs('users.*') && !request()->routeIs('users.portal*') ? 'bg-gray-50 text-gray-700 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center">
                            <i class="fas fa-users-cog text-white text-sm"></i>
                        </div>
                        <span>User Management</span>
                    </a>
                    
                    <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-slate-50 hover:text-slate-600 rounded-xl {{ request()->routeIs('documents.*') ? 'bg-slate-50 text-slate-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center">
                            <i class="fas fa-file-alt text-white text-sm"></i>
                        </div>
                        <span>Documents</span>
                    </a>
                    
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-zinc-50 hover:text-zinc-600 rounded-xl {{ request()->routeIs('settings.*') && !request()->routeIs('settings.gateways.*') ? 'bg-zinc-50 text-zinc-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-zinc-500 to-zinc-600 flex items-center justify-center">
                            <i class="fas fa-cog text-white text-sm"></i>
                        </div>
                        <span>Settings</span>
                    </a>
                    
                    <a href="{{ route('system.config.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-orange-50 hover:text-orange-600 rounded-xl {{ request()->routeIs('system.config.*') ? 'bg-orange-50 text-orange-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                            <i class="fas fa-plug text-white text-sm"></i>
                        </div>
                        <span>Gateway Settings</span>
                    </a>
                    
                    <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-stone-50 hover:text-stone-600 rounded-xl {{ request()->routeIs('activity-logs.*') ? 'bg-stone-50 text-stone-600 font-medium shadow-sm' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-stone-500 to-stone-600 flex items-center justify-center">
                            <i class="fas fa-history text-white text-sm"></i>
                        </div>
                        <span>Activity Logs</span>
                    </a>
                </div>
                @else
                <!-- Staff Navigation -->
                <a href="{{ route('users.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl {{ request()->routeIs('users.dashboard') ? 'bg-indigo-50 text-indigo-600 font-medium shadow-sm' : '' }}">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white text-sm"></i>
                    </div>
                    <span>My Dashboard</span>
                </a>

                <a href="{{ route('users.portal') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 rounded-xl {{ request()->routeIs('users.portal*') && !request()->has('category') ? 'bg-blue-50 text-blue-600 font-medium shadow-sm' : '' }}">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-sm"></i>
                    </div>
                    <span>All Documents</span>
                </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navigation -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center justify-between h-14 px-4 md:px-6">
                    <!-- Mobile Menu Button -->
                    <button 
                        @click="sidebarOpen = !sidebarOpen" 
                        class="text-gray-600 md:hidden focus:outline-none"
                    >
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    
                    <!-- Page Title - Mobile -->
                    <div class="md:hidden font-semibold text-lg text-gray-800">
                        {{ $header ?? __('Dashboard') }}
                    </div>
                    
                    <!-- Search -->
                    <div class="hidden md:flex md:flex-1 md:max-w-md">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-gray-100 border-0 rounded-lg focus:bg-white focus:ring-2 focus:ring-primary-500 focus:outline-none" 
                                placeholder="Search..."
                            >
                        </div>
                    </div>
                    
                    <!-- Right Navigation -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open" 
                                class="p-2 text-gray-600 transition-colors duration-200 rounded-full hover:bg-gray-100 hover:text-primary-600 focus:outline-none"
                            >
                                <i class="fas fa-bell"></i>
                            </button>
                            
                            <!-- Dropdown -->
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                x-transition 
                                class="absolute right-0 z-10 w-80 mt-2 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg"
                            >
                                <div class="p-3 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <div class="p-4 text-sm text-gray-500">
                                        No new notifications
                                    </div>
                                </div>
                                <div class="p-2 border-t border-gray-200">
                                    <a href="#" class="block px-4 py-2 text-xs font-medium text-center text-primary-600 hover:underline">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open" 
                                class="flex items-center space-x-2 focus:outline-none"
                            >
                                <div class="w-8 h-8 overflow-hidden rounded-full bg-primary-100 flex items-center justify-center">
                                    <i class="fas fa-user text-primary-600"></i>
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'User' }}</span>
                                <i class="hidden md:block fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>
                            
                            <!-- Dropdown -->
                            <div 
                                x-show="open" 
                                @click.away="open = false" 
                                x-transition 
                                class="absolute right-0 z-10 w-48 mt-2 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg"
                            >
                                <div class="p-2">
                                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md">
                                        <i class="fas fa-user-circle mr-2"></i> Profile
                                    </a>
                                    <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md">
                                        <i class="fas fa-cog mr-2"></i> Settings
                                    </a>
                                    <hr class="my-1 border-gray-200">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 flex flex-col px-4 md:px-6 py-4">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="p-3 bg-green-50 border-l-4 border-green-500 text-green-700 flex justify-between items-center">
                        <div>
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                        <button @click="show = false" class="text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700 flex justify-between items-center">
                        <div>
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                        <button @click="show = false" class="text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                
                <!-- Page Header -->
                @if (isset($header))
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-2">
                        <div class="p-3 bg-white border-b border-gray-200">
                            {{ $header }}
                        </div>
                    </div>
                @else
                    <div class="py-2 mt-1">
                        <h1 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-500">@yield('subtitle', 'Welcome to the Church Management System')</p>
                    </div>
                @endif
                
                <!-- Content -->
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
            
            <!-- Footer -->
            <footer class="py-4 px-6 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} Church Management System. All rights reserved.
                    </p>
                    <div class="mt-2 md:mt-0">
                        <a href="#" class="text-sm text-gray-500 hover:text-primary-600 mr-4">Privacy Policy</a>
                        <a href="#" class="text-sm text-gray-500 hover:text-primary-600">Terms of Service</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Portal JavaScript -->
    <script>
        // Global CSRF token setup
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Global notification function
        window.showNotification = function(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="${icons[type] || icons.info} mr-3"></i>
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        };
    </script>
    
    @stack('scripts')
</body>
</html>
