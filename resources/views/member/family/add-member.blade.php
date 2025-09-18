@extends('member.layouts.app')

@section('title', 'Add Family Member')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('member.family.index') }}" class="mr-4 p-2 rounded-lg bg-white/80 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <i class="fas fa-arrow-left text-blue-600"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add Family Member</h1>
                    <p class="text-gray-600">Add a new member to your family</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Family Member Information</h2>
                        <p class="text-blue-100">Fill in the details below</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('member.family.store-member') }}" method="POST" class="p-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>First Name *
                        </label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" 
                               required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Last Name *
                        </label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" 
                               required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middle_name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-400"></i>Middle Name
                        </label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                        @error('middle_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-green-600"></i>Phone Number
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-purple-600"></i>Date of Birth
                        </label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" 
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-2 text-pink-600"></i>Gender *
                        </label>
                        <select id="gender" name="gender" 
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" 
                                required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Relationship -->
                    <div>
                        <label for="relationship_to_head" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-heart mr-2 text-red-600"></i>Relationship *
                        </label>
                        <select id="relationship_to_head" name="relationship_to_head" 
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" 
                                required>
                            <option value="">Select Relationship</option>
                            <option value="spouse" {{ old('relationship_to_head') === 'spouse' ? 'selected' : '' }}>Spouse</option>
                            <option value="child" {{ old('relationship_to_head') === 'child' ? 'selected' : '' }}>Child</option>
                            <option value="parent" {{ old('relationship_to_head') === 'parent' ? 'selected' : '' }}>Parent</option>
                            <option value="sibling" {{ old('relationship_to_head') === 'sibling' ? 'selected' : '' }}>Sibling</option>
                            <option value="other" {{ old('relationship_to_head') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('relationship_to_head')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Membership Status -->
                    <div>
                        <label for="membership_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-church mr-2 text-indigo-600"></i>Membership Status *
                        </label>
                        <select id="membership_status" name="membership_status" 
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" 
                                required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('membership_status') === 'active' ? 'selected' : '' }}>Active Member</option>
                            <option value="inactive" {{ old('membership_status') === 'inactive' ? 'selected' : '' }}>Inactive Member</option>
                            <option value="pending" {{ old('membership_status') === 'pending' ? 'selected' : '' }}>Pending Membership</option>
                        </select>
                        @error('membership_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Membership Type -->
                    <div>
                        <label for="membership_type" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-orange-600"></i>Membership Type
                        </label>
                        <select id="membership_type" name="membership_type" 
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300">
                            <option value="">Select Type</option>
                            <option value="full" {{ old('membership_type') === 'full' ? 'selected' : '' }}>Full Member</option>
                            <option value="associate" {{ old('membership_type') === 'associate' ? 'selected' : '' }}>Associate Member</option>
                            <option value="youth" {{ old('membership_type') === 'youth' ? 'selected' : '' }}>Youth Member</option>
                            <option value="child" {{ old('membership_type') === 'child' ? 'selected' : '' }}>Child Member</option>
                        </select>
                        @error('membership_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 px-8 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-plus mr-3"></i>
                        Add Family Member
                        <i class="fas fa-arrow-right ml-3"></i>
                    </button>
                    
                    <a href="{{ route('member.family.index') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-4 px-8 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-times mr-3"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
