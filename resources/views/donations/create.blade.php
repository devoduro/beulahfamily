@extends('components.app-layout')

@section('title', 'Make a Donation')
@section('subtitle', 'Support our church mission')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-heart text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Make a Donation</h1>
            <p class="text-gray-600 text-lg mt-2">Your generous contribution helps us serve our community better</p>
        </div>

        <form id="donation-form" class="space-y-8">
            @csrf
            
            <!-- Donation Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-donate text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Donation Details</h2>
                        <p class="text-gray-500 text-sm">Choose your donation amount and purpose</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-800 mb-4">Donation Amount (GHS)</label>
                        
                        <!-- Quick Amount Buttons -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <button type="button" class="amount-btn px-4 py-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors font-semibold" data-amount="50">
                                GHS 50
                            </button>
                            <button type="button" class="amount-btn px-4 py-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors font-semibold" data-amount="100">
                                GHS 100
                            </button>
                            <button type="button" class="amount-btn px-4 py-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors font-semibold" data-amount="200">
                                GHS 200
                            </button>
                            <button type="button" class="amount-btn px-4 py-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors font-semibold" data-amount="500">
                                GHS 500
                            </button>
                        </div>

                        <div class="relative">
                            <input type="number" name="amount" id="amount" min="1" step="0.01" required 
                                   class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md text-lg" 
                                   placeholder="Enter custom amount">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <span class="text-gray-500 font-medium">GHS</span>
                            </div>
                        </div>
                    </div>

                    <!-- Donation Type -->
                    <div>
                        <label for="donation_type" class="block text-sm font-semibold text-gray-800 mb-2">Donation Type</label>
                        <select name="donation_type" id="donation_type" required class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select Type</option>
                            <option value="tithe">Tithe</option>
                            <option value="offering">Offering</option>
                            <option value="building_fund">Building Fund</option>
                            <option value="missions">Missions</option>
                            <option value="youth_ministry">Youth Ministry</option>
                            <option value="women_ministry">Women's Ministry</option>
                            <option value="men_ministry">Men's Ministry</option>
                            <option value="special_project">Special Project</option>
                            <option value="thanksgiving">Thanksgiving</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Purpose -->
                    <div>
                        <label for="purpose" class="block text-sm font-semibold text-gray-800 mb-2">Purpose (Optional)</label>
                        <input type="text" name="purpose" id="purpose" 
                               class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                               placeholder="e.g., In memory of...">
                    </div>
                </div>
            </div>

            <!-- Donor Information -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Your Information</h2>
                        <p class="text-gray-500 text-sm">We need this for your donation receipt</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Member Selection -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-800 mb-2">Are you a church member?</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_member" value="yes" class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">Yes, I'm a member</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_member" value="no" checked class="mr-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-700">No, I'm a visitor/friend</span>
                            </label>
                        </div>
                    </div>

                    <!-- Member Selection Dropdown -->
                    <div id="member-selection" class="md:col-span-2 hidden">
                        <label for="member_id" class="block text-sm font-semibold text-gray-800 mb-2">Select Your Profile</label>
                        <select name="member_id" id="member_id" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select your member profile</option>
                            @foreach($members ?? [] as $member)
                                <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->member_id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Manual Information -->
                    <div id="manual-info">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="donor_name" class="block text-sm font-semibold text-gray-800 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="donor_name" id="donor_name" required 
                                       class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                                       placeholder="Enter your full name">
                            </div>

                            <div>
                                <label for="donor_email" class="block text-sm font-semibold text-gray-800 mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="donor_email" id="donor_email" required 
                                       class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                                       placeholder="your@email.com">
                            </div>

                            <div>
                                <label for="donor_phone" class="block text-sm font-semibold text-gray-800 mb-2">Phone Number</label>
                                <input type="tel" name="donor_phone" id="donor_phone" 
                                       class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                                       placeholder="+233 XX XXX XXXX">
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_anonymous" value="1" class="mr-2 text-blue-600 focus:ring-blue-500 rounded">
                                    <span class="text-gray-700">Make this donation anonymous</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-credit-card text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Payment Method</h2>
                        <p class="text-gray-500 text-sm">Choose how you'd like to pay</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Card Payment -->
                    <div class="payment-method-card cursor-pointer border-2 border-gray-200 rounded-2xl p-6 hover:border-blue-500 hover:bg-blue-50 transition-all duration-300" data-method="card">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Card Payment</h3>
                            <p class="text-sm text-gray-600 mt-1">Visa, Mastercard, Verve</p>
                        </div>
                    </div>

                    <!-- Mobile Money -->
                    <div class="payment-method-card cursor-pointer border-2 border-gray-200 rounded-2xl p-6 hover:border-green-500 hover:bg-green-50 transition-all duration-300" data-method="mobile_money">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-mobile-alt text-green-600 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Mobile Money</h3>
                            <p class="text-sm text-gray-600 mt-1">MTN, Vodafone, AirtelTigo</p>
                        </div>
                    </div>

                    <!-- Bank Transfer -->
                    <div class="payment-method-card cursor-pointer border-2 border-gray-200 rounded-2xl p-6 hover:border-purple-500 hover:bg-purple-50 transition-all duration-300" data-method="bank">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-university text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">Bank Transfer</h3>
                            <p class="text-sm text-gray-600 mt-1">Direct bank payment</p>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="payment_method" id="payment_method" required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" id="donate-btn" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-heart mr-3"></i>
                    <span>Proceed to Payment</span>
                    <div class="ml-3 w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin hidden" id="loading-spinner"></div>
                </button>
                <p class="text-sm text-gray-600 mt-4">
                    <i class="fas fa-lock mr-1"></i>
                    Your payment is secured by Paystack
                </p>
            </div>
        </form>
    </div>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Amount button handlers
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('amount').value = this.dataset.amount;
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('bg-blue-500', 'text-white'));
            this.classList.add('bg-blue-500', 'text-white');
        });
    });

    // Member selection toggle
    document.querySelectorAll('input[name="is_member"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const memberSelection = document.getElementById('member-selection');
            const manualInfo = document.getElementById('manual-info');
            
            if (this.value === 'yes') {
                memberSelection.classList.remove('hidden');
                manualInfo.classList.add('hidden');
                document.getElementById('donor_name').required = false;
                document.getElementById('donor_email').required = false;
            } else {
                memberSelection.classList.add('hidden');
                manualInfo.classList.remove('hidden');
                document.getElementById('donor_name').required = true;
                document.getElementById('donor_email').required = true;
            }
        });
    });

    // Payment method selection
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.payment-method-card').forEach(c => {
                c.classList.remove('border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50', 'border-purple-500', 'bg-purple-50');
                c.classList.add('border-gray-200');
            });
            
            const method = this.dataset.method;
            document.getElementById('payment_method').value = method;
            
            if (method === 'card') {
                this.classList.add('border-blue-500', 'bg-blue-50');
            } else if (method === 'mobile_money') {
                this.classList.add('border-green-500', 'bg-green-50');
            } else if (method === 'bank') {
                this.classList.add('border-purple-500', 'bg-purple-50');
            }
        });
    });

    // Form submission
    document.getElementById('donation-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const donateBtn = document.getElementById('donate-btn');
        const loadingSpinner = document.getElementById('loading-spinner');
        
        // Show loading state
        donateBtn.disabled = true;
        loadingSpinner.classList.remove('hidden');
        
        // Initialize Paystack payment
        fetch('{{ route("donations.initialize") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Initialize Paystack popup
                const handler = PaystackPop.setup({
                    key: '{{ config("services.paystack.public_key") }}',
                    email: data.email,
                    amount: data.amount * 100, // Convert to kobo
                    ref: data.reference,
                    currency: 'GHS',
                    channels: ['card', 'bank', 'ussd', 'mobile_money'],
                    callback: function(response) {
                        // Payment successful
                        window.location.href = '{{ route("donations.verify") }}?reference=' + response.reference;
                    },
                    onClose: function() {
                        // Reset button state
                        donateBtn.disabled = false;
                        loadingSpinner.classList.add('hidden');
                    }
                });
                
                handler.openIframe();
            } else {
                alert('Error: ' + data.message);
                donateBtn.disabled = false;
                loadingSpinner.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            donateBtn.disabled = false;
            loadingSpinner.classList.add('hidden');
        });
    });
});
</script>
@endsection
