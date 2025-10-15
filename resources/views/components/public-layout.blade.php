<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $pageOrgName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family');
    @endphp
    <title>@yield('title', $pageOrgName)</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        accent: {
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
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-bg-alt {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('programs.index') }}" class="flex items-center space-x-3">
                        @php
                            $orgLogo = \App\Models\Setting::getValue('organization_logo', 'general');
                            $orgName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family');
                        @endphp
                        @if($orgLogo)
                            <img src="{{ asset('storage/' . $orgLogo) }}" alt="{{ $orgName }}" class="h-10 w-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-church text-white text-lg"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $orgName }}</h1>
                            <p class="text-xs text-gray-600">Programs & Events</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('programs.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                        <i class="fas fa-graduation-cap mr-2"></i>Programs
                    </a>
                    <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                        <i class="fas fa-calendar-alt mr-2"></i>Events
                    </a>
                    <a href="{{ route('announcements.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                        <i class="fas fa-bullhorn mr-2"></i>Announcements
                    </a>
                    
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                                <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                            </a>
                        @endif
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-700">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-red-600 transition-colors">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Admin Login
                        </a>
                        <a href="{{ route('member.login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                            <i class="fas fa-user mr-2"></i>Member Login
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-blue-600 transition-colors" x-data x-on:click="$dispatch('toggle-mobile-menu')">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="md:hidden border-t border-gray-200" x-data="{ open: false }" x-on:toggle-mobile-menu.window="open = !open" x-show="open" x-cloak>
            <div class="px-4 py-3 space-y-3">
                <a href="{{ route('programs.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium">
                    <i class="fas fa-graduation-cap mr-2"></i>Programs
                </a>
                <a href="{{ route('events.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>Events
                </a>
                <a href="{{ route('announcements.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium">
                    <i class="fas fa-bullhorn mr-2"></i>Announcements
                </a>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                        </a>
                    @endif
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-gray-700 font-medium mb-2">{{ auth()->user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 transition-colors font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block text-gray-700 hover:text-blue-600 transition-colors font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Admin Login
                    </a>
                    <a href="{{ route('member.login') }}" class="block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors font-medium text-center">
                        <i class="fas fa-user mr-2"></i>Member Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 mx-4 mt-4 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="ml-auto text-green-600 hover:text-green-800" x-on:click="show = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 mx-4 mt-4 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="ml-auto text-red-600 hover:text-red-800" x-on:click="show = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 mx-4 mt-4 rounded-lg" x-data="{ show: true }" x-show="show">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle mr-3 mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-medium mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="ml-auto text-red-600 hover:text-red-800" x-on:click="show = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Church Info -->
                <div class="md:col-span-2">
                    @php
                        $footerLogo = \App\Models\Setting::getValue('organization_logo', 'general');
                        $footerName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family Church');
                        $footerSlogan = \App\Models\Setting::getValue('organization_slogan', 'general', 'Building Lives, Transforming Communities');
                    @endphp
                    <div class="flex items-center space-x-3 mb-4">
                        @if($footerLogo)
                            <img src="{{ asset('storage/' . $footerLogo) }}" alt="{{ $footerName }}" class="h-10 w-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-church text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold">{{ $footerName }}</h3>
                            <p class="text-gray-300">{{ $footerSlogan }}</p>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed">
                        Join us for our programs and events as we grow together in faith and fellowship. 
                        Everyone is welcome to participate in our community activities.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('programs.index') }}" class="text-gray-300 hover:text-white transition-colors">Programs</a></li>
                        <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white transition-colors">Events</a></li>
                        <li><a href="{{ route('announcements.index') }}" class="text-gray-300 hover:text-white transition-colors">Announcements</a></li>
                        <li><a href="{{ route('member.login') }}" class="text-gray-300 hover:text-white transition-colors">Member Portal</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">Admin Login</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                    <div class="space-y-2 text-gray-300">
                        <p><i class="fas fa-envelope mr-2"></i>info@beulahfamily.org</p>
                        <p><i class="fas fa-phone mr-2"></i>+233 XX XXX XXXX</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Accra, Ghana</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; {{ date('Y') }} {{ $footerName ?? 'Beulah Family Church' }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
