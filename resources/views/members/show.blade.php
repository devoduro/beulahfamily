@extends('components.app-layout')

@section('title', 'Member Profile')
@section('subtitle', 'View member details and information')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center overflow-hidden">
                @if($member->photo_path ?? false)
                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user text-white text-2xl"></i>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $member->full_name ?? 'John Doe' }}</h1>
                <p class="text-gray-600">Member ID: {{ $member->member_id ?? 'M2025001' }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($member->membership_status ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($member->membership_status ?? 'Active') }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($member->membership_type ?? 'Member') }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-cyan-100 to-blue-100 text-cyan-800 border border-cyan-200">
                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $member->chapter ?? 'ACCRA' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('members.edit', $member->id ?? 1) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                <i class="fas fa-edit mr-2"></i>
                Edit Member
            </a>
            <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Members
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                        <p class="text-gray-900">{{ $member->full_name ?? 'John Doe' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Date of Birth</label>
                        <p class="text-gray-900">
                            @if($member->date_of_birth ?? false)
                                {{ $member->date_of_birth->format('F j, Y') }} ({{ $member->age }} years old)
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Gender</label>
                        <p class="text-gray-900">{{ ucfirst($member->gender ?? 'Not specified') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Marital Status</label>
                        <p class="text-gray-900">{{ ucfirst($member->marital_status ?? 'Not specified') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Occupation</label>
                        <p class="text-gray-900">{{ $member->occupation ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Employer</label>
                        <p class="text-gray-900">{{ $member->employer ?? 'Not provided' }}</p>
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

                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-envelope w-5 mr-3 text-gray-400"></i>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $member->email ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone w-5 mr-3 text-gray-400"></i>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Primary Phone</label>
                            <p class="text-gray-900">{{ $member->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    @if($member->alternate_phone ?? false)
                        <div class="flex items-center">
                            <i class="fas fa-mobile-alt w-5 mr-3 text-gray-400"></i>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Alternate Phone</label>
                                <p class="text-gray-900">{{ $member->alternate_phone }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt w-5 mr-3 text-gray-400 mt-1"></i>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Address</label>
                            <p class="text-gray-900">
                                @if($member->address ?? false)
                                    {{ $member->address }}<br>
                                    {{ $member->city ?? '' }}{{ $member->city && $member->state ? ', ' : '' }}{{ $member->state ?? '' }} {{ $member->postal_code ?? '' }}<br>
                                    {{ $member->country ?? '' }}
                                @else
                                    Not provided
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            @if($member->family ?? false)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-home text-white"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Family Information</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Family Name</label>
                            <p class="text-gray-900">{{ $member->family->family_name ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Relationship to Head</label>
                            <p class="text-gray-900">{{ ucfirst($member->relationship_to_head ?? 'Not specified') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Family Members</label>
                            <div class="space-y-2">
                                @foreach($member->family->members ?? [] as $familyMember)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $familyMember->full_name }}</p>
                                                <p class="text-sm text-gray-600">{{ ucfirst($familyMember->relationship_to_head ?? 'Member') }}</p>
                                            </div>
                                        </div>
                                        @if($familyMember->id !== $member->id)
                                            <a href="{{ route('members.show', $familyMember->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Ministry Involvement -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-hands-praying text-white"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Ministry Involvement</h2>
                </div>

                @if($member->activeMinistries ?? false)
                    <div class="space-y-3">
                        @foreach($member->activeMinistries ?? [] as $ministry)
                            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-hands-praying text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $ministry->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ ucfirst($ministry->pivot ? $ministry->pivot->role ?? 'Member' : 'Member') }}</p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">
                                    Since {{ ($ministry->pivot && $ministry->pivot->joined_date) ? \Carbon\Carbon::parse($ministry->pivot->joined_date)->format('M Y') : 'Unknown' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Not currently involved in any ministries</p>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-medium text-gray-900">
                            {{ $member->membership_date ? $member->membership_date->format('M Y') : 'Unknown' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Donations</span>
                        <span class="font-medium text-green-600">₵{{ number_format($member->total_donations ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">This Year</span>
                        <span class="font-medium text-green-600">₵{{ number_format($member->yearly_donations ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Active Ministries</span>
                        <span class="font-medium text-purple-600">{{ $member->activeMinistries ? $member->activeMinistries->count() : 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Spiritual Information -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-cross text-white text-sm"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Spiritual Journey</h3>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Baptized</span>
                        <div class="flex items-center">
                            @if($member->is_baptized ?? false)
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-600">Yes</span>
                            @else
                                <i class="fas fa-times-circle text-gray-400 mr-2"></i>
                                <span class="text-gray-500">No</span>
                            @endif
                        </div>
                    </div>
                    @if($member->baptism_date ?? false)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Baptism Date</span>
                            <span class="font-medium text-gray-900">{{ $member->baptism_date->format('M j, Y') }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Confirmed</span>
                        <div class="flex items-center">
                            @if($member->is_confirmed ?? false)
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-600">Yes</span>
                            @else
                                <i class="fas fa-times-circle text-gray-400 mr-2"></i>
                                <span class="text-gray-500">No</span>
                            @endif
                        </div>
                    </div>
                    @if($member->confirmation_date ?? false)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Confirmation Date</span>
                            <span class="font-medium text-gray-900">{{ $member->confirmation_date->format('M j, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Emergency Contact -->
            @if($member->emergency_contact_name ?? false)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Emergency Contact</h3>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Name</label>
                            <p class="text-gray-900">{{ $member->emergency_contact_name }}</p>
                        </div>
                        @if($member->emergency_contact_phone ?? false)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Phone</label>
                                <p class="text-gray-900">{{ $member->emergency_contact_phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Communication Preferences -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Communication</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Email Newsletter</span>
                        <div class="flex items-center">
                            @if($member->receive_newsletter ?? false)
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-600">Subscribed</span>
                            @else
                                <i class="fas fa-times-circle text-gray-400 mr-2"></i>
                                <span class="text-gray-500">Not subscribed</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">SMS Notifications</span>
                        <div class="flex items-center">
                            @if($member->receive_sms ?? false)
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-600">Enabled</span>
                            @else
                                <i class="fas fa-times-circle text-gray-400 mr-2"></i>
                                <span class="text-gray-500">Disabled</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            @if($member->notes ?? false)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $member->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
