@extends('components.public-layout')

@section('title', 'Registration Details - ' . $program->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="text-white">
                        <h1 class="text-3xl font-bold mb-2">Registration Details</h1>
                        <p class="text-blue-100 text-lg">{{ $program->name }}</p>
                    </div>
                    <div class="mt-4 lg:mt-0 lg:text-right">
                        <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2 text-white">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            {{ $program->date_range }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Status -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Registration Status</h2>
                    <div class="flex items-center space-x-4">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'approved' => 'bg-green-100 text-green-800 border-green-200',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200'
                            ];
                            $paymentColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'paid' => 'bg-green-100 text-green-800 border-green-200',
                                'partial' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'refunded' => 'bg-purple-100 text-purple-800 border-purple-200'
                            ];
                        @endphp
                        
                        <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $statusColors[$registration->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ ucfirst($registration->status) }}
                        </span>
                        
                        <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $paymentColors[$registration->payment_status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                            <i class="fas fa-credit-card mr-1"></i>
                            Payment: {{ ucfirst($registration->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-800">Registered On</p>
                                <p class="text-lg font-bold text-blue-900">{{ $registration->registered_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($program->registration_fee > 0)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">Registration Fee</p>
                                <p class="text-lg font-bold text-green-900">₵{{ number_format($program->registration_fee, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-hashtag text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-purple-800">Registration ID</p>
                                <p class="text-lg font-bold text-purple-900">#{{ $registration->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Business Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $registration->business_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                        <p class="text-lg text-gray-900">
                            {{ $registration->business_type === 'other' ? $registration->business_type_other : ucfirst(str_replace('_', ' ', $registration->business_type)) }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Services Offered</label>
                        <p class="text-gray-900 leading-relaxed">{{ $registration->services_offered }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
                        <p class="text-gray-900 leading-relaxed">{{ $registration->business_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Contact Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $registration->contact_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Phone</label>
                        <p class="text-lg text-gray-900">
                            <i class="fas fa-phone mr-2 text-blue-600"></i>
                            {{ $registration->business_phone }}
                        </p>
                    </div>

                    @if($registration->whatsapp_number)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                        <p class="text-lg text-gray-900">
                            <i class="fab fa-whatsapp mr-2 text-green-600"></i>
                            {{ $registration->whatsapp_number }}
                        </p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <p class="text-lg text-gray-900">
                            <i class="fas fa-envelope mr-2 text-purple-600"></i>
                            {{ $registration->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Information -->
        @if($registration->member)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Church Member Information</h2>
                
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $registration->member->full_name }}</p>
                        <p class="text-gray-600">Member ID: {{ $registration->member->member_id }}</p>
                        <p class="text-gray-600">{{ $registration->member->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Additional Information -->
        @if($registration->special_offers || $registration->additional_info)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Additional Information</h2>
                
                @if($registration->special_offers)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Offers for Conference Attendees</label>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-gray-900 leading-relaxed">{{ $registration->special_offers }}</p>
                    </div>
                </div>
                @endif

                @if($registration->additional_info)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Information</label>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-gray-900 leading-relaxed">{{ $registration->additional_info }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Uploaded Files -->
        @if($registration->uploaded_files && count($registration->uploaded_files) > 0)
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Uploaded Files</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($registration->uploaded_files as $index => $file)
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                @php
                                    $iconClass = 'fas fa-file text-gray-400';
                                    if (str_contains($file['mime_type'], 'image/')) {
                                        $iconClass = 'fas fa-image text-green-500';
                                    } elseif (str_contains($file['mime_type'], 'video/')) {
                                        $iconClass = 'fas fa-video text-blue-500';
                                    } elseif (str_contains($file['mime_type'], 'audio/')) {
                                        $iconClass = 'fas fa-music text-purple-500';
                                    } elseif ($file['mime_type'] === 'application/pdf') {
                                        $iconClass = 'fas fa-file-pdf text-red-500';
                                    }
                                @endphp
                                <i class="{{ $iconClass }} text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $file['original_name'] }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($file['size'] / 1024 / 1024, 2) }} MB</p>
                            </div>
                        </div>
                        <a href="{{ route('programs.registration.file.download', [$program, $registration, $index]) }}" 
                           class="text-blue-600 hover:text-blue-800 transition-colors">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <a href="{{ route('programs.show', $program) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Program
            </a>
            
            @if($registration->status === 'pending' || $registration->status === 'approved')
            <div class="flex space-x-3">
                <a href="{{ route('programs.registration.edit', [$program, $registration]) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-blue-300 text-base font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Registration
                </a>
                
                @if($registration->status !== 'cancelled')
                <form method="POST" action="{{ route('programs.registration.cancel', [$program, $registration]) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this registration?')"
                            class="inline-flex items-center justify-center px-6 py-3 border border-red-300 text-base font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancel Registration
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
