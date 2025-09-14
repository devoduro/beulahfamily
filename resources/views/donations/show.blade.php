@extends('components.app-layout')

@section('title', 'Donation Details')
@section('subtitle', 'View donation information and receipt')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-receipt text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Donation Details</h1>
            <p class="text-gray-600 text-lg mt-2">{{ $donation->donation_number ?? 'DON-' . str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Status Badge -->
        <div class="flex justify-center mb-8">
            @php
                $statusColors = [
                    'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'failed' => 'bg-red-100 text-red-800 border-red-200',
                    'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200'
                ];
                $statusIcons = [
                    'confirmed' => 'fas fa-check-circle',
                    'pending' => 'fas fa-clock',
                    'failed' => 'fas fa-times-circle',
                    'cancelled' => 'fas fa-ban'
                ];
            @endphp
            <span class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold border-2 {{ $statusColors[$donation->status] ?? $statusColors['pending'] }}">
                <i class="{{ $statusIcons[$donation->status] ?? $statusIcons['pending'] }} mr-2"></i>
                {{ ucfirst($donation->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Donation Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-info-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Donation Information</h2>
                        <p class="text-gray-500 text-sm">Basic donation details</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Amount</span>
                        <span class="text-2xl font-bold text-green-600">GHS {{ number_format($donation->amount, 2) }}</span>
                    </div>

                    @if($donation->net_amount && $donation->net_amount != $donation->amount)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Net Amount</span>
                        <span class="text-lg font-semibold text-gray-900">GHS {{ number_format($donation->net_amount, 2) }}</span>
                    </div>
                    @endif

                    @if($donation->transaction_fee)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Transaction Fee</span>
                        <span class="text-sm text-gray-500">GHS {{ number_format($donation->transaction_fee, 2) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Type</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ ucwords(str_replace('_', ' ', $donation->donation_type)) }}
                        </span>
                    </div>

                    @if($donation->purpose)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Purpose</span>
                        <span class="text-gray-900 font-medium">{{ $donation->purpose }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Date</span>
                        <span class="text-gray-900 font-medium">{{ $donation->donation_date->format('M d, Y') }}</span>
                    </div>

                    @if($donation->payment_method)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Payment Method</span>
                        <span class="text-gray-900 font-medium">{{ ucwords(str_replace('_', ' ', $donation->payment_method)) }}</span>
                    </div>
                    @endif

                    @if($donation->payment_channel)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Payment Channel</span>
                        <span class="text-gray-900 font-medium">{{ ucwords($donation->payment_channel) }}</span>
                    </div>
                    @endif

                    @if($donation->reference_number)
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-600 font-medium">Reference</span>
                        <span class="text-gray-900 font-mono text-sm">{{ $donation->reference_number }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Donor Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Donor Information</h2>
                        <p class="text-gray-500 text-sm">Details about the donor</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if($donation->member)
                        <div class="flex items-center p-4 bg-blue-50 rounded-2xl border border-blue-200">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user-check text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-blue-900">Church Member</p>
                                <p class="text-blue-700">{{ $donation->member->first_name }} {{ $donation->member->last_name }}</p>
                                <p class="text-blue-600 text-sm">Member ID: {{ $donation->member->member_id }}</p>
                            </div>
                        </div>

                        @if($donation->member->email)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Email</span>
                            <span class="text-gray-900">{{ $donation->member->email }}</span>
                        </div>
                        @endif

                        @if($donation->member->phone)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Phone</span>
                            <span class="text-gray-900">{{ $donation->member->phone }}</span>
                        </div>
                        @endif

                        @if($donation->member->chapter)
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-600 font-medium">Chapter</span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                {{ $donation->member->chapter }}
                            </span>
                        </div>
                        @endif
                    @else
                        <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-200">
                            <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Guest Donor</p>
                                <p class="text-gray-700">{{ $donation->donor_name }}</p>
                            </div>
                        </div>

                        @if($donation->donor_email)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 font-medium">Email</span>
                            <span class="text-gray-900">{{ $donation->donor_email }}</span>
                        </div>
                        @endif

                        @if($donation->donor_phone)
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-600 font-medium">Phone</span>
                            <span class="text-gray-900">{{ $donation->donor_phone }}</span>
                        </div>
                        @endif
                    @endif

                    @if($donation->is_anonymous)
                    <div class="flex items-center p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                        <i class="fas fa-eye-slash text-yellow-600 mr-2"></i>
                        <span class="text-yellow-800 font-medium">Anonymous Donation</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Details (if Paystack) -->
        @if($donation->paystack_reference)
        <div class="mt-8 bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                    <i class="fas fa-credit-card text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Payment Details</h2>
                    <p class="text-gray-500 text-sm">Paystack transaction information</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Paystack Reference</span>
                    <span class="text-gray-900 font-mono text-sm">{{ $donation->paystack_reference }}</span>
                </div>

                @if($donation->paystack_transaction_id)
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Transaction ID</span>
                    <span class="text-gray-900 font-mono text-sm">{{ $donation->paystack_transaction_id }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($donation->notes)
        <div class="mt-8 bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                    <i class="fas fa-sticky-note text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Notes</h2>
                    <p class="text-gray-500 text-sm">Additional information</p>
                </div>
            </div>
            <p class="text-gray-700 leading-relaxed">{{ $donation->notes }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('donations.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Donations
            </a>

            @if($donation->status === 'confirmed')
                <button onclick="generateReceipt({{ $donation->id }})" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-receipt mr-2"></i>
                    Generate Receipt
                </button>

                @if($donation->donor_email || ($donation->member && $donation->member->email))
                <button onclick="sendReceipt({{ $donation->id }})" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-envelope mr-2"></i>
                    Send Receipt
                </button>
                @endif
            @endif

            @can('update', $donation)
            <a href="{{ route('donations.edit', $donation) }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-edit mr-2"></i>
                Edit Donation
            </a>
            @endcan
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-sm mx-4">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600 font-medium">Processing...</p>
        </div>
    </div>
</div>

<script>
function showLoading() {
    document.getElementById('loadingModal').classList.remove('hidden');
    document.getElementById('loadingModal').classList.add('flex');
}

function hideLoading() {
    document.getElementById('loadingModal').classList.add('hidden');
    document.getElementById('loadingModal').classList.remove('flex');
}

function generateReceipt(donationId) {
    showLoading();
    
    fetch(`/donations/${donationId}/generate-receipt`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Show success message
            showNotification('Receipt generated successfully!', 'success');
            
            // If there's a download URL, trigger download
            if (data.download_url) {
                window.open(data.download_url, '_blank');
            }
        } else {
            showNotification('Failed to generate receipt: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('An error occurred while generating the receipt.', 'error');
    });
}

function sendReceipt(donationId) {
    showLoading();
    
    fetch(`/donations/${donationId}/send-receipt`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showNotification('Receipt sent successfully!', 'success');
        } else {
            showNotification('Failed to send receipt: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('An error occurred while sending the receipt.', 'error');
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-2xl shadow-xl max-w-sm transform transition-all duration-300 translate-x-full`;
    
    if (type === 'success') {
        notification.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        notification.className += ' bg-red-500 text-white';
    } else {
        notification.className += ' bg-blue-500 text-white';
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle mr-3"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}
</script>
@endsection
