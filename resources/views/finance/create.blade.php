@extends('components.app-layout')

@section('title', 'Add Transaction')
@section('subtitle', 'Record new income or expense transaction')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="relative">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-chart-line text-white text-4xl"></i>
                </div>
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-plus text-white text-sm"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent mb-3">Add Transaction</h1>
            <p class="text-slate-600 text-xl font-medium">Record new financial activity</p>
            <div class="flex items-center justify-center mt-4 space-x-4">
                <div class="flex items-center text-sm text-slate-500">
                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                    Approval workflow
                </div>
                <div class="flex items-center text-sm text-slate-500">
                    <i class="fas fa-tags mr-2 text-blue-500"></i>
                    Category management
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white/95 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/50 p-10 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full -translate-y-16 translate-x-16 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-purple-100 to-pink-200 rounded-full translate-y-12 -translate-x-12 opacity-50"></div>
            <form method="POST" action="{{ route('finance.store') }}" class="space-y-6">
                @csrf

                <!-- Category Selection with Add New -->
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <label for="category_id" class="block text-sm font-semibold text-slate-700">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-tag text-white text-sm"></i>
                                </div>
                                Select Category *
                            </div>
                        </label>
                        <button type="button" onclick="openCategoryModal()" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-lg hover:shadow-md transition-all duration-200">
                            <i class="fas fa-plus mr-1 text-xs"></i>
                            Add Category
                        </button>
                    </div>
                    <div class="relative">
                        <select name="category_id" id="category_id" required class="w-full rounded-2xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-4 px-5 text-slate-700 font-medium bg-white/80 backdrop-blur-sm @error('category_id') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror">
                            <option value="" class="text-slate-400">Choose a category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} data-type="{{ $category->type }}" class="py-2">
                                    <i class="{{ $category->icon }}"></i> {{ $category->name }} ({{ ucfirst($category->type) }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <i class="fas fa-chevron-down text-slate-400"></i>
                        </div>
                    </div>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Transaction Title -->
                <div class="relative">
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-heading text-white text-sm"></i>
                            </div>
                            Transaction Title *
                        </div>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                           class="w-full rounded-2xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 py-4 px-5 text-slate-700 font-medium bg-white/80 backdrop-blur-sm @error('title') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                           placeholder="Enter a descriptive title...">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i>Description
                    </label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Enter transaction description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount and Date Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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

                    <!-- Transaction Date -->
                    <div class="relative">
                        <label for="transaction_date" class="block text-sm font-semibold text-slate-700 mb-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-white text-sm"></i>
                                </div>
                                Transaction Date *
                            </div>
                        </label>
                        <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required 
                               class="w-full rounded-2xl border-2 border-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all duration-200 py-4 px-5 text-slate-700 font-medium bg-white/80 backdrop-blur-sm @error('transaction_date') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror">
                        @error('transaction_date')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method and Receipt Number Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2 text-yellow-500"></i>Payment Method *
                        </label>
                        <select name="payment_method" id="payment_method" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('payment_method') border-red-500 @enderror">
                            <option value="">Select payment method</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                            <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Receipt Number -->
                    <div>
                        <label for="receipt_number" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-receipt mr-2 text-pink-500"></i>Receipt Number
                        </label>
                        <input type="text" name="receipt_number" id="receipt_number" value="{{ old('receipt_number') }}" 
                               class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('receipt_number') border-red-500 @enderror"
                               placeholder="Enter receipt number">
                        @error('receipt_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-2 text-orange-500"></i>Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                              placeholder="Additional notes or comments">{{ old('notes') }}</textarea>
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
                            Save Transaction
                        </div>
                    </button>
                    <a href="{{ route('finance.transactions') }}" class="flex-1 relative overflow-hidden group">
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

        <!-- Help Text -->
        <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-4 mt-0.5">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Transaction Guidelines</h3>
                    <ul class="text-blue-800 space-y-1 text-sm">
                        <li>• All transactions require approval before being reflected in reports</li>
                        <li>• Choose the appropriate category to ensure accurate financial reporting</li>
                        <li>• Include receipt numbers when available for better record keeping</li>
                        <li>• Use clear, descriptive titles for easy identification</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-800">Quick Add Category</h3>
                    <button onclick="closeCategoryModal()" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>

                <form id="quickCategoryForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Category Name *</label>
                            <input type="text" name="name" required class="w-full rounded-xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 py-3 px-4 text-slate-700 font-medium" placeholder="Enter name">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Type *</label>
                            <select name="type" required class="w-full rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-3 px-4 text-slate-700 font-medium">
                                <option value="">Select type</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Color *</label>
                            <input type="color" name="color" value="#3B82F6" required class="w-full h-12 rounded-lg border-2 border-slate-200 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Icon *</label>
                            <select name="icon" required class="w-full rounded-xl border-2 border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-200 py-3 px-4 text-slate-700 font-medium">
                                <option value="">Select icon</option>
                                <option value="fas fa-coins">💰 Coins</option>
                                <option value="fas fa-money-bill">💵 Money Bill</option>
                                <option value="fas fa-chart-line">📈 Chart Line</option>
                                <option value="fas fa-shopping-cart">🛒 Shopping Cart</option>
                                <option value="fas fa-home">🏠 Home</option>
                                <option value="fas fa-car">🚗 Car</option>
                                <option value="fas fa-utensils">🍽️ Food</option>
                                <option value="fas fa-heart">❤️ Heart</option>
                                <option value="fas fa-gift">🎁 Gift</option>
                                <option value="fas fa-tools">🔧 Tools</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 py-3 px-4 text-slate-700" placeholder="Optional description"></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 px-6 rounded-xl hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Create Category
                        </button>
                        <button type="button" onclick="closeCategoryModal()" class="flex-1 bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCategoryModal() {
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('quickCategoryForm').reset();
}

// Handle quick category form submission
document.getElementById('quickCategoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route("finance.categories.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            
            // Add new category to select dropdown
            const categorySelect = document.getElementById('category_id');
            const option = document.createElement('option');
            option.value = result.id;
            option.textContent = `${result.name} (${result.type.charAt(0).toUpperCase() + result.type.slice(1)})`;
            option.selected = true;
            categorySelect.appendChild(option);
            
            // Close modal and show success message
            closeCategoryModal();
            
            // Show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.innerHTML = '<i class="fas fa-check mr-2"></i>Category created successfully!';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        } else {
            throw new Error('Failed to create category');
        }
    } catch (error) {
        alert('Error creating category. Please try again.');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});

// Close modal when clicking outside
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCategoryModal();
    }
});
</script>
@endpush
@endsection
