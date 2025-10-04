@extends('components.app-layout')

@section('title', 'Payment Details')
@section('subtitle', 'View member payment information')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12 space-y-4 lg:space-y-0">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl flex items-center justify-center mr-4 shadow-xl">
                    <i class="fas fa-receipt text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">Payment Receipt</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-hashtag mr-1 text-xs"></i>{{ $payment->payment_reference }}
                        </span>
                        @if($payment->invoice_number)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-file-invoice mr-1 text-xs"></i>{{ $payment->invoice_number }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('member-payments.edit', $payment) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                @if($payment->sms_sent)
                    <form method="POST" action="{{ route('member-payments.resend-sms', $payment) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-sms mr-2"></i>
                            Resend SMS
                        </button>
                    </form>
                @endif
                <a href="{{ route('member-payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Payments
                </a>
            </div>
        </div>

        <!-- Payment Receipt Card -->
        <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 overflow-hidden relative">
            <!-- Decorative Pattern -->
            <div class="absolute top-0 right-0 w-64 h-64 opacity-5">
                <svg viewBox="0 0 100 100" class="w-full h-full">
                    <pattern id="receipt-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="10" cy="10" r="2" fill="currentColor"/>
                    </pattern>
                    <rect width="100" height="100" fill="url(#receipt-pattern)"/>
                </svg>
            </div>
            
            <!-- Header Section -->
            <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 p-10 text-white relative overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
                <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white/10 rounded-full"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-6 lg:space-y-0">
                        <div class="flex items-center">
                            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center mr-6 shadow-xl">
                                <i class="fas fa-hand-holding-heart text-4xl"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold mb-2">{{ $payment->payment_type_display }}</h2>
                                <p class="text-xl text-white/90 font-medium">{{ $payment->member->first_name }} {{ $payment->member->last_name }}</p>
                                <p class="text-white/70 text-sm mt-1">Member ID: {{ $payment->member->member_id }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
                                <p class="text-white/80 text-sm font-medium mb-1">Amount Paid</p>
                                <p class="text-4xl font-bold mb-3">₵{{ number_format($payment->amount, 2) }}</p>
                                {!! $payment->status_badge !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Member Information</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Member Name</p>
                                        <p class="font-medium text-gray-900">{{ $payment->member->first_name }} {{ $payment->member->last_name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-id-card text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Member ID</p>
                                        <p class="font-medium text-gray-900">{{ $payment->member->member_id }}</p>
                                    </div>
                                </div>

                                @if($payment->member->phone)
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-phone text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Phone Number</p>
                                            <p class="font-medium text-gray-900">{{ $payment->member->phone }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($payment->member->chapter)
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-map-marker-alt text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Chapter</p>
                                            <p class="font-medium text-gray-900">{{ $payment->member->chapter }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($payment->description)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700">{{ $payment->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-calendar text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Payment Date</p>
                                        <p class="font-medium text-gray-900">{{ $payment->payment_date->format('F j, Y') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-credit-card text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Payment Method</p>
                                        <p class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-receipt text-pink-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Receipt Number</p>
                                        <p class="font-medium text-gray-900">{{ $payment->receipt_number ?? 'Not generated' }}</p>
                                    </div>
                                </div>

                                @if($payment->invoice_number)
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-file-invoice text-indigo-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Invoice Number</p>
                                            <p class="font-medium text-gray-900">{{ $payment->invoice_number }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-user-tie text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Recorded By</p>
                                        <p class="font-medium text-gray-900">{{ $payment->recordedBy->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-clock text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Recorded At</p>
                                        <p class="font-medium text-gray-900">{{ $payment->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($payment->notes)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <p class="text-gray-700">{{ $payment->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Status & Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <!-- SMS Status -->
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-sms text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">SMS Notification</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 {{ $payment->sms_sent ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }} rounded-xl">
                        <div class="flex items-center">
                            <div class="w-10 h-10 {{ $payment->sms_sent ? 'bg-green-100' : 'bg-gray-100' }} rounded-lg flex items-center justify-center mr-4">
                                <i class="fas {{ $payment->sms_sent ? 'fa-check-circle text-green-600' : 'fa-clock text-gray-600' }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold {{ $payment->sms_sent ? 'text-green-800' : 'text-gray-700' }}">
                                    {{ $payment->sms_sent ? 'SMS Delivered' : 'SMS Pending' }}
                                </p>
                                @if($payment->sms_sent_at)
                                    <p class="text-sm {{ $payment->sms_sent ? 'text-green-600' : 'text-gray-500' }}">{{ $payment->sms_sent_at->format('M j, Y \a\t g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                        @if($payment->sms_sent)
                            <form method="POST" action="{{ route('member-payments.resend-sms', $payment) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-redo mr-2"></i>
                                    Resend
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-bolt text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Quick Actions</h3>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('member-payments.edit', $payment) }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-4 transition-colors">
                            <i class="fas fa-edit text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-blue-800">Edit Payment</p>
                            <p class="text-sm text-blue-600">Modify payment details</p>
                        </div>
                    </a>
                    
                    <button onclick="window.print()" class="w-full flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-4 transition-colors">
                            <i class="fas fa-print text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-green-800">Print Receipt</p>
                            <p class="text-sm text-green-600">Generate printable receipt</p>
                        </div>
                    </button>
                    
                    <a href="{{ route('member-payments.index') }}" class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-xl hover:from-gray-100 hover:to-slate-100 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-gray-100 group-hover:bg-gray-200 rounded-lg flex items-center justify-center mr-4 transition-colors">
                            <i class="fas fa-list text-gray-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">All Payments</p>
                            <p class="text-sm text-gray-600">View payment history</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        @if($payment->transaction_id)
            <div class="mt-8 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Information</h3>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-link text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Transaction ID</p>
                        <p class="text-sm text-gray-500">{{ $payment->transaction_id }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Metadata -->
        @if($payment->metadata)
            <div class="mt-8 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($payment->metadata, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endpush
