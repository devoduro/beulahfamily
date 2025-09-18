@extends('components.app-layout')

@section('title', 'Add New Member')
@section('subtitle', 'Register a new church member')

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
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Add New Member</h1>
                     </div>
                    <p class="text-gray-600 text-lg">Complete the form below to register a new church member</p>
                </div>
                <a href="{{ route('members.index') }}" class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm text-gray-700 font-semibold rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-300 border border-gray-200/50">
                    <i class="fas fa-arrow-left mr-2 text-gray-500"></i>
                    Back to Members
                </a>
            </div>
        </div>

    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Personal Information -->
        <div class="group bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-500 hover:bg-white/80">
            <div class="flex items-center mb-8">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full animate-pulse"></div>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Personal Information</h2>
                    <p class="text-gray-500 text-sm mt-1">Basic member details and identification</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Photo Upload -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-800 mb-4">Profile Photo</label>
                    <div class="flex flex-col items-center">
                        <div class="relative group">
                            <div class="w-36 h-36 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-3xl flex items-center justify-center mb-6 overflow-hidden border-4 border-white shadow-xl group-hover:shadow-2xl transition-all duration-300">
                                <img id="photo-preview" src="" alt="Preview" class="w-full h-full object-cover hidden rounded-2xl">
                                <div id="photo-icon" class="flex flex-col items-center space-y-2">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-camera text-white text-xl"></i>
                                    </div>
                                    <span class="text-xs text-gray-500 font-medium">Add Photo</span>
                                </div>
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform duration-200" onclick="document.getElementById('photo').click()">
                                <i class="fas fa-plus text-white text-sm"></i>
                            </div>
                        </div>
                        <input type="file" name="photo" id="photo" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('photo').click()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-2xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl text-sm">
                            <i class="fas fa-upload mr-2"></i>Choose Photo
                        </button>
                    </div>
                </div>

                <!-- Name Fields -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-semibold text-gray-800">Title</label>
                        <div class="relative">
                            <select name="title" id="title" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                                <option value="">Select Title</option>
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Ms.">Ms.</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Rev.">Rev.</option>
                                <option value="Pastor">Pastor</option>
                                <option value="Prof.">Prof.</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="first_name" class="block text-sm font-semibold text-gray-800">
                            First Name 
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-1">Required</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="first_name" id="first_name" required class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md placeholder-gray-400" value="{{ old('first_name') }}" placeholder="Enter first name">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        @error('first_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="middle_name" class="block text-sm font-semibold text-gray-800">Middle Name</label>
                        <div class="relative">
                            <input type="text" name="middle_name" id="middle_name" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md placeholder-gray-400" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="last_name" class="block text-sm font-semibold text-gray-800">
                            Last Name 
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-1">Required</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="last_name" id="last_name" required class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md placeholder-gray-400" value="{{ old('last_name') }}" placeholder="Enter last name">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        @error('last_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-800">Date of Birth</label>
                        <div class="relative">
                            <input type="date" name="date_of_birth" id="date_of_birth" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('date_of_birth') }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="gender" class="block text-sm font-semibold text-gray-800">Gender</label>
                        <div class="relative">
                            <select name="gender" id="gender" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="marital_status" class="block text-sm font-semibold text-gray-800">Marital Status</label>
                        <div class="relative">
                            <select name="marital_status" id="marital_status" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                                <option value="">Select Status</option>
                                <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="church_affiliation" class="block text-sm font-semibold text-gray-800">Church Affiliation</label>
                        <div class="relative">
                            <select name="church_affiliation" id="church_affiliation" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                                <option value="">Select Affiliation</option>
                                <option value="Catholic" {{ old('church_affiliation') == 'Catholic' ? 'selected' : '' }}>Catholic</option>
                                <option value="Presbyterian" {{ old('church_affiliation') == 'Presbyterian' ? 'selected' : '' }}>Presbyterian</option>
                                <option value="Methodist" {{ old('church_affiliation') == 'Methodist' ? 'selected' : '' }}>Methodist</option>
                                <option value="Pentecostal" {{ old('church_affiliation') == 'Pentecostal' ? 'selected' : '' }}>Pentecostal</option>
                                <option value="Baptist" {{ old('church_affiliation') == 'Baptist' ? 'selected' : '' }}>Baptist</option>
                                <option value="Lutheran" {{ old('church_affiliation') == 'Lutheran' ? 'selected' : '' }}>Lutheran</option>
                                <option value="Episcopal" {{ old('church_affiliation') == 'Episcopal' ? 'selected' : '' }}>Episcopal</option>
                                <option value="Anglican" {{ old('church_affiliation') == 'Anglican' ? 'selected' : '' }}>Anglican</option>
                                <option value="Other" {{ old('church_affiliation') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="occupation" class="block text-sm font-semibold text-gray-800">Occupation / Profession</label>
                        <div class="relative">
                            <input type="text" name="occupation" id="occupation" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('occupation') }}" placeholder="e.g., Engineer, Teacher">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-briefcase text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="group bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-500 hover:bg-white/80">
            <div class="flex items-center mb-8">
                <div class="relative">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                        <i class="fas fa-phone text-white text-lg"></i>
                    </div>
                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full animate-pulse"></div>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Contact Information</h2>
                    <p class="text-gray-500 text-sm mt-1">Communication and address details</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-800">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('email') }}" placeholder="e.g., member@example.com">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="phone" class="block text-sm font-semibold text-gray-800">Primary Phone</label>
                    <div class="relative">
                        <input type="tel" name="phone" id="phone" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('phone') }}" placeholder="e.g., (123) 456-7890">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="whatsapp_phone" class="block text-sm font-semibold text-gray-800">WhatsApp Phone <span class="text-gray-500 font-normal">(if different)</span></label>
                    <div class="relative">
                        <input type="tel" name="whatsapp_phone" id="whatsapp_phone" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('whatsapp_phone') }}" placeholder="Enter WhatsApp number">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fab fa-whatsapp text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="employer" class="block text-sm font-semibold text-gray-800">Employer</label>
                    <div class="relative">
                        <input type="text" name="employer" id="employer" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('employer') }}" placeholder="e.g., Acme Corporation">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-building text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="address" class="block text-sm font-semibold text-gray-800">GPS Address/Location</label>
                    <textarea name="address" id="address" rows="3" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" placeholder="Enter street address">{{ old('address') }}</textarea>
                </div>

                <div class="space-y-2">
                    <label for="city" class="block text-sm font-semibold text-gray-800">City</label>
                    <input type="text" name="city" id="city" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('city') }}" placeholder="Enter city">
                </div>

                <div class="space-y-2">
                    <label for="state" class="block text-sm font-semibold text-gray-800">Region/State/Province</label>
                    <input type="text" name="state" id="state" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('state') }}" placeholder="Enter state">
                </div>

                <div class="space-y-2">
                    <label for="postal_code" class="block text-sm font-semibold text-gray-800">Postal Code</label>
                    <input type="text" name="postal_code" id="postal_code" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('postal_code') }}" placeholder="Enter postal code">
                </div>

                <div class="space-y-2">
                    <label for="country" class="block text-sm font-semibold text-gray-800">Country</label>
                    <input type="text" name="country" id="country" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" value="{{ old('country', 'United States') }}" placeholder="Enter country">
                </div>
            </div>
        </div>

        <!-- Family Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-home text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Family Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="family_id" class="block text-sm font-medium text-gray-700 mb-2">Family</label>
                    <select name="family_id" id="family_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Family or Create New</option>
                        @foreach($families ?? [] as $family)
                            <option value="{{ $family->id }}" {{ old('family_id') == $family->id ? 'selected' : '' }}>
                                {{ $family->family_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="relationship_to_head" class="block text-sm font-medium text-gray-700 mb-2">Relationship to Family Head</label>
                    <select name="relationship_to_head" id="relationship_to_head" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Relationship</option>
                        <option value="head" {{ old('relationship_to_head') == 'head' ? 'selected' : '' }}>Head of Family</option>
                        <option value="spouse" {{ old('relationship_to_head') == 'spouse' ? 'selected' : '' }}>Spouse</option>
                        <option value="child" {{ old('relationship_to_head') == 'child' ? 'selected' : '' }}>Child</option>
                        <option value="parent" {{ old('relationship_to_head') == 'parent' ? 'selected' : '' }}>Parent</option>
                        <option value="sibling" {{ old('relationship_to_head') == 'sibling' ? 'selected' : '' }}>Sibling</option>
                        <option value="relative" {{ old('relationship_to_head') == 'relative' ? 'selected' : '' }}>Other Relative</option>
                    </select>
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="membership_date" class="block text-sm font-medium text-gray-700 mb-2">Membership Date</label>
                    <input type="date" name="membership_date" id="membership_date" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('membership_date', date('Y-m-d')) }}">
                </div>

                <div>
                    <label for="membership_status" class="block text-sm font-medium text-gray-700 mb-2">Membership Status</label>
                    <select name="membership_status" id="membership_status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ old('membership_status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('membership_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="transferred" {{ old('membership_status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                        <option value="deceased" {{ old('membership_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                </div>

                <div>
                    <label for="membership_type" class="block text-sm font-medium text-gray-700 mb-2">Membership Type</label>
                    <select name="membership_type" id="membership_type" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="member" {{ old('membership_type', 'member') == 'member' ? 'selected' : '' }}>Member</option>
                        <option value="visitor" {{ old('membership_type') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                        <option value="friend" {{ old('membership_type') == 'friend' ? 'selected' : '' }}>Friend</option>
                        <option value="associate" {{ old('membership_type') == 'associate' ? 'selected' : '' }}>Associate</option>
                    </select>
                </div>

                <div>
                    <label for="chapter" class="block text-sm font-medium text-gray-700 mb-2">
                        Chapter <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="chapter" id="chapter" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" required>
                            <option value="">Select Chapter</option>
                            <option value="ACCRA" {{ old('chapter', 'ACCRA') == 'ACCRA' ? 'selected' : '' }}>ACCRA</option>
                            <option value="KUMASI" {{ old('chapter') == 'KUMASI' ? 'selected' : '' }}>KUMASI</option>
                            <option value="NEW JESSY" {{ old('chapter') == 'NEW JESSY' ? 'selected' : '' }}>NEW JESSY</option>
                            <option value="STUDENTS" {{ old('chapter') == 'STUDENTS' ? 'selected' : '' }}>STUDENTS</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                        </div>
                    </div>
                    @error('chapter')
                        <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Spiritual Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-cross text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Spiritual Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_baptized" id="is_baptized" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('is_baptized') ? 'checked' : '' }}>
                    <label for="is_baptized" class="ml-2 block text-sm text-gray-900">Baptized</label>
                </div>

                <div>
                    <label for="baptism_date" class="block text-sm font-medium text-gray-700 mb-2">Baptism Date</label>
                    <input type="date" name="baptism_date" id="baptism_date" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('baptism_date') }}">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_confirmed" id="is_confirmed" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('is_confirmed') ? 'checked' : '' }}>
                    <label for="is_confirmed" class="ml-2 block text-sm text-gray-900">Confirmed</label>
                </div>

                <div>
                    <label for="confirmation_date" class="block text-sm font-medium text-gray-700 mb-2">Confirmation Date</label>
                    <input type="date" name="confirmation_date" id="confirmation_date" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('confirmation_date') }}">
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Emergency Contact</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Name</label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('emergency_contact_name') }}">
                </div>

                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Phone</label>
                    <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('emergency_contact_phone') }}">
                </div>

                <div class="md:col-span-2">
                    <label for="medical_conditions" class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions / Allergies</label>
                    <textarea name="medical_conditions" id="medical_conditions" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="List any medical conditions, allergies, or special needs...">{{ old('medical_conditions') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Additional Information</h2>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="skills_talents" class="block text-sm font-medium text-gray-700 mb-2">Skills & Talents</label>
                    <textarea name="skills_talents" id="skills_talents" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="List skills, talents, or areas of expertise...">{{ old('skills_talents') }}</textarea>
                </div>

                <div>
                    <label for="interests" class="block text-sm font-medium text-gray-700 mb-2">Interests & Hobbies</label>
                    <textarea name="interests" id="interests" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="List interests, hobbies, or ministry preferences...">{{ old('interests') }}</textarea>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Any additional notes or comments...">{{ old('notes') }}</textarea>
                </div>

                <!-- Communication Preferences -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Communication Preferences</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="receive_newsletter" id="receive_newsletter" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('receive_newsletter', true) ? 'checked' : '' }}>
                            <label for="receive_newsletter" class="ml-2 block text-sm text-gray-900">Receive email newsletters and announcements</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="receive_sms" id="receive_sms" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('receive_sms') ? 'checked' : '' }}>
                            <label for="receive_sms" class="ml-2 block text-sm text-gray-900">Receive SMS notifications for urgent announcements</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-6 pt-8">
            <a href="{{ route('members.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white/80 backdrop-blur-sm text-gray-700 font-semibold rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-300 border border-gray-200/50">
                <i class="fas fa-times mr-2 text-gray-500"></i>
                Cancel
            </a>
            <button type="submit" id="submit-btn" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-user-plus mr-3" id="submit-icon"></i>
                <span id="submit-text">Add Member</span>
                <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
            </button>
        </div>
    </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo preview functionality
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');
    const photoIcon = document.getElementById('photo-icon');

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.classList.remove('hidden');
                photoIcon.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Auto-enable baptism/confirmation date fields
    document.getElementById('is_baptized').addEventListener('change', function() {
        document.getElementById('baptism_date').disabled = !this.checked;
    });

    document.getElementById('is_confirmed').addEventListener('change', function() {
        document.getElementById('confirmation_date').disabled = !this.checked;
    });

    // Form submission enhancement
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const submitIcon = document.getElementById('submit-icon');
    const submitText = document.getElementById('submit-text');

    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitIcon.className = 'fas fa-spinner fa-spin mr-3';
        submitText.textContent = 'Creating Member...';
        
        // Scroll to top to show any error messages
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Form validation feedback
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-200');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            }
        });
    });
});
</script>
@endsection
