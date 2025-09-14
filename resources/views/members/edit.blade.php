@extends('components.app-layout')

@section('title', 'Edit Member')
@section('subtitle', 'Update member information')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Member: {{ $member->full_name ?? 'Member Name' }}</h1>
            <p class="text-gray-600 mt-1">Update member information and details</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('members.show', $member->id ?? 1) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-xl hover:bg-blue-200 transition-colors duration-200">
                <i class="fas fa-eye mr-2"></i>
                View Profile
            </a>
            <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Members
            </a>
        </div>
    </div>

    <form action="{{ route('members.update', $member->id ?? 1) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Personal Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-user text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Photo Upload -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    <div class="flex flex-col items-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-full flex items-center justify-center mb-4 overflow-hidden">
                            @if($member->photo_path ?? false)
                                <img id="photo-preview" src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                            @else
                                <img id="photo-preview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                <i id="photo-icon" class="fas fa-camera text-3xl text-blue-500"></i>
                            @endif
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('photo').click()" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-sm">
                            <i class="fas fa-upload mr-2"></i>Change Photo
                        </button>
                    </div>
                </div>

                <!-- Name Fields -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <select name="title" id="title" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Title</option>
                            <option value="Mr." {{ ($member->title ?? old('title')) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                            <option value="Mrs." {{ ($member->title ?? old('title')) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            <option value="Ms." {{ ($member->title ?? old('title')) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            <option value="Dr." {{ ($member->title ?? old('title')) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                            <option value="Rev." {{ ($member->title ?? old('title')) == 'Rev.' ? 'selected' : '' }}>Rev.</option>
                            <option value="Pastor" {{ ($member->title ?? old('title')) == 'Pastor' ? 'selected' : '' }}>Pastor</option>
                        </select>
                    </div>

                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="first_name" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->first_name ?? old('first_name') }}">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->middle_name ?? old('middle_name') }}">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="last_name" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->last_name ?? old('last_name') }}">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : old('date_of_birth') }}">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="gender" id="gender" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Gender</option>
                            <option value="male" {{ ($member->gender ?? old('gender')) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ ($member->gender ?? old('gender')) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div>
                        <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">Marital Status</label>
                        <select name="marital_status" id="marital_status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Status</option>
                            <option value="single" {{ ($member->marital_status ?? old('marital_status')) == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ ($member->marital_status ?? old('marital_status')) == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="divorced" {{ ($member->marital_status ?? old('marital_status')) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="widowed" {{ ($member->marital_status ?? old('marital_status')) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                        <input type="text" name="occupation" id="occupation" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->occupation ?? old('occupation') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-phone text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Contact Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->email ?? old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Primary Phone</label>
                    <input type="tel" name="phone" id="phone" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->phone ?? old('phone') }}">
                </div>

                <div>
                    <label for="alternate_phone" class="block text-sm font-medium text-gray-700 mb-2">Alternate Phone</label>
                    <input type="tel" name="alternate_phone" id="alternate_phone" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->alternate_phone ?? old('alternate_phone') }}">
                </div>

                <div>
                    <label for="employer" class="block text-sm font-medium text-gray-700 mb-2">Employer</label>
                    <input type="text" name="employer" id="employer" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->employer ?? old('employer') }}">
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">GPS Address/Location</label>
                    <textarea name="address" id="address" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $member->address ?? old('address') }}</textarea>
                </div>

                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" id="city" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->city ?? old('city') }}">
                </div>

                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">Region/State/Province</label>
                    <input type="text" name="state" id="state" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->state ?? old('state') }}">
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->postal_code ?? old('postal_code') }}">
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" name="country" id="country" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->country ?? old('country') }}">
                </div>
            </div>
        </div>

        <!-- Membership Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-id-card text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Membership Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">Member ID</label>
                    <input type="text" name="member_id" id="member_id" class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg" value="{{ $member->member_id ?? 'Auto-generated' }}" readonly>
                </div>

                <div>
                    <label for="membership_date" class="block text-sm font-medium text-gray-700 mb-2">Membership Date</label>
                    <input type="date" name="membership_date" id="membership_date" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $member->membership_date ? $member->membership_date->format('Y-m-d') : old('membership_date') }}">
                </div>

                <div>
                    <label for="chapter" class="block text-sm font-medium text-gray-700 mb-2">
                        Chapter <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="chapter" id="chapter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                            <option value="">Select Chapter</option>
                            <option value="ACCRA" {{ ($member->chapter ?? old('chapter')) == 'ACCRA' ? 'selected' : '' }}>ACCRA</option>
                            <option value="KUMASI" {{ ($member->chapter ?? old('chapter')) == 'KUMASI' ? 'selected' : '' }}>KUMASI</option>
                            <option value="NEW JESSY" {{ ($member->chapter ?? old('chapter')) == 'NEW JESSY' ? 'selected' : '' }}>NEW JESSY</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                    </div>
                    @error('chapter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="membership_status" class="block text-sm font-medium text-gray-700 mb-2">Membership Status</label>
                    <select name="membership_status" id="membership_status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ ($member->membership_status ?? old('membership_status')) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($member->membership_status ?? old('membership_status')) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="transferred" {{ ($member->membership_status ?? old('membership_status')) == 'transferred' ? 'selected' : '' }}>Transferred</option>
                        <option value="deceased" {{ ($member->membership_status ?? old('membership_status')) == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                </div>

                <div>
                    <label for="membership_type" class="block text-sm font-medium text-gray-700 mb-2">Membership Type</label>
                    <select name="membership_type" id="membership_type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="member" {{ (old('membership_type', $member->membership_type ?? 'member')) == 'member' ? 'selected' : '' }}>Member</option>
                        <option value="visitor" {{ (old('membership_type', $member->membership_type)) == 'visitor' ? 'selected' : '' }}>Visitor</option>
                        <option value="friend" {{ (old('membership_type', $member->membership_type)) == 'friend' ? 'selected' : '' }}>Friend</option>
                        <option value="associate" {{ (old('membership_type', $member->membership_type)) == 'associate' ? 'selected' : '' }}>Associate</option>
                    </select>
                    @error('membership_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="family_id" class="block text-sm font-medium text-gray-700 mb-2">Family</label>
                    <select name="family_id" id="family_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">No Family</option>
                        @foreach($families ?? [] as $family)
                            <option value="{{ $family->id }}" {{ ($member->family_id ?? old('family_id')) == $family->id ? 'selected' : '' }}>
                                {{ $family->family_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="relationship_to_head" class="block text-sm font-medium text-gray-700 mb-2">Relationship to Family Head</label>
                    <select name="relationship_to_head" id="relationship_to_head" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Relationship</option>
                        <option value="head" {{ ($member->relationship_to_head ?? old('relationship_to_head')) == 'head' ? 'selected' : '' }}>Head of Family</option>
                        <option value="spouse" {{ ($member->relationship_to_head ?? old('relationship_to_head')) == 'spouse' ? 'selected' : '' }}>Spouse</option>
                        <option value="child" {{ ($member->relationship_to_head ?? old('relationship_to_head')) == 'child' ? 'selected' : '' }}>Child</option>
                        <option value="parent" {{ ($member->relationship_to_head ?? old('relationship_to_head')) == 'parent' ? 'selected' : '' }}>Parent</option>
                        <option value="sibling" {{ ($member->relationship_to_head ?? old('relationship_to_head')) == 'sibling' ? 'selected' : '' }}>Sibling</option>
                        <option value="relative" {{ ($member->relationship_to_head ?? old('relationship_to_head')) == 'relative' ? 'selected' : '' }}>Other Relative</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-between pt-6">
            <button type="button" onclick="confirmDelete()" class="px-6 py-3 bg-red-100 text-red-700 font-medium rounded-xl hover:bg-red-200 transition-colors duration-200">
                <i class="fas fa-trash mr-2"></i>
                Delete Member
            </button>
            <div class="flex space-x-4">
                <a href="{{ route('members.show', $member->id ?? 1) }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" id="submit-btn" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2" id="submit-icon"></i>
                    <span id="submit-text">Update Member</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    <form id="delete-form" action="{{ route('members.destroy', $member->id ?? 1) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this member? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Form submission enhancement
    const form = document.querySelector('form[method="POST"]');
    const submitBtn = document.getElementById('submit-btn');
    const submitIcon = document.getElementById('submit-icon');
    const submitText = document.getElementById('submit-text');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Show loading state
            submitBtn.disabled = true;
            submitIcon.className = 'fas fa-spinner fa-spin mr-2';
            submitText.textContent = 'Updating...';
            
            // Scroll to top to show any error messages
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Form validation feedback
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            }
        });
    });

    // Photo preview functionality
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    
    if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
