@extends('components.app-layout')

@section('title', 'Donation Successful')
@section('subtitle', 'Thank you for your generosity')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl animate-pulse">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-4">
                Donation Successful!
            </h1>
            <p class="text-xl text-gray-600">Thank you for your generous contribution to our church</p>
        </div>

        <!-- Donation Details Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 p-8 mb-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-receipt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Donation Receipt</h2>
                        <p class="text-gray-500">Transaction completed successfully</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Reference</p>
                    <p class="font-mono text-sm font-semibold text-gray-800">{{ $donation->paystack_reference }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Donation Information -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Donation Details
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Amount</span>
                                <span class="font-bold text-2xl text-green-600">GHS {{ number_format($donation->amount, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Type</span>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ ucwords(str_replace('_', ' ', $donation->donation_type)) }}
                                </span>
                            </div>
                            
                            @if($donation->purpose)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Purpose</span>
                                <span class="text-gray-900 font-medium">{{ $donation->purpose }}</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                    {{ ucwords(str_replace('_', ' ', $donation->payment_channel)) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Date & Time</span>
                                <span class="text-gray-900 font-medium">
                                    {{ $donation->confirmed_at ? $donation->confirmed_at->format('M d, Y \a\t g:i A') : $donation->donation_date->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Donor Information -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user text-green-500 mr-2"></i>
                            Donor Information
                        </h3>
                        
                        <div class="space-y-4">
                            @if(!$donation->is_anonymous)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Name</span>
                                    <span class="text-gray-900 font-medium">{{ $donation->donor_name }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Email</span>
                                    <span class="text-gray-900 font-medium">{{ $donation->donor_email }}</span>
                                </div>
                                
                                @if($donation->donor_phone)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Phone</span>
                                    <span class="text-gray-900 font-medium">{{ $donation->donor_phone }}</span>
                                </div>
                                @endif
                                
                                @if($donation->member)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Member ID</span>
                                    <span class="text-gray-900 font-medium">{{ $donation->member->member_id }}</span>
                                </div>
                                @endif
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-user-secret text-gray-400 text-3xl mb-2"></i>
                                    <p class="text-gray-500">Anonymous Donation</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            @if($donation->transaction_fee)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calculator text-orange-500 mr-2"></i>
                    Transaction Breakdown
                </h3>
                
                <div class="bg-gray-50 rounded-2xl p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Donation Amount</span>
                            <span>GHS {{ number_format($donation->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Transaction Fee</span>
                            <span>GHS {{ number_format($donation->transaction_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-gray-900 pt-2 border-t border-gray-300">
                            <span>Net Amount Received</span>
                            <span>GHS {{ number_format($donation->net_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="text-center space-y-4">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-print mr-2"></i>
                    Print Receipt
                </button>
                
                <a href="{{ route('donations.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-heart mr-2"></i>
                    Make Another Donation
                </a>
                
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-home mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
            
            <div class="mt-8 p-6 bg-blue-50 rounded-2xl">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">What happens next?</h3>
                <p class="text-blue-700 mb-4">Your donation has been successfully processed and will be used to support our church ministries and community outreach programs.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center text-blue-600">
                        <i class="fas fa-envelope mr-2"></i>
                        <span>Receipt sent to your email</span>
                    </div>
                    <div class="flex items-center text-blue-600">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>Added to church records</span>
                    </div>
                    <div class="flex items-center text-blue-600">
                        <i class="fas fa-hands-helping mr-2"></i>
                        <span>Supporting our mission</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
