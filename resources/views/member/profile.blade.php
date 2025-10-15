@extends('member.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center overflow-hidden">
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}?v={{ time() }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user text-gray-400 text-2xl"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-white">{{ $member->full_name }}</h1>
                        <p class="text-blue-100">{{ $member->membership_type }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs bg-white/20 px-2 py-1 rounded text-white">
                                <i class="fas fa-id-card mr-1"></i>{{ $member->member_id }}
                            </span>
                            <span class="text-xs bg-white/20 px-2 py-1 rounded text-white">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $member->chapter }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                    Edit Profile
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update your personal information</p>
            </div>
            <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                <!-- Photo Upload Section -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">
                        <i class="fas fa-camera text-gray-500 mr-2"></i>Profile Photo
                    </h3>
                    <div class="flex items-center space-x-4">
                        <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            @if($member->photo)
                                <img id="photo-preview" src="{{ asset('storage/' . $member->photo) }}?v={{ time() }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                            @else
                                <img id="photo-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                <i id="photo-icon" class="fas fa-user text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                            <button type="button" onclick="document.getElementById('photo').click()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-upload mr-2"></i>Upload Photo
                            </button>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (Max 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm text-gray-700 mb-2">Title</label>
                            <select id="title" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Title</option>
                                <option value="Mr." {{ old('title', $member->title) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Mrs." {{ old('title', $member->title) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                <option value="Ms." {{ old('title', $member->title) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                <option value="Dr." {{ old('title', $member->title) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                                <option value="Rev." {{ old('title', $member->title) == 'Rev.' ? 'selected' : '' }}>Rev.</option>
                                <option value="Pastor" {{ old('title', $member->title) == 'Pastor' ? 'selected' : '' }}>Pastor</option>
                                <option value="Prof." {{ old('title', $member->title) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                            </select>
                        </div>

                        <div>
                            <label for="first_name" class="block text-sm text-gray-700 mb-2">First Name *</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $member->first_name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="middle_name" class="block text-sm text-gray-700 mb-2">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm text-gray-700 mb-2">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $member->last_name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm text-gray-700 mb-2">Gender</label>
                            <select id="gender" name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="marital_status" class="block text-sm text-gray-700 mb-2">Marital Status</label>
                            <select id="marital_status" name="marital_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Status</option>
                                <option value="single" {{ old('marital_status', $member->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status', $member->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status', $member->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status', $member->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $member->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="whatsapp_phone" class="block text-sm text-gray-700 mb-2">WhatsApp Number</label>
                            <input type="tel" id="whatsapp_phone" name="whatsapp_phone" value="{{ old('whatsapp_phone', $member->whatsapp_phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="alternate_phone" class="block text-sm text-gray-700 mb-2">Alternate Phone</label>
                            <input type="tel" id="alternate_phone" name="alternate_phone" value="{{ old('alternate_phone', $member->alternate_phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Address Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm text-gray-700 mb-2">Street Address</label>
                            <textarea id="address" name="address" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $member->address) }}</textarea>
                        </div>

                        <div>
                            <label for="city" class="block text-sm text-gray-700 mb-2">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city', $member->city) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="state" class="block text-sm text-gray-700 mb-2">State/Region</label>
                            <input type="text" id="state" name="state" value="{{ old('state', $member->state) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm text-gray-700 mb-2">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $member->postal_code) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="country" class="block text-sm text-gray-700 mb-2">Country</label>
                            <input type="text" id="country" name="country" value="{{ old('country', $member->country) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Professional Information (Optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="occupation" class="block text-sm text-gray-700 mb-2">Occupation</label>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $member->occupation) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="employer" class="block text-sm text-gray-700 mb-2">Employer</label>
                            <input type="text" id="employer" name="employer" value="{{ old('employer', $member->employer) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    const icon = document.getElementById('photo-icon');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (icon) {
                icon.classList.add('hidden');
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
