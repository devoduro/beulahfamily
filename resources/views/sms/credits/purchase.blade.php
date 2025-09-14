@extends('components.app-layout')

@section('title', 'Purchase SMS Credits')
@section('subtitle', 'Top up your SMS credits with secure payment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-shopping-cart text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Purchase SMS Credits</h1>
            <p class="text-gray-600 text-lg mt-2">Choose your preferred package and payment method</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Pricing Plans -->
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Select a Package</h2>
                
                @foreach($pricingPlans as $plan)
                    <div class="pricing-plan bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 cursor-pointer transition-all duration-300 hover:shadow-2xl hover:scale-105 {{ $loop->index === 2 ? 'ring-4 ring-green-500 ring-opacity-50' : '' }}" 
                         data-plan-id="{{ $plan->id }}"
                         data-plan-name="{{ $plan->name }}"
                         data-plan-credits="{{ $plan->credits }}"
                         data-plan-total-credits="{{ $plan->total_credits }}"
                         data-plan-price="{{ $plan->price }}"
                         data-plan-bonus="{{ $plan->bonus_credits }}">
                        
                        @if($loop->index === 2)
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                    @if($plan->bonus_credits > 0)
                                        <span class="ml-3 bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-semibold">
                                            +{{ $plan->bonus_credits }}% Bonus
                                        </span>
                                    @endif
                                </div>
                                <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                                
                                <div class="flex items-center space-x-6">
                                    <div>
                                        <span class="text-3xl font-bold text-green-600">{{ number_format($plan->credits) }}</span>
                                        <span class="text-gray-500">credits</span>
                                    </div>
                                    @if($plan->bonus_credits > 0)
                                        <div class="text-center">
                                            <i class="fas fa-plus text-orange-500 mb-1"></i>
                                            <div class="text-lg font-semibold text-orange-600">{{ number_format($plan->total_credits - $plan->credits) }}</div>
                                            <div class="text-xs text-orange-500">bonus</div>
                                        </div>
                                        <div class="text-center">
                                            <i class="fas fa-equals text-gray-400 mb-1"></i>
                                            <div class="text-xl font-bold text-gray-900">{{ number_format($plan->total_credits) }}</div>
                                            <div class="text-xs text-gray-500">total</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right ml-6">
                                <div class="text-3xl font-bold text-gray-900">{{ $plan->formatted_price }}</div>
                                <div class="text-sm text-gray-500">{{ number_format($plan->value, 1) }} credits/GHS</div>
                                <div class="mt-4">
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-full plan-radio"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary & Payment -->
            <div class="lg:col-span-1">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h3>
                    
                    <div id="orderSummary" class="mb-6">
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-mouse-pointer text-3xl mb-3"></i>
                            <p>Select a package to continue</p>
                        </div>
                    </div>

                    <form id="purchaseForm" class="space-y-6" style="display: none;">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-3">Payment Method</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-500 transition-colors">
                                    <input type="radio" name="payment_method" value="mobile_money" class="mr-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-mobile-alt text-green-600 mr-3"></i>
                                        <div>
                                            <div class="font-semibold">Mobile Money</div>
                                            <div class="text-sm text-gray-500">MTN, Vodafone, AirtelTigo</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-500 transition-colors">
                                    <input type="radio" name="payment_method" value="bank" class="mr-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-university text-blue-600 mr-3"></i>
                                        <div>
                                            <div class="font-semibold">Bank Transfer</div>
                                            <div class="text-sm text-gray-500">Direct bank payment</div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-500 transition-colors">
                                    <input type="radio" name="payment_method" value="card" class="mr-3">
                                    <div class="flex items-center">
                                        <i class="fas fa-credit-card text-purple-600 mr-3"></i>
                                        <div>
                                            <div class="font-semibold">Card Payment</div>
                                            <div class="text-sm text-gray-500">Visa, Mastercard</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <button type="submit" 
                                    class="w-full px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <i class="fas fa-lock mr-2"></i>
                                Proceed to Payment
                            </button>
                        </div>

                        <div class="text-center text-xs text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Secured by Paystack
                        </div>
                    </form>
                </div>

                <!-- Security Info -->
                <div class="mt-6 bg-blue-50 rounded-2xl p-4 border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-blue-900">Secure Payment</h4>
                            <p class="text-sm text-blue-700 mt-1">All payments are processed securely through Paystack. Your financial information is encrypted and protected.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedPlan = null;

document.addEventListener('DOMContentLoaded', function() {
    const pricingPlans = document.querySelectorAll('.pricing-plan');
    const orderSummary = document.getElementById('orderSummary');
    const purchaseForm = document.getElementById('purchaseForm');

    pricingPlans.forEach(plan => {
        plan.addEventListener('click', function() {
            // Remove selection from all plans
            pricingPlans.forEach(p => {
                p.classList.remove('ring-4', 'ring-green-500', 'ring-opacity-50');
                p.querySelector('.plan-radio').classList.remove('bg-green-500', 'border-green-500');
                p.querySelector('.plan-radio').classList.add('border-gray-300');
            });

            // Add selection to clicked plan
            this.classList.add('ring-4', 'ring-green-500', 'ring-opacity-50');
            const radio = this.querySelector('.plan-radio');
            radio.classList.remove('border-gray-300');
            radio.classList.add('bg-green-500', 'border-green-500');

            // Store selected plan data
            selectedPlan = {
                id: this.dataset.planId,
                name: this.dataset.planName,
                credits: parseInt(this.dataset.planCredits),
                totalCredits: parseInt(this.dataset.planTotalCredits),
                price: parseFloat(this.dataset.planPrice),
                bonus: parseFloat(this.dataset.planBonus)
            };

            // Update order summary
            updateOrderSummary();
            
            // Show purchase form
            purchaseForm.style.display = 'block';
        });
    });

    function updateOrderSummary() {
        if (!selectedPlan) return;

        const bonusCredits = selectedPlan.totalCredits - selectedPlan.credits;
        
        orderSummary.innerHTML = `
            <div class="space-y-4">
                <div class="bg-green-50 rounded-2xl p-4 border border-green-200">
                    <h4 class="font-bold text-green-900">${selectedPlan.name}</h4>
                    <div class="mt-2 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-green-700">Base Credits:</span>
                            <span class="font-semibold">${selectedPlan.credits.toLocaleString()}</span>
                        </div>
                        ${bonusCredits > 0 ? `
                        <div class="flex justify-between">
                            <span class="text-orange-700">Bonus Credits:</span>
                            <span class="font-semibold text-orange-600">+${bonusCredits.toLocaleString()}</span>
                        </div>
                        <hr class="border-green-200">
                        <div class="flex justify-between font-bold">
                            <span class="text-green-900">Total Credits:</span>
                            <span class="text-green-600">${selectedPlan.totalCredits.toLocaleString()}</span>
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold">GHS ${selectedPlan.price.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Processing Fee:</span>
                        <span class="font-semibold">Included</span>
                    </div>
                    <hr class="border-gray-200">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span class="text-green-600">GHS ${selectedPlan.price.toFixed(2)}</span>
                    </div>
                </div>
            </div>
        `;
    }

    // Handle form submission
    purchaseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!selectedPlan) {
            alert('Please select a package');
            return;
        }

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            alert('Please select a payment method');
            return;
        }

        const formData = new FormData();
        formData.append('pricing_id', selectedPlan.id);
        formData.append('payment_method', paymentMethod.value);

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        submitButton.disabled = true;

        fetch('{{ route("message.credits.initialize") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Redirect to Paystack payment page
                window.location.href = data.data.authorization_url;
            } else {
                alert('Payment initialization failed: ' + data.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });
});
</script>
@endsection
