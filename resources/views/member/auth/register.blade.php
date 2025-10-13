<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration - {{ \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family') }}</title>
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
<body class="min-h-screen gradient-bg py-8 px-4">
    <div class="max-w-4xl mx-auto">
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
            <p class="text-indigo-100">Member Registration</p>
        </div>

        <!-- Registration Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Register as a Member</h2>
                <p class="text-gray-600">Fill in your details below. Your account will be reviewed by our administrators.</p>
            </div>

            <form method="POST" action="{{ route('member.register') }}" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-user-circle mr-2 text-indigo-600"></i>Personal Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('last_name') border-red-500 @enderror">
                            @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-address-book mr-2 text-indigo-600"></i>Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required 
                                   placeholder="+233241234567"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('phone') border-red-500 @enderror">
                            @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Phone</label>
                            <input type="text" name="whatsapp_phone" value="{{ old('whatsapp_phone') }}" 
                                   placeholder="+233241234567"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-id-card mr-2 text-indigo-600"></i>Personal Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('date_of_birth') border-red-500 @enderror">
                            @error('date_of_birth')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                            <select name="gender" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                            <select name="marital_status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Status</option>
                                <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i>Location & Chapter
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State/Region</label>
                            <input type="text" name="state" value="{{ old('state') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" value="{{ old('country', 'Ghana') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Chapter *</label>
                            <select name="chapter" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('chapter') border-red-500 @enderror">
                                <option value="">Select Chapter</option>
                                <option value="ACCRA" {{ old('chapter') == 'ACCRA' ? 'selected' : '' }}>ACCRA</option>
                                <option value="KUMASI" {{ old('chapter') == 'KUMASI' ? 'selected' : '' }}>KUMASI</option>
                                <option value="NEW JESSY" {{ old('chapter') == 'NEW JESSY' ? 'selected' : '' }}>NEW JESSY</option>
                                <option value="STUDENTS" {{ old('chapter') == 'STUDENTS' ? 'selected' : '' }}>STUDENTS</option>
                            </select>
                            @error('chapter')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Membership Information -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-id-badge mr-2 text-indigo-600"></i>Membership Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Membership Type *</label>
                            <select name="membership_type" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 @error('membership_type') border-red-500 @enderror">
                                <option value="">Select Membership Type</option>
                                <option value="member" {{ old('membership_type', 'member') == 'member' ? 'selected' : '' }}>Member</option>
                                <option value="visitor" {{ old('membership_type') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                <option value="friend" {{ old('membership_type') == 'friend' ? 'selected' : '' }}>Friend</option>
                                <option value="associate" {{ old('membership_type') == 'associate' ? 'selected' : '' }}>Associate</option>
                            </select>
                            @error('membership_type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1">Choose the type of membership you're applying for</p>
                        </div>
                    </div>
                </div>

                <!-- Occupation -->
                <div class="border-b pb-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        <i class="fas fa-briefcase mr-2 text-indigo-600"></i>Occupation (Optional)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                            <input type="text" name="occupation" value="{{ old('occupation') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Note:</strong> After submitting your registration, you will receive an email with your default password. 
                        Your account will be activated once approved by an administrator. You will be required to change your password on first login.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Submit Registration
                    </button>
                    <a href="{{ route('member.login') }}" 
                       class="flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 text-center transition-all duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Already Registered? Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-indigo-100">
            <p class="text-sm">© {{ date('Y') }} {{ $orgName }}. All rights reserved.</p>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Toast Notification Script -->
    <script>
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `transform transition-all duration-500 ease-in-out translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
            
            toast.innerHTML = `
                <div class="${bgColor} text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3 min-w-[300px] max-w-md">
                    <i class="fas ${icon} text-2xl"></i>
                    <div class="flex-1">
                        <p class="font-semibold">${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Info'}</p>
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

        // Show toast on page load if there's a success message
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('success') }}", 'success');
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast("{{ session('error') }}", 'error');
            });
        @endif
    </script>
</body>
</html>
