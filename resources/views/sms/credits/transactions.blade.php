@extends('components.app-layout')

@section('title', 'SMS Credit Transactions')
@section('subtitle', 'View your complete SMS credit transaction history')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-history text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Transaction History</h1>
            <p class="text-gray-600 text-lg mt-2">Complete record of your SMS credit activities</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Current Balance</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($userCredit->credits) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-plus text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Purchased</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPurchased) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-minus text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Used</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsed) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Spent</p>
                        <p class="text-2xl font-bold text-gray-900">GHS {{ number_format($totalSpent, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6 mb-8">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Type</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Types</option>
                        <option value="purchase" {{ request('type') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="usage" {{ request('type') === 'usage' ? 'selected' : '' }}>Usage</option>
                        <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Refund</option>
                        <option value="bonus" {{ request('type') === 'bonus' ? 'selected' : '' }}>Bonus</option>
                    </select>
                </div>

                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('sms.credits.transactions') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Transactions</h3>
                <p class="text-sm text-gray-600">{{ $transactions->total() }} total transactions</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaction->type === 'purchase' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->type === 'usage' ? 'bg-red-100 text-red-800' : 
                                           ($transaction->type === 'refund' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800')) }}">
                                        <i class="fas fa-{{ $transaction->type === 'purchase' ? 'plus' : 
                                                          ($transaction->type === 'usage' ? 'minus' : 
                                                          ($transaction->type === 'refund' ? 'undo' : 'gift')) }} mr-1"></i>
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $transaction->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                    {{ $transaction->type === 'purchase' || $transaction->type === 'refund' || $transaction->type === 'bonus' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'purchase' || $transaction->type === 'refund' || $transaction->type === 'bonus' ? '+' : '-' }}{{ number_format(abs($transaction->credits)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($transaction->amount)
                                        GHS {{ number_format($transaction->amount, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        <i class="fas fa-{{ $transaction->status === 'completed' ? 'check' : 
                                                          ($transaction->status === 'pending' ? 'clock' : 'times') }} mr-1"></i>
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($transaction->paystack_reference)
                                        <span class="font-mono text-xs">{{ $transaction->paystack_reference }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
                                        <p class="text-gray-500">Your transaction history will appear here</p>
                                        <a href="{{ route('sms.credits.purchase') }}" 
                                           class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                            <i class="fas fa-plus mr-2"></i>
                                            Purchase Credits
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Export Options -->
        <div class="mt-8 text-center">
            <div class="inline-flex rounded-xl shadow-lg overflow-hidden">
                <a href="{{ route('sms.credits.transactions', array_merge(request()->query(), ['export' => 'csv'])) }}" 
                   class="px-6 py-3 bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-file-csv mr-2"></i>
                    Export CSV
                </a>
                <a href="{{ route('sms.credits.transactions', array_merge(request()->query(), ['export' => 'excel'])) }}" 
                   class="px-6 py-3 bg-green-600 text-white hover:bg-green-700 transition-colors border-l border-green-700">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
