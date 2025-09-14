@extends('components.app-layout')

@section('title', 'Edit Member Payment')
@section('subtitle', 'Update member payment information')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-edit text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Edit Member Payment</h1>
            <p class="text-gray-600 text-lg mt-2">Update payment information for {{ $payment->member->first_name }} {{ $payment->member->last_name }}</p>
        </div>

        <!-- Form -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 p-8">
            <form method="POST" action="{{ route('member-payments.update', $payment) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Member Selection -->
                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>Member *
                    </label>
                    <select name="member_id" id="member_id" required class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('member_id') border-red-500 @enderror">
                        <option value="">Select a member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ (old('member_id', $payment->member_id) == $member->id) ? 'selected' : '' }}>
                                {{ $member->first_name }} {{ $member->last_name }} ({{ $member->member_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Type and Amount Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Type -->
                    <div>
                        <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-purple-500"></i>Payment Type *
                        </label>
                        <select name="payment_type" id="payment_type" required class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('payment_type') border-red-500 @enderror">
                            <option value="">Select payment type</option>
                            @foreach($paymentTypes as $key => $label)
                                <option value="{{ $key }}" {{ (old('payment_type', $payment->payment_type) === $key) ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill mr-2 text-green-500"></i>Amount (GHS) *
                        </label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" step="0.01" min="0.01" required 
                               class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('amount') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Date and Method Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-red-500"></i>Payment Date *
                        </label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required 
                               class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('payment_date') border-red-500 @enderror">
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2 text-yellow-500"></i>Payment Method *
                        </label>
                        <select name="payment_method" id="payment_method" required class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('payment_method') border-red-500 @enderror">
                            <option value="">Select payment method</option>
                            <option value="cash" {{ (old('payment_method', $payment->payment_method) === 'cash') ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ (old('payment_method', $payment->payment_method) === 'bank_transfer') ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ (old('payment_method', $payment->payment_method) === 'mobile_money') ? 'selected' : '' }}>Mobile Money</option>
                            <option value="cheque" {{ (old('payment_method', $payment->payment_method) === 'cheque') ? 'selected' : '' }}>Cheque</option>
                            <option value="card" {{ (old('payment_method', $payment->payment_method) === 'card') ? 'selected' : '' }}>Card</option>
                            <option value="online" {{ (old('payment_method', $payment->payment_method) === 'online') ? 'selected' : '' }}>Online</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status and Receipt Number Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag mr-2 text-indigo-500"></i>Status *
                        </label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('status') border-red-500 @enderror">
                            <option value="confirmed" {{ (old('status', $payment->status) === 'confirmed') ? 'selected' : '' }}>Confirmed</option>
                            <option value="pending" {{ (old('status', $payment->status) === 'pending') ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Receipt Number -->
                    <div>
                        <label for="receipt_number" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-receipt mr-2 text-pink-500"></i>Receipt Number
                        </label>
                        <input type="text" name="receipt_number" id="receipt_number" value="{{ old('receipt_number', $payment->receipt_number) }}" 
                               class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('receipt_number') border-red-500 @enderror"
                               placeholder="Enter receipt number (optional)">
                        @error('receipt_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i>Description
                    </label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('description') border-red-500 @enderror"
                              placeholder="Additional details about the payment (optional)">{{ old('description', $payment->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-orange-500"></i>Notes
                    </label>
                    <textarea name="notes" id="notes" rows="2" 
                              class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('notes') border-red-500 @enderror"
                              placeholder="Internal notes (optional)">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        Update Payment
                    </button>
                    <a href="{{ route('member-payments.show', $payment) }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Payment Information -->
        <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4 mt-0.5">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Payment Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-blue-800 text-sm">
                        <div>
                            <p><strong>Reference:</strong> {{ $payment->payment_reference }}</p>
                            <p><strong>Created:</strong> {{ $payment->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <p><strong>Recorded by:</strong> {{ $payment->recordedBy->name }}</p>
                            @if($payment->sms_sent)
                                <p><strong>SMS Sent:</strong> {{ $payment->sms_sent_at ? $payment->sms_sent_at->format('M j, Y g:i A') : 'Yes' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SMS Warning -->
        @if($payment->sms_sent)
            <div class="mt-6 bg-yellow-50 rounded-2xl p-6 border border-yellow-200">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-4 mt-0.5">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-900 mb-2">SMS Already Sent</h3>
                        <p class="text-yellow-800 text-sm">
                            An SMS notification has already been sent to the member for this payment. 
                            Updating the payment will not automatically send a new SMS notification.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
