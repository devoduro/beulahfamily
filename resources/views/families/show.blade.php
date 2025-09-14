@extends('components.app-layout')

@section('title', $family->family_name)
@section('subtitle', 'Family Details and Members')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header with Actions -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('families.index') }}" class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2 text-gray-600"></i>
                    Back to Families
                </a>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-home text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $family->family_name }}</h1>
                        <p class="text-sm text-gray-600">{{ $family->members->count() }} {{ Str::plural('member', $family->members->count()) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('families.edit', $family) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Family
                </a>
                <a href="{{ route('members.create') }}?family_id={{ $family->id }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add Member
                </a>
            </div>
        </div>

        <!-- Family Information Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                        Family Information
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span class="text-sm font-medium text-gray-500 w-24">Name:</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $family->family_name }}</span>
                        </div>
                        @if($family->head)
                        <div class="flex items-start">
                            <span class="text-sm font-medium text-gray-500 w-24">Head:</span>
                            <div class="flex items-center space-x-3">
                                @if($family->head->photo_path)
                                    <img src="{{ asset('storage/' . $family->head->photo_path) }}" alt="{{ $family->head->full_name }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600 text-xs"></i>
                                    </div>
                                @endif
                                <a href="{{ route('members.show', $family->head) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">{{ $family->head->full_name }}</a>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-start">
                            <span class="text-sm font-medium text-gray-500 w-24">Status:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $family->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas fa-circle mr-1 text-xs {{ $family->is_active ? 'text-emerald-500' : 'text-gray-500' }}"></i>
                                {{ $family->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-sm font-medium text-gray-500 w-24">Joined:</span>
                            <span class="text-sm text-gray-900">{{ $family->created_at->format('F j, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-address-book mr-3 text-green-500"></i>
                        Contact Information
                    </h3>
                    <div class="space-y-4">
                        @if($family->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone w-5 mr-3 text-emerald-500"></i>
                            <span class="text-sm text-gray-900">{{ $family->phone }}</span>
                        </div>
                        @endif
                        @if($family->email)
                        <div class="flex items-center">
                            <i class="fas fa-envelope w-5 mr-3 text-blue-500"></i>
                            <span class="text-sm text-gray-900">{{ $family->email }}</span>
                        </div>
                        @endif
                        @if($family->address)
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt w-5 mr-3 text-red-500 mt-0.5"></i>
                            <div class="text-sm text-gray-900">
                                <div>{{ $family->address }}</div>
                                @if($family->city || $family->state || $family->zip_code)
                                <div class="text-gray-600">
                                    {{ $family->city }}@if($family->city && ($family->state || $family->zip_code)), @endif{{ $family->state }} {{ $family->zip_code }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if(!$family->phone && !$family->email && !$family->address)
                        <p class="text-sm text-gray-500 italic">No contact information available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Members -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100/50 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-users mr-3 text-purple-500"></i>
                    Family Members ({{ $family->members->count() }})
                </h3>
                <a href="{{ route('members.create') }}?family_id={{ $family->id }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add Member
                </a>
            </div>

            @if($family->members->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($family->members as $member)
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                    @if($member->photo_path)
                                        <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-500"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $member->full_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $member->relationship_to_head ?: 'Member' }}</p>
                                </div>
                                @if($family->head_id === $member->id)
                                    <i class="fas fa-crown text-yellow-500" title="Family Head"></i>
                                @endif
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                @if($member->email)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-envelope w-4 mr-2 text-blue-500"></i>
                                    <span class="truncate">{{ $member->email }}</span>
                                </div>
                                @endif
                                @if($member->phone)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-phone w-4 mr-2 text-emerald-500"></i>
                                    <span>{{ $member->phone }}</span>
                                </div>
                                @endif
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-birthday-cake w-4 mr-2 text-pink-500"></i>
                                    <span>{{ $member->date_of_birth ? $member->date_of_birth->format('M j, Y') : 'Not provided' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $member->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('members.show', $member) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('members.edit', $member) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit Member">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No family members yet</h3>
                    <p class="text-gray-500 mb-6">Start building this family by adding the first member.</p>
                    <a href="{{ route('members.create') }}?family_id={{ $family->id }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>
                        Add First Member
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
