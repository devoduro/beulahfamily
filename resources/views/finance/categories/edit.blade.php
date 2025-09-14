@extends('components.app-layout')

@section('title', 'Edit Finance Category')
@section('subtitle', 'Update category details')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl text-white text-3xl" style="background: {{ $category->color }};">
                <i class="{{ $category->icon }}"></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-purple-900 to-pink-900 bg-clip-text text-transparent">Edit Category</h1>
            <p class="text-slate-600 text-lg mt-2">Update "{{ $category->name }}" category details</p>
        </div>

        <!-- Form -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/40 p-8">
            <form method="POST" action="{{ route('finance.categories.update', $category) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Category Name and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-tag mr-2 text-purple-500"></i>Category Name *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required 
                               class="w-full rounded-xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 py-3 px-4 text-slate-700 font-medium @error('name') border-red-400 @enderror"
                               placeholder="Enter category name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-exchange-alt mr-2 text-blue-500"></i>Category Type *
                        </label>
                        <select name="type" id="type" required class="w-full rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-3 px-4 text-slate-700 font-medium @error('type') border-red-400 @enderror">
                            <option value="">Select type</option>
                            <option value="income" {{ old('type', $category->type) === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ old('type', $category->type) === 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Color and Icon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="color" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-palette mr-2 text-pink-500"></i>Color *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="color" id="color" value="{{ old('color', $category->color) }}" required 
                                   class="w-16 h-12 rounded-lg border-2 border-slate-200 cursor-pointer @error('color') border-red-400 @enderror">
                            <input type="text" id="color-text" value="{{ old('color', $category->color) }}" 
                                   class="flex-1 rounded-xl border-2 border-slate-200 focus:border-pink-500 focus:ring-4 focus:ring-pink-100 transition-all duration-200 py-3 px-4 text-slate-700 font-mono text-sm"
                                   placeholder="#3B82F6" readonly>
                        </div>
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-icons mr-2 text-orange-500"></i>Icon *
                        </label>
                        <div class="relative">
                            <select name="icon" id="icon" required class="w-full rounded-xl border-2 border-slate-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-200 py-3 px-4 text-slate-700 font-medium @error('icon') border-red-400 @enderror">
                                <option value="">Select icon</option>
                                <option value="fas fa-coins" {{ old('icon', $category->icon) === 'fas fa-coins' ? 'selected' : '' }}>💰 Coins</option>
                                <option value="fas fa-money-bill" {{ old('icon', $category->icon) === 'fas fa-money-bill' ? 'selected' : '' }}>💵 Money Bill</option>
                                <option value="fas fa-chart-line" {{ old('icon', $category->icon) === 'fas fa-chart-line' ? 'selected' : '' }}>📈 Chart Line</option>
                                <option value="fas fa-shopping-cart" {{ old('icon', $category->icon) === 'fas fa-shopping-cart' ? 'selected' : '' }}>🛒 Shopping Cart</option>
                                <option value="fas fa-home" {{ old('icon', $category->icon) === 'fas fa-home' ? 'selected' : '' }}>🏠 Home</option>
                                <option value="fas fa-car" {{ old('icon', $category->icon) === 'fas fa-car' ? 'selected' : '' }}>🚗 Car</option>
                                <option value="fas fa-utensils" {{ old('icon', $category->icon) === 'fas fa-utensils' ? 'selected' : '' }}>🍽️ Food</option>
                                <option value="fas fa-heart" {{ old('icon', $category->icon) === 'fas fa-heart' ? 'selected' : '' }}>❤️ Heart</option>
                                <option value="fas fa-gift" {{ old('icon', $category->icon) === 'fas fa-gift' ? 'selected' : '' }}>🎁 Gift</option>
                                <option value="fas fa-tools" {{ old('icon', $category->icon) === 'fas fa-tools' ? 'selected' : '' }}>🔧 Tools</option>
                                <option value="fas fa-graduation-cap" {{ old('icon', $category->icon) === 'fas fa-graduation-cap' ? 'selected' : '' }}>🎓 Education</option>
                                <option value="fas fa-medkit" {{ old('icon', $category->icon) === 'fas fa-medkit' ? 'selected' : '' }}>🏥 Medical</option>
                            </select>
                            <div id="icon-preview" class="absolute right-12 top-1/2 transform -translate-y-1/2 text-lg"></div>
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i>Description
                    </label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full rounded-xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 py-3 px-4 text-slate-700 @error('description') border-red-400 @enderror"
                              placeholder="Optional description for this category">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order and Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-sort-numeric-up mr-2 text-green-500"></i>Sort Order
                        </label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" 
                               class="w-full rounded-xl border-2 border-slate-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 py-3 px-4 text-slate-700 @error('sort_order') border-red-400 @enderror"
                               placeholder="0">
                        <p class="mt-1 text-sm text-slate-500">Lower numbers appear first in lists</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-toggle-on mr-2 text-blue-500"></i>Status
                        </label>
                        <select name="is_active" id="is_active" class="w-full rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-3 px-4 text-slate-700 font-medium @error('is_active') border-red-400 @enderror">
                            <option value="1" {{ old('is_active', $category->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $category->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Category Stats -->
                @if($category->transactions_count > 0)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-blue-800 font-medium">
                                This category is used in {{ $category->transactions_count }} transaction(s). 
                                Changes will affect existing transactions.
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>
                        Update Category
                    </button>
                    <a href="{{ route('finance.categories.index') }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <form method="POST" action="{{ route('finance.categories.toggle', $category) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full {{ $category->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white py-3 px-4 rounded-xl transition-colors font-medium">
                        <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} mr-2"></i>
                        {{ $category->is_active ? 'Disable Category' : 'Enable Category' }}
                    </button>
                </form>

                <a href="{{ route('finance.categories.create') }}" class="w-full bg-blue-500 text-white py-3 px-4 rounded-xl hover:bg-blue-600 transition-colors font-medium text-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Category
                </a>

                @if(($category->transactions_count ?? 0) === 0)
                    <form method="POST" action="{{ route('finance.categories.destroy', $category) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 text-white py-3 px-4 rounded-xl hover:bg-red-600 transition-colors font-medium">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Category
                        </button>
                    </form>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-500 py-3 px-4 rounded-xl cursor-not-allowed font-medium" title="Cannot delete category with transactions">
                        <i class="fas fa-trash mr-2"></i>
                        Cannot Delete
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color-text');
    const iconSelect = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');

    // Update color text when color picker changes
    colorInput.addEventListener('input', function() {
        colorText.value = this.value;
    });

    // Update icon preview
    function updateIconPreview() {
        const selectedIcon = iconSelect.value;
        if (selectedIcon) {
            iconPreview.innerHTML = `<i class="${selectedIcon}"></i>`;
        } else {
            iconPreview.innerHTML = '';
        }
    }

    iconSelect.addEventListener('change', updateIconPreview);
    updateIconPreview(); // Initial load
});
</script>
@endpush
@endsection
