@extends('components.app-layout')

@section('title', 'Registration Details - ' . $registration->business_name)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Registration Details</h1>
                <p class="text-gray-600">{{ $program->name }} - {{ $registration->business_name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.programs.registrations', $program) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Registrations
                </a>
                <a href="{{ route('admin.programs.show', $program) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-eye mr-2"></i>View Program
                </a>
            </div>
        </div>
    </div>

    <!-- Registration Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Registration Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @php
                        $statusIcons = [
                            'pending' => 'fas fa-clock text-yellow-600',
                            'approved' => 'fas fa-check-circle text-green-600',
                            'rejected' => 'fas fa-times-circle text-red-600',
                            'cancelled' => 'fas fa-ban text-gray-600'
                        ];
                    @endphp
                    <i class="{{ $statusIcons[$registration->status] ?? 'fas fa-question-circle text-gray-600' }} text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($registration->status) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @php
                        $paymentIcons = [
                            'pending' => 'fas fa-clock text-yellow-600',
                            'paid' => 'fas fa-check-circle text-green-600',
                            'partial' => 'fas fa-exclamation-circle text-blue-600',
                            'refunded' => 'fas fa-undo text-purple-600'
                        ];
                    @endphp
                    <i class="{{ $paymentIcons[$registration->payment_status] ?? 'fas fa-question-circle text-gray-600' }} text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Payment</p>
                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($registration->payment_status) }}</p>
                </div>
            </div>
        </div>

        <!-- Registration Date -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Registered</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->registered_at->format('M j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Files Count -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-paperclip text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Files</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $registration->getUploadedFilesCount() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Business Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Business Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Business Name</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $registration->business_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                            <p class="text-gray-900">{{ $registration->getBusinessTypeLabel() }}</p>
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $registration->contact_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Business Phone</label>
                            <p class="text-gray-900">
                                <i class="fas fa-phone mr-2 text-blue-600"></i>
                                <a href="tel:{{ $registration->business_phone }}" class="hover:text-blue-600">{{ $registration->business_phone }}</a>
                            </p>
                        </div>

                        @if($registration->whatsapp_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                            <p class="text-gray-900">
                                <i class="fab fa-whatsapp mr-2 text-green-600"></i>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->whatsapp_number) }}" target="_blank" class="hover:text-green-600">{{ $registration->whatsapp_number }}</a>
                            </p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-gray-900">
                                <i class="fas fa-envelope mr-2 text-purple-600"></i>
                                <a href="mailto:{{ $registration->email }}" class="hover:text-purple-600">{{ $registration->email }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($registration->special_offers || $registration->additional_info)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    @if($registration->special_offers)
                    <div>
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Uploaded Files</h3>
                </div>
                <div class="p-6">
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
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Member Information -->
            @if($registration->member)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Church Member</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $registration->member->full_name }}</p>
                            <p class="text-sm text-gray-600">ID: {{ $registration->member->member_id }}</p>
                            <p class="text-sm text-gray-600">{{ $registration->member->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Management -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status Management</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.programs.registrations.update-status', [$program, $registration]) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Registration Status</label>
                                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" {{ $registration->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $registration->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $registration->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ $registration->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                                <textarea id="admin_notes" name="admin_notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Add notes about this registration...">{{ $registration->admin_notes }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Information -->
            @if($program->registration_fee > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Payment Information</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Registration Fee:</span>
                            <span class="font-semibold">₵{{ number_format($program->registration_fee, 2) }}</span>
                        </div>
                        
                        @if($registration->amount_paid)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Amount Paid:</span>
                            <span class="font-semibold text-green-600">₵{{ number_format($registration->amount_paid, 2) }}</span>
                        </div>
                        @endif

                        @if($registration->payment_reference)
                        <div>
                            <span class="text-sm text-gray-600">Payment Reference:</span>
                            <p class="font-mono text-sm bg-gray-100 p-2 rounded mt-1">{{ $registration->payment_reference }}</p>
                        </div>
                        @endif

                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600">Payment Status:</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $registration->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($registration->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="mailto:{{ $registration->email }}" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>Send Email
                    </a>
                    
                    @if($registration->whatsapp_number)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->whatsapp_number) }}" target="_blank" 
                       class="w-full bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                    @endif
                    
                    <a href="tel:{{ $registration->business_phone }}" 
                       class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-2 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-phone mr-2"></i>Call
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
