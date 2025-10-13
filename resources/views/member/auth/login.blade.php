<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white opacity-5 rounded-full floating-animation"></div>
        <div class="absolute top-3/4 right-1/4 w-48 h-48 bg-white opacity-5 rounded-full floating-animation" style="animation-delay: -2s;"></div>
        <div class="absolute top-1/2 right-1/3 w-32 h-32 bg-white opacity-5 rounded-full floating-animation" style="animation-delay: -4s;"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Church Logo/Header -->
        <div class="text-center mb-8">
            @php
                $orgLogo = \App\Models\Setting::getValue('organization_logo', 'general');
                $orgName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family');
                $orgSlogan = \App\Models\Setting::getValue('organization_slogan', 'general');
            @endphp
            
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                @if($orgLogo)
                    <img src="{{ asset('storage/' . $orgLogo) }}" alt="{{ $orgName }} Logo" class="w-16 h-16 object-contain rounded-full">
                @else
                    <i class="fas fa-church text-3xl text-indigo-600"></i>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $orgName }}</h1>
            <p class="text-indigo-100">{{ $orgSlogan ?: 'Member Portal' }}</p>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                <p class="text-gray-600">Sign in to your member account</p>
            </div>

            <form method="POST" action="{{ route('member.login') }}" class="space-y-6">
                @csrf
                
                <!-- Login Field (Member ID or Email) -->
                <div class="space-y-2">
                    <label for="login" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user mr-2 text-indigo-600"></i>Member ID or Email
                    </label>
                    <input type="text" 
                           id="login" 
                           name="login" 
                           value="{{ old('login') }}"
                           required 
                           autofocus
                           placeholder="Enter your Member ID or Email"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('login') border-red-500 @enderror">
                    @error('login')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-lock mr-2 text-indigo-600"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="Enter your password"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('password') border-red-500 @enderror">
                        <button type="button" 
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 transition-colors">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-sm text-gray-500">New Member?</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <!-- Registration Section -->
            <div class="text-center space-y-4">
                <a href="{{ route('member.register') }}" 
                   class="block w-full bg-white border-2 border-indigo-600 text-indigo-600 py-3 px-4 rounded-lg font-medium hover:bg-indigo-50 transition-all duration-200">
                    <i class="fas fa-user-plus mr-2"></i>Register as a New Member
                </a>
                
                <div class="flex justify-center space-x-6 text-sm">
                    <a href="tel:+1234567890" class="text-indigo-100 hover:text-white transition-colors">
                        <i class="fas fa-phone mr-1"></i>Call Office
                    </a>
                    <a href="mailto:info@beulahfamily.org" class="text-indigo-100 hover:text-white transition-colors">
                        <i class="fas fa-envelope mr-1"></i>Email Us
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-indigo-100">
            <p class="text-sm">
                © {{ date('Y') }} {{ $orgName }}. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `transform transition-all duration-500 ease-in-out translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
            
            toast.innerHTML = `
                <div class="${bgColor} text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3 min-w-[300px] max-w-md">
                    <i class="fas ${icon} text-2xl"></i>
                    <div class="flex-1">
                        <p class="font-semibold">${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : type === 'warning' ? 'Warning!' : 'Info'}</p>
                        <p class="text-sm opacity-90">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 5000);
        }

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-focus on login field and show toasts
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('login').focus();
            
            // Show toast messages
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif

            @if(session('warning'))
                showToast("{{ session('warning') }}", 'warning');
            @endif
        });

        // Form validation feedback
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
