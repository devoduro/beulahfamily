@extends('components.app-layout')

@section('title', 'Finance Categories')
@section('subtitle', 'Manage income and expense categories')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-purple-900 to-pink-900 bg-clip-text text-transparent">Finance Categories</h1>
                <p class="text-slate-600 text-lg mt-1">Organize your income and expense categories</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                <a href="{{ route('finance.categories.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>
                    Add Category
                </a>
                <a href="{{ route('finance.index') }}" class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm text-slate-700 font-semibold rounded-xl border border-slate-200 hover:bg-white hover:shadow-md transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Finance
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/40">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tags text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total Categories</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $categories->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/40">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-up text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Income Categories</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $categories->where('type', 'income')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/40">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-down text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Expense Categories</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $categories->where('type', 'expense')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-6 shadow-xl border border-white/40">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Active Categories</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $categories->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full rounded-xl border-2 border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 py-2 px-4"
                           placeholder="Search categories...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                    <select name="type" class="w-full rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 py-2 px-4">
                        <option value="">All Types</option>
                        <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-2 border-slate-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 py-2 px-4">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-2 px-4 rounded-xl hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('finance.categories.index') }}" class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Categories Grid -->
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Category Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-xl" style="background: {{ $category->color }};">
                                    <i class="{{ $category->icon }}"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-bold text-slate-900">{{ $category->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas fa-{{ $category->type === 'income' ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                        {{ ucfirst($category->type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas fa-{{ $category->is_active ? 'check-circle' : 'pause-circle' }} mr-1"></i>
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <!-- Description -->
                        @if($category->description)
                            <p class="text-slate-600 text-sm mb-4">{{ $category->description }}</p>
                        @endif

                        <!-- Stats -->
                        <div class="bg-slate-50 rounded-xl p-3 mb-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600">Transactions:</span>
                                <span class="font-semibold text-slate-900">{{ $category->transactions_count ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm mt-1">
                                <span class="text-slate-600">Sort Order:</span>
                                <span class="font-semibold text-slate-900">{{ $category->sort_order }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('finance.categories.edit', $category) }}" 
                               class="flex-1 bg-blue-500 text-white text-center py-2 px-3 rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            
                            <form method="POST" action="{{ route('finance.categories.toggle', $category) }}" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full {{ $category->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white py-2 px-3 rounded-lg transition-colors text-sm font-medium">
                                    <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} mr-1"></i>
                                    {{ $category->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            
                            @if(($category->transactions_count ?? 0) === 0)
                                <form method="POST" action="{{ route('finance.categories.destroy', $category) }}" 
                                      onsubmit="return confirm('Are you sure you want to delete this category?')" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 text-white py-2 px-3 rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            @else
                                <button disabled class="flex-1 bg-gray-300 text-gray-500 py-2 px-3 rounded-lg cursor-not-allowed text-sm font-medium" title="Cannot delete category with transactions">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $categories->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-12 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-tags text-4xl text-purple-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">No Categories Found</h3>
                <p class="text-slate-600 mb-8 max-w-md mx-auto">
                    @if(request()->hasAny(['search', 'type', 'status']))
                        No categories match your current filters. Try adjusting your search criteria.
                    @else
                        Get started by creating your first finance category to organize your transactions.
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('finance.categories.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        Create First Category
                    </a>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('finance.categories.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-slate-700 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
