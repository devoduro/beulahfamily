@extends('components.app-layout')

@section('title', 'Record Member Payment')
@section('subtitle', 'Record tithe, offering or other member payment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="relative">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-receipt text-white text-4xl"></i>
                </div>
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-plus text-white text-sm"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent mb-3">Record Payment</h1>
            <p class="text-slate-600 text-xl font-medium">Create a new member contribution record</p>
            <div class="flex items-center justify-center mt-4 space-x-4">
                <div class="flex items-center text-sm text-slate-500">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                    Auto-generated receipt
                </div>
                <div class="flex items-center text-sm text-slate-500">
                    <i class="fas fa-sms mr-2 text-blue-500"></i>
                    SMS notification
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/40 p-10 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full -translate-y-16 translate-x-16 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-purple-100 to-pink-200 rounded-full translate-y-12 -translate-x-12 opacity-50"></div>
            <form method="POST" action="{{ route('member-payments.store') }}" class="space-y-6">
                @csrf

                <!-- Member Selection -->
                <div class="relative">
                    <label for="member_id" class="block text-sm font-semibold text-slate-700 mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            Select Member *
                        </div>
                    </label>
                    <div class="relative">
                        <select name="member_id" id="member_id" required class="w-full rounded-2xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-4 px-5 text-slate-700 font-medium bg-white/80 backdrop-blur-sm @error('member_id') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror">
                            <option value="" class="text-slate-400">Choose a church member...</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }} class="py-2">
                                    {{ $member->first_name }} {{ $member->last_name }} • {{ $member->member_id }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-slate-400"></i>
                        </div>
                    </div>
                    @error('member_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Payment Type and Amount Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Payment Type -->
                    <div class="relative">
                        <label for="payment_type" class="block text-sm font-semibold text-slate-700 mb-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-tag text-white text-sm"></i>
                                </div>
                                Payment Type *
                            </div>
                        </label>
                        <div class="relative">
                            <select name="payment_type" id="payment_type" required class="w-full rounded-2xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 py-4 px-5 text-slate-700 font-medium bg-white/80 backdrop-blur-sm @error('payment_type') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror">
                                <option value="" class="text-slate-400">Choose payment type...</option>
                                @foreach($paymentTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('payment_type') === $key ? 'selected' : '' }} class="py-2">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                <i class="fas fa-chevron-down text-slate-400"></i>
                            </div>
                        </div>
                        @error('payment_type')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div class="relative">
                        <label for="amount" class="block text-sm font-semibold text-slate-700 mb-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-coins text-white text-sm"></i>
                                </div>
                                Amount (GHS) *
                            </div>
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-500 font-semibold">
                                GHS
                            </div>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required 
                                   class="w-full rounded-2xl border-2 border-slate-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 py-4 pl-16 pr-5 text-slate-700 font-semibold text-lg bg-white/80 backdrop-blur-sm @error('amount') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
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
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required 
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
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                            <option value="online" {{ old('payment_method') === 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Auto-Generated Numbers Info -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-magic text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Auto-Generated Documents</h3>
                            <p class="text-slate-600 text-sm">Receipt and invoice numbers will be automatically generated</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center p-3 bg-white/60 rounded-xl">
                            <i class="fas fa-receipt text-green-500 mr-3"></i>
                            <div>
                                <p class="font-semibold text-slate-700 text-sm">Receipt Number</p>
                                <p class="text-slate-500 text-xs">Format: RCP-YYYYMMDD-0001</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-white/60 rounded-xl">
                            <i class="fas fa-file-invoice text-blue-500 mr-3"></i>
                            <div>
                                <p class="font-semibold text-slate-700 text-sm">Invoice Number</p>
                                <p class="text-slate-500 text-xs">Format: INV-YYYY-000001</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="receipt_number" class="block text-sm font-semibold text-slate-700 mb-2">
                            Custom Receipt Number (Optional)
                        </label>
                        <input type="text" name="receipt_number" id="receipt_number" value="{{ old('receipt_number') }}" 
                               class="w-full rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-3 px-4 text-slate-700 bg-white/80 backdrop-blur-sm @error('receipt_number') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                               placeholder="Leave empty for auto-generation">
                        @error('receipt_number')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
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
                              placeholder="Additional details about the payment (optional)">{{ old('description') }}</textarea>
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
                              placeholder="Internal notes (optional)">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 pt-8">
                    <button type="submit" class="flex-1 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative px-8 py-4 text-white font-bold text-lg flex items-center justify-center">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            Record Payment
                        </div>
                    </button>
                    <a href="{{ route('member-payments.index') }}" class="flex-1 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-400 to-slate-500 rounded-2xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-300 to-slate-400 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative px-8 py-4 text-white font-bold text-lg flex items-center justify-center">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-arrow-left text-sm"></i>
                            </div>
                            Cancel
                        </div>
                    </a>
                </div>
            </form>
        </div>

        <!-- SMS Notification Info -->
        <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4 mt-0.5">
                    <i class="fas fa-sms text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">SMS Notification</h3>
                    <p class="text-blue-800 text-sm mb-2">
                        An SMS confirmation will be automatically sent to the member after recording this payment.
                    </p>
                    <ul class="text-blue-800 space-y-1 text-sm">
                        <li>• Payment confirmation with reference number</li>
                        <li>• Amount and payment type details</li>
                        <li>• Thank you message from the church</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Payment Types Info -->
        <div class="mt-6 bg-emerald-50 rounded-2xl p-6 border border-emerald-200">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-4 mt-0.5">
                    <i class="fas fa-info-circle text-emerald-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-emerald-900 mb-2">Payment Types</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-emerald-800 text-sm">
                        <div>
                            <p><strong>Tithe:</strong> 10% of income offering</p>
                            <p><strong>Offering:</strong> General church offering</p>
                            <p><strong>Welfare:</strong> Support for church welfare programs</p>
                        </div>
                        <div>
                            <p><strong>Building Fund:</strong> Church construction/renovation</p>
                            <p><strong>Special Offering:</strong> Special events or causes</p>
                            <p><strong>Thanksgiving:</strong> Gratitude offerings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-focus on member selection
document.addEventListener('DOMContentLoaded', function() {
    const memberSelect = document.getElementById('member_id');
    if (memberSelect) {
        memberSelect.focus();
    }
});
</script>
@endpush
@endsection
