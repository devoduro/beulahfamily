@extends('components.app-layout')

@section('title', 'Transaction Details')
@section('subtitle', 'View transaction information')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Transaction Details</h1>
                <p class="text-gray-600 mt-2">{{ $transaction->reference_number }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($transaction->status === 'pending')
                    <form method="POST" action="{{ route('finance.approve', $transaction) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('finance.reject', $transaction) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Reject
                        </button>
                    </form>
                @endif
                <a href="{{ route('finance.transactions') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Transactions
                </a>
            </div>
        </div>

        <!-- Transaction Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r {{ $transaction->type === 'income' ? 'from-green-600 to-emerald-600' : 'from-red-600 to-pink-600' }} p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-6">
                            <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up' : 'fa-arrow-down' }} text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $transaction->title }}</h2>
                            <p class="text-lg opacity-90">{{ ucfirst($transaction->type) }} Transaction</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold">
                            {{ $transaction->type === 'income' ? '+' : '-' }}₵{{ number_format($transaction->amount, 2) }}
                        </p>
                        {!! $transaction->status_badge !!}
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Information</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-tag text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Category</p>
                                        <p class="font-medium text-gray-900">{{ $transaction->category->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-calendar text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Transaction Date</p>
                                        <p class="font-medium text-gray-900">{{ $transaction->transaction_date->format('F j, Y') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-credit-card text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Payment Method</p>
                                        <p class="font-medium text-gray-900">{{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                                    </div>
                                </div>

                                @if($transaction->receipt_number)
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-receipt text-yellow-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Receipt Number</p>
                                            <p class="font-medium text-gray-900">{{ $transaction->receipt_number }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($transaction->description)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="text-gray-700">{{ $transaction->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Recorded By</p>
                                        <p class="font-medium text-gray-900">{{ $transaction->user->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-clock text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Created At</p>
                                        <p class="font-medium text-gray-900">{{ $transaction->created_at->format('F j, Y g:i A') }}</p>
                                    </div>
                                </div>

                                @if($transaction->approved_by)
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-user-check text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Approved By</p>
                                            <p class="font-medium text-gray-900">{{ $transaction->approver->name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-calendar-check text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Approved At</p>
                                            <p class="font-medium text-gray-900">{{ $transaction->approved_at->format('F j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($transaction->notes)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <p class="text-gray-700">{{ $transaction->notes }}</p>
                                </div>
                            </div>
                        @endif

                        @if($transaction->metadata)
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Additional Information</h3>
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($transaction->metadata, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action History -->
        @if($transaction->status !== 'pending')
            <div class="mt-8 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction History</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-plus text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Transaction Created</p>
                            <p class="text-sm text-gray-500">{{ $transaction->created_at->format('F j, Y g:i A') }} by {{ $transaction->user->name }}</p>
                        </div>
                    </div>

                    @if($transaction->approved_at)
                        <div class="flex items-center">
                            <div class="w-8 h-8 {{ $transaction->status === 'approved' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mr-4">
                                <i class="fas {{ $transaction->status === 'approved' ? 'fa-check' : 'fa-times' }} {{ $transaction->status === 'approved' ? 'text-green-600' : 'text-red-600' }} text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Transaction {{ ucfirst($transaction->status) }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->approved_at->format('F j, Y g:i A') }} by {{ $transaction->approver->name }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
