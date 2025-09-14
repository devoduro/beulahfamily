@extends('components.app-layout')

@section('title', 'SMS Credits')
@section('subtitle', 'Manage your SMS credit balance and purchase more credits')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-credit-card text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">SMS Credits</h1>
            <p class="text-gray-600 text-lg mt-2">Manage your SMS credit balance and top up when needed</p>
        </div>

        <!-- Credit Balance Card -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-6 md:mb-0">
                    <h2 class="text-2xl font-bold mb-2">Current Balance</h2>
                    <div class="text-5xl font-bold mb-2">{{ number_format($userCredit->credits) }}</div>
                    <p class="text-green-100">SMS Credits Available</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('message.credits.purchase') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-green-600 font-semibold rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Buy Credits
                    </a>
                    <a href="{{ route('message.credits.transactions') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-500 text-white font-semibold rounded-2xl hover:bg-green-400 transition-all duration-300 shadow-lg hover:shadow-xl border-2 border-white/20">
                        <i class="fas fa-history mr-2"></i>
                        Transaction History
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Pricing Plans -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-tags text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Available Packages</h2>
                        <p class="text-gray-500 text-sm">Choose the best plan for your needs</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($pricingPlans as $plan)
                        <div class="border-2 border-gray-200 rounded-2xl p-4 hover:border-green-500 transition-all duration-300 cursor-pointer" 
                             onclick="selectPlan({{ $plan->id }})">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $plan->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $plan->description }}</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-lg font-bold text-green-600">{{ $plan->credits }} credits</span>
                                        @if($plan->bonus_credits > 0)
                                            <span class="text-sm bg-orange-100 text-orange-800 px-2 py-1 rounded-full">
                                                +{{ $plan->bonus_credits }}% bonus
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-900">{{ $plan->formatted_price }}</div>
                                    @if($plan->total_credits > $plan->credits)
                                        <div class="text-sm text-green-600">{{ $plan->total_credits }} total credits</div>
                                    @endif
                                    <div class="text-xs text-gray-500">{{ number_format($plan->value, 1) }} credits/GHS</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-2xl border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-blue-900">Payment Methods</h4>
                            <p class="text-sm text-blue-700 mt-1">We accept Mobile Money (MOMO), Bank Transfer, and Card payments via Paystack.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                            <i class="fas fa-receipt text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Recent Transactions</h2>
                            <p class="text-gray-500 text-sm">Your latest credit activities</p>
                        </div>
                    </div>
                    <a href="{{ route('message.credits.transactions') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recentTransactions as $transaction)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3
                                    {{ $transaction->type === 'purchase' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    <i class="fas fa-{{ $transaction->type === 'purchase' ? 'plus' : 'minus' }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $transaction->description }}</p>
                                    <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold {{ $transaction->type === 'purchase' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'purchase' ? '+' : '-' }}{{ number_format(abs($transaction->credits)) }}
                                </p>
                                @if($transaction->amount)
                                    <p class="text-sm text-gray-500">GHS {{ number_format($transaction->amount, 2) }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No transactions yet</p>
                            <p class="text-sm text-gray-400">Your credit activities will appear here</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="mt-8 bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                    <i class="fas fa-chart-bar text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Usage Overview</h2>
                    <p class="text-gray-500 text-sm">Your SMS credit usage patterns</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-2xl">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($userCredit->credits) }}</div>
                    <div class="text-sm text-blue-800 font-medium">Available Credits</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-2xl">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $recentTransactions->where('type', 'purchase')->sum('credits') }}
                    </div>
                    <div class="text-sm text-green-800 font-medium">Credits Purchased</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-2xl">
                    <div class="text-2xl font-bold text-red-600">
                        {{ abs($recentTransactions->where('type', 'usage')->sum('credits')) }}
                    </div>
                    <div class="text-sm text-red-800 font-medium">Credits Used</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-2xl">
                    <div class="text-2xl font-bold text-purple-600">
                        GHS {{ number_format($recentTransactions->where('type', 'purchase')->sum('amount'), 2) }}
                    </div>
                    <div class="text-sm text-purple-800 font-medium">Total Spent</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Purchase Modal -->
<div id="purchaseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-md mx-4 w-full">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Purchase SMS Credits</h3>
        <div id="selectedPlanDetails" class="mb-6"></div>
        
        <form id="purchaseForm">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Payment Method</label>
                <select name="payment_method" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="">Select Payment Method</option>
                    <option value="mobile_money">Mobile Money (MOMO)</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="card">Card Payment</option>
                </select>
            </div>
            
            <div class="flex gap-4">
                <button type="button" onclick="closePurchaseModal()" 
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all">
                    Purchase Now
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedPlanId = null;

function selectPlan(planId) {
    selectedPlanId = planId;
    const plan = @json($pricingPlans->keyBy('id'));
    const selectedPlan = plan[planId];
    
    document.getElementById('selectedPlanDetails').innerHTML = `
        <div class="bg-green-50 rounded-2xl p-4 border border-green-200">
            <h4 class="font-bold text-green-900">${selectedPlan.name}</h4>
            <p class="text-sm text-green-700">${selectedPlan.description}</p>
            <div class="flex justify-between items-center mt-2">
                <span class="text-lg font-bold text-green-600">${selectedPlan.credits} credits</span>
                <span class="text-xl font-bold text-gray-900">${selectedPlan.formatted_price}</span>
            </div>
            ${selectedPlan.bonus_credits > 0 ? `<p class="text-sm text-orange-600 mt-1">+${selectedPlan.bonus_credits}% bonus (${selectedPlan.total_credits} total credits)</p>` : ''}
        </div>
    `;
    
    document.getElementById('purchaseModal').classList.remove('hidden');
    document.getElementById('purchaseModal').classList.add('flex');
}

function closePurchaseModal() {
    document.getElementById('purchaseModal').classList.add('hidden');
    document.getElementById('purchaseModal').classList.remove('flex');
    selectedPlanId = null;
}

document.getElementById('purchaseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!selectedPlanId) {
        alert('Please select a plan');
        return;
    }
    
    const formData = new FormData(this);
    formData.append('pricing_id', selectedPlanId);
    
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
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>
@endsection
