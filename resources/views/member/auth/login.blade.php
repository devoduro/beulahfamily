<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Login - Beulah Family</title>
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
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-church text-3xl text-indigo-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Beulah Family</h1>
            <p class="text-indigo-100">Member Portal</p>
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
                <span class="px-4 text-sm text-gray-500">Need help?</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <!-- Help Section -->
            <div class="text-center space-y-3">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1 text-indigo-600"></i>
                    New member? Contact the church office for your login credentials.
                </p>
                <div class="flex justify-center space-x-6 text-sm">
                    <a href="tel:+1234567890" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-phone mr-1"></i>Call Office
                    </a>
                    <a href="mailto:info@beulahfamily.org" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-envelope mr-1"></i>Email Us
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-indigo-100">
            <p class="text-sm">
                © {{ date('Y') }} Beulah Family Church. All rights reserved.
            </p>
        </div>
    </div>

    <script>
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

        // Auto-focus on login field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('login').focus();
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
