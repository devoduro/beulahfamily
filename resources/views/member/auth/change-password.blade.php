<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}</title>
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
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        @php
            $orgName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family');
            $orgLogo = \App\Models\Setting::getValue('organization_logo', 'general');
        @endphp

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                @if($orgLogo)
                    <img src="{{ asset('storage/' . $orgLogo) }}" alt="{{ $orgName }} Logo" class="w-16 h-16 object-contain rounded-full">
                @else
                    <i class="fas fa-church text-3xl text-indigo-600"></i>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $orgName }}</h1>
            <p class="text-indigo-100">Change Your Password</p>
        </div>

        <!-- Password Change Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Password Change Required:</strong> For security reasons, you must change your password before continuing.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('warning') }}
                </div>
            @endif

            <form method="POST" action="{{ route('member.password.update') }}" class="space-y-6">
                @csrf

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-key mr-2 text-indigo-600"></i>Current Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               required
                               placeholder="Enter your current password"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('current_password') border-red-500 @enderror">
                        <button type="button" 
                                onclick="togglePassword('current_password', 'toggleIcon1')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-2 text-indigo-600"></i>New Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               minlength="8"
                               placeholder="Enter your new password (min 8 characters)"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                        <button type="button" 
                                onclick="togglePassword('password', 'toggleIcon2')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Password must be at least 8 characters and different from your current password.
                    </p>
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-2 text-indigo-600"></i>Confirm New Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               placeholder="Confirm your new password"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <button type="button" 
                                onclick="togglePassword('password_confirmation', 'toggleIcon3')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="toggleIcon3"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800 font-medium mb-2">
                        <i class="fas fa-shield-alt mr-2"></i>Password Requirements:
                    </p>
                    <ul class="text-sm text-blue-700 space-y-1 ml-6">
                        <li>• Minimum 8 characters</li>
                        <li>• Different from current password</li>
                        <li>• Must match confirmation</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-check mr-2"></i>Change Password
                </button>

                <!-- Logout Option -->
                <div class="text-center">
                    <form action="{{ route('member.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-800 transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout Instead
                        </button>
                    </form>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-indigo-100">
            <p class="text-sm">© {{ date('Y') }} {{ $orgName }}. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form validation feedback
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            
            if (password !== confirmation) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Changing Password...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
