@extends('member.layouts.app')

@section('title', 'My Profile')

@section('content')
<!-- Enhanced Background with Gradient -->
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full opacity-10 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-pink-400 to-rose-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-full opacity-5 animate-pulse" style="animation-delay: 4s"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Enhanced Profile Header -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 mb-12 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-600 p-8">
                <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
                    <div class="relative group">
                        <div class="w-32 h-32 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center shadow-2xl ring-4 ring-white/30">
                            @if($member->photo_path)
                                <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-28 h-28 rounded-2xl object-cover">
                            @else
                                <i class="fas fa-user text-white text-4xl"></i>
                            @endif
                        </div>
                        <button class="absolute -bottom-2 -right-2 w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center text-white hover:from-yellow-500 hover:to-orange-600 transition-all duration-300 shadow-xl group-hover:scale-110">
                            <i class="fas fa-camera text-lg"></i>
                        </button>
                        <div class="absolute -top-3 -left-3 w-8 h-8 bg-green-400 rounded-full animate-bounce"></div>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-4xl font-bold text-white mb-2">{{ $member->full_name }}</h1>
                        <p class="text-white/90 text-xl mb-4">{{ $member->membership_type }} Member</p>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-2xl text-sm font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30">
                                <i class="fas fa-check-circle mr-2 text-green-300"></i>{{ ucfirst($member->membership_status) }}
                            </span>
                            <span class="inline-flex items-center px-4 py-2 rounded-2xl text-sm font-medium bg-white/10 backdrop-blur-sm text-white/90">
                                <i class="fas fa-id-card mr-2 text-blue-300"></i>{{ $member->member_id }}
                            </span>
                            <span class="inline-flex items-center px-4 py-2 rounded-2xl text-sm font-medium bg-white/10 backdrop-blur-sm text-white/90">
                                <i class="fas fa-map-marker-alt mr-2 text-red-300"></i>{{ $member->chapter }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white/50 backdrop-blur-sm p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="group">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-heart text-white text-2xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">₵{{ number_format($member->total_donations ?? 0, 0) }}</div>
                        <div class="text-sm text-gray-600">Total Donations</div>
                    </div>
                    <div class="group">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-calendar-check text-white text-2xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ $member->eventAttendances->count() ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Events Attended</div>
                    </div>
                    <div class="group">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ $member->activeMinistries->count() ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Active Ministries</div>
                    </div>
                    <div class="group">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-clock text-white text-2xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">{{ $member->membership_date ? $member->membership_date->diffInYears(now()) : 0 }}</div>
                        <div class="text-sm text-gray-600">Years Member</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Enhanced Main Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                    <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                <i class="fas fa-user-edit text-white text-xl"></i>
                            </div>
                            Personal Information
                        </h2>
                        <p class="text-gray-600 mt-2">Update your personal details and preferences</p>
                    </div>
                    <form method="POST" action="{{ route('member.profile.update') }}" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Personal Details Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-user text-indigo-600 mr-3"></i>Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div class="group">
                                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-user-tag text-indigo-500 mr-2"></i>First Name *
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $member->first_name) }}" required
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300 @error('first_name') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-check text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                    @error('first_name')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="group">
                                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-user-tag text-indigo-500 mr-2"></i>Last Name *
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $member->last_name) }}" required
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300 @error('last_name') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-check text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                    @error('last_name')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Middle Name -->
                                <div class="group">
                                    <label for="middle_name" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-user-tag text-gray-500 mr-2"></i>Middle Name
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $member->middle_name) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <span class="text-xs text-gray-400">Optional</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="group">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>Email Address *
                                    </label>
                                    <div class="relative">
                                        <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}" required
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300 @error('email') border-red-500 @enderror">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-at text-blue-400"></i>
                                        </div>
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-phone text-green-600 mr-3"></i>Contact Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Phone -->
                                <div class="group">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-phone text-green-500 mr-2"></i>Phone Number
                                    </label>
                                    <div class="relative">
                                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $member->phone) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-mobile-alt text-green-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- WhatsApp Phone -->
                                <div class="group">
                                    <label for="whatsapp_phone" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fab fa-whatsapp text-green-500 mr-2"></i>WhatsApp Number
                                    </label>
                                    <div class="relative">
                                        <input type="tel" id="whatsapp_phone" name="whatsapp_phone" value="{{ old('whatsapp_phone', $member->whatsapp_phone) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fab fa-whatsapp text-green-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-briefcase text-purple-600 mr-3"></i>Professional Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Occupation -->
                                <div class="group">
                                    <label for="occupation" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-user-tie text-purple-500 mr-2"></i>Occupation
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $member->occupation) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-briefcase text-purple-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employer -->
                                <div class="group">
                                    <label for="employer" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-building text-purple-500 mr-2"></i>Employer
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="employer" name="employer" value="{{ old('employer', $member->employer) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-building text-purple-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-3"></i>Address Information
                        </h3>
                        <div class="space-y-6">
                            <!-- Address -->
                            <div class="group">
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-home text-red-500 mr-2"></i>Street Address
                                </label>
                                <div class="relative">
                                    <textarea id="address" name="address" rows="4"
                                              class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-red-500/20 focus:border-red-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300 resize-none"
                                              placeholder="Enter your complete address...">{{ old('address', $member->address) }}</textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                        <i class="fas fa-map text-red-400 mr-1"></i>Optional
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- City -->
                                <div class="group">
                                    <label for="city" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-city text-red-500 mr-2"></i>City
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="city" name="city" value="{{ old('city', $member->city) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-red-500/20 focus:border-red-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-city text-red-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- State -->
                                <div class="group">
                                    <label for="state" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-flag text-red-500 mr-2"></i>State/Region
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="state" name="state" value="{{ old('state', $member->state) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-red-500/20 focus:border-red-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-flag text-red-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Postal Code -->
                                <div class="group">
                                    <label for="postal_code" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-mail-bulk text-red-500 mr-2"></i>Postal Code
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $member->postal_code) }}"
                                               class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-red-500/20 focus:border-red-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                            <i class="fas fa-mail-bulk text-red-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-exclamation-triangle text-orange-600 mr-3"></i>Emergency Contact
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label for="emergency_contact_name" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-user-shield text-orange-500 mr-2"></i>Contact Name
                                </label>
                                <div class="relative">
                                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}"
                                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                        <i class="fas fa-user-shield text-orange-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="group">
                                <label for="emergency_contact_phone" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-phone-alt text-orange-500 mr-2"></i>Contact Phone
                                </label>
                                <div class="relative">
                                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}"
                                           class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                        <i class="fas fa-phone-alt text-orange-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Communication Preferences Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-bell text-yellow-600 mr-3"></i>Communication Preferences
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-100">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <input type="checkbox" name="receive_newsletter" value="1" {{ old('receive_newsletter', $member->receive_newsletter) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-6 h-6 border-2 border-blue-300 rounded-lg flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all duration-200">
                                                <i class="fas fa-check text-white text-sm opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-semibold text-gray-900">Email Newsletter</div>
                                            <div class="text-sm text-gray-600">Receive newsletter and email updates</div>
                                        </div>
                                    </div>
                                    <div class="text-blue-600">
                                        <i class="fas fa-envelope text-2xl"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-100">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <input type="checkbox" name="receive_sms" value="1" {{ old('receive_sms', $member->receive_sms) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-6 h-6 border-2 border-green-300 rounded-lg flex items-center justify-center peer-checked:border-green-600 peer-checked:bg-green-600 transition-all duration-200">
                                                <i class="fas fa-check text-white text-sm opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-semibold text-gray-900">SMS Notifications</div>
                                            <div class="text-sm text-gray-600">Receive SMS notifications and alerts</div>
                                        </div>
                                    </div>
                                    <div class="text-green-600">
                                        <i class="fas fa-sms text-2xl"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Submit Button -->
                    <div class="pt-8 border-t-2 border-gray-100">
                        <button type="submit" class="group relative w-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white py-6 px-8 rounded-2xl font-bold text-xl transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative flex items-center justify-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-save text-white"></i>
                                </div>
                                <span>Update Profile</span>
                                <i class="fas fa-arrow-right ml-4 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-500 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-indigo-500 mr-2"></i>
                                Your information is secure and encrypted
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enhanced Sidebar -->
        <div class="space-y-8">
            <!-- Change Password Card -->
            <div class="bg-gradient-to-br from-white via-gray-50 to-indigo-50 rounded-3xl shadow-xl border-2 border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-lock text-white"></i>
                        </div>
                        Change Password
                    </h3>
                    <p class="text-indigo-100 mt-2">Keep your account secure with a strong password</p>
                </div>
                <form method="POST" action="{{ route('member.password.change') }}" class="p-8">
                    @csrf
                    <div class="space-y-6">
                        <div class="group">
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-shield-alt text-gray-500 mr-2"></i>Current Password
                            </label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password" required
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i class="fas fa-eye-slash text-gray-400 cursor-pointer hover:text-indigo-500 transition-colors" onclick="togglePassword('current_password')"></i>
                                </div>
                            </div>
                        </div>
                        <div class="group">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-key text-green-500 mr-2"></i>New Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i class="fas fa-eye-slash text-gray-400 cursor-pointer hover:text-green-500 transition-colors" onclick="togglePassword('password')"></i>
                                </div>
                            </div>
                        </div>
                        <div class="group">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-check-double text-blue-500 mr-2"></i>Confirm Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                       class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <i class="fas fa-eye-slash text-gray-400 cursor-pointer hover:text-blue-500 transition-colors" onclick="togglePassword('password_confirmation')"></i>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="group w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-4 px-6 rounded-2xl font-bold transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <div class="flex items-center justify-center">
                                <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-key text-white text-sm"></i>
                                </div>
                                <span>Update Password</span>
                                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Enhanced Member Stats Card -->
            <div class="bg-gradient-to-br from-white via-purple-50 to-pink-50 rounded-3xl shadow-xl border-2 border-purple-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        Member Stats
                    </h3>
                    <p class="text-purple-100 mt-2">Your membership journey at a glance</p>
                </div>
                <div class="p-8">
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border-2 border-blue-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-700">Member Since</div>
                                        <div class="text-xs text-gray-500">Joined the family</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-blue-700">{{ $member->created_at->format('M Y') }}</div>
                                    <div class="text-xs text-blue-500">{{ $member->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-4 border-2 border-green-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check-circle text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-700">Status</div>
                                        <div class="text-xs text-gray-500">Current membership</div>
                                    </div>
                                </div>
                                <div class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-bold shadow-lg">
                                    {{ ucfirst($member->status ?? 'Active') }}
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-2xl p-4 border-2 border-orange-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-semibold text-gray-700">Chapter</div>
                                        <div class="text-xs text-gray-500">Your local chapter</div>
                                    </div>
                                </div>
                                <div class="px-4 py-2 bg-orange-500 text-white rounded-full text-sm font-bold shadow-lg">
                                    {{ $member->chapter ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Quick Links Card -->
            <div class="bg-gradient-to-br from-white via-green-50 to-blue-50 rounded-3xl shadow-xl border-2 border-green-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-blue-600 p-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        Quick Links
                    </h3>
                    <p class="text-green-100 mt-2">Navigate to your favorite sections</p>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <a href="{{ route('member.donations.index') }}" class="group flex items-center p-4 rounded-2xl bg-gradient-to-r from-red-50 to-pink-50 border-2 border-red-100 hover:border-red-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-heart text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">My Donations</div>
                                <div class="text-sm text-gray-600">View donation history</div>
                            </div>
                            <i class="fas fa-arrow-right text-red-500 group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                        <a href="{{ route('member.events.index') }}" class="group flex items-center p-4 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-100 hover:border-blue-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-calendar text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">My Events</div>
                                <div class="text-sm text-gray-600">Manage event registrations</div>
                            </div>
                            <i class="fas fa-arrow-right text-blue-500 group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                        <a href="{{ route('member.ministries.index') }}" class="group flex items-center p-4 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-100 hover:border-green-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-hands-helping text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">My Ministries</div>
                                <div class="text-sm text-gray-600">View ministry involvement</div>
                            </div>
                            <i class="fas fa-arrow-right text-green-500 group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                        <a href="{{ route('member.family.index') }}" class="group flex items-center p-4 rounded-2xl bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-100 hover:border-purple-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">Family Members</div>
                                <div class="text-sm text-gray-600">Manage family connections</div>
                            </div>
                            <i class="fas fa-arrow-right text-purple-500 group-hover:translate-x-1 transition-transform duration-200"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}

// Add smooth animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add floating animation to background elements
    const floatingElements = document.querySelectorAll('.floating-element');
    floatingElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.5}s`;
    });
    
    // Add form validation feedback
    const inputs = document.querySelectorAll('input[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('border-red-300');
                this.classList.remove('border-gray-200');
            } else {
                this.classList.remove('border-red-300');
                this.classList.add('border-green-300');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('border-red-300');
                this.classList.add('border-green-300');
            }
        });
    });
    
    // Add password strength indicator
    const passwordField = document.getElementById('password');
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updatePasswordStrengthIndicator(strength);
        });
    }
});

function calculatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}

function updatePasswordStrengthIndicator(strength) {
    const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
    const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    
    // This would update a strength indicator if we had one in the UI
    console.log(`Password strength: ${labels[strength - 1] || 'Very Weak'}`);
}
</script>

@endsection
