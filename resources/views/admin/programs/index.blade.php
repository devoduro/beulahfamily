@extends('components.app-layout')

@section('title', 'Programs Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Enhanced Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-3xl shadow-2xl mb-8">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
            <div class="relative px-8 py-12">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-3xl text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold text-white">Programs Management</h1>
                                <p class="text-blue-100 text-lg">Comprehensive program and registration management</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-blue-100 text-sm font-medium">Total Programs</p>
                                        <p class="text-2xl font-bold text-white">{{ $stats['total'] ?? 0 }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-blue-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-calendar text-blue-200"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-100 text-sm font-medium">Published</p>
                                        <p class="text-2xl font-bold text-white">{{ $stats['published'] ?? 0 }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-green-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-200"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-purple-100 text-sm font-medium">Registrations</p>
                                        <p class="text-2xl font-bold text-white">{{ $stats['total_registrations'] ?? 0 }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-purple-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-users text-purple-200"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-yellow-100 text-sm font-medium">Pending</p>
                                        <p class="text-2xl font-bold text-white">{{ $stats['pending_registrations'] ?? 0 }}</p>
                                    </div>
                                    <div class="w-10 h-10 bg-yellow-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-clock text-yellow-200"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-900 font-semibold rounded-2xl hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2"></i>
                            Create Program
                        </a>
                        <button onclick="toggleView()" class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-2xl hover:bg-white/30 transition-all duration-200 border border-white/30">
                            <i class="fas fa-th-large mr-2" id="viewIcon"></i>
                            <span id="viewText">Card View</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Filters -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50 p-8 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Filter & Search Programs</h2>
                <button onclick="toggleFilters()" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-sliders-h"></i>
                </button>
            </div>
            
            <!-- Main Search -->
            <div class="relative mb-6">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </div>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                       class="block w-full pl-12 pr-4 py-4 text-lg border-0 bg-gray-50 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:bg-white transition-all duration-200" 
                       placeholder="Search programs by name, description, or venue...">
            </div>

            <!-- Advanced Filters -->
            <div id="advancedFilters" class="hidden">
                <form method="GET" action="{{ route('admin.programs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Program Type</label>
                        <select name="type" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                            <option value="">All Types</option>
                            @foreach(\App\Models\Program::getProgramTypeOptions() as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                        <select name="status" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                            <option value="">All Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>✅ Published</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>🔄 Ongoing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🎯 Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Sort By</label>
                        <select name="sort" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                            <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Start Date</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                            <option value="registrations_count" {{ request('sort') == 'registrations_count' ? 'selected' : '' }}>Registrations</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Order</label>
                        <select name="order" class="block w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white">
                            <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-filter mr-2"></i>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Programs Display Container -->
        <div id="programsContainer">
            <!-- Table View (Default) -->
            <div id="tableView" class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50">
                <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Programs Overview</h3>
                            <p class="text-sm text-gray-600">Comprehensive program management and analytics</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600">{{ $programs->total() }} programs total</span>
                            <button onclick="exportPrograms()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-download mr-2"></i>
                                Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    @if($programs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Program</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Registrations</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Fee</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($programs as $program)
                                        <tr class="hover:bg-blue-50 transition-colors duration-200">
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                <div class="flex items-center space-x-4">
                                                    @if($program->hasFlyer())
                                                        <img src="{{ $program->flyer_url }}" alt="{{ $program->name }}" class="w-12 h-12 rounded-xl object-cover border-2 border-gray-200">
                                                    @else
                                                        @php
                                                            $typeColors = [
                                                                'ergates_conference' => 'from-orange-500 to-red-500',
                                                                'annual_retreat' => 'from-green-500 to-blue-500',
                                                                'conference' => 'from-blue-500 to-purple-500',
                                                                'workshop' => 'from-yellow-500 to-orange-500',
                                                                'seminar' => 'from-purple-500 to-pink-500',
                                                                'retreat' => 'from-green-500 to-teal-500',
                                                                'other' => 'from-gray-500 to-slate-500'
                                                            ];
                                                            $typeColor = $typeColors[$program->type] ?? $typeColors['other'];
                                                        @endphp
                                                        <div class="w-12 h-12 bg-gradient-to-br {{ $typeColor }} rounded-xl flex items-center justify-center">
                                                            <i class="fas fa-calendar-alt text-white text-lg"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900">{{ $program->name }}</div>
                                                        @if($program->venue)
                                                            <div class="text-sm text-gray-500 flex items-center mt-1">
                                                                <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i> 
                                                                {{ Str::limit($program->venue, 30) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800">
                                                    {{ $program->formatted_type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $program->date_range }}</div>
                                                @if($program->time_range)
                                                    <div class="text-sm text-gray-500 flex items-center mt-1">
                                                        <i class="fas fa-clock mr-1 text-gray-400"></i>
                                                        {{ $program->time_range }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                @php
                                                    $statusConfig = [
                                                        'draft' => ['bg' => 'from-gray-100 to-gray-200', 'text' => 'text-gray-800', 'icon' => 'fas fa-edit'],
                                                        'published' => ['bg' => 'from-green-100 to-emerald-200', 'text' => 'text-green-800', 'icon' => 'fas fa-check-circle'],
                                                        'ongoing' => ['bg' => 'from-blue-100 to-cyan-200', 'text' => 'text-blue-800', 'icon' => 'fas fa-play-circle'],
                                                        'completed' => ['bg' => 'from-purple-100 to-violet-200', 'text' => 'text-purple-800', 'icon' => 'fas fa-flag-checkered'],
                                                        'cancelled' => ['bg' => 'from-red-100 to-rose-200', 'text' => 'text-red-800', 'icon' => 'fas fa-times-circle']
                                                    ];
                                                    $config = $statusConfig[$program->status] ?? $statusConfig['draft'];
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r {{ $config['bg'] }} {{ $config['text'] }}">
                                                    <i class="{{ $config['icon'] }} mr-1"></i>
                                                    {{ ucfirst($program->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <div class="text-sm font-bold text-gray-900">
                                                        {{ $program->registrations->count() }}
                                                        @if($program->max_participants)
                                                            <span class="text-gray-400">/ {{ $program->max_participants }}</span>
                                                        @endif
                                                    </div>
                                                    @if($program->max_participants)
                                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300" 
                                                                 style="width: {{ min(($program->registrations->count() / $program->max_participants) * 100, 100) }}%"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Approved: {{ $program->approvedRegistrations->count() }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                @if($program->registration_fee > 0)
                                                    <div class="text-sm font-bold text-gray-900">₵{{ number_format($program->registration_fee, 2) }}</div>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-green-100 to-emerald-200 text-green-800">
                                                        <i class="fas fa-gift mr-1"></i>Free
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-6 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('admin.programs.show', $program) }}" 
                                                       class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors" 
                                                       title="View Details">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('admin.programs.edit', $program) }}" 
                                                       class="inline-flex items-center justify-center w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition-colors" 
                                                       title="Edit Program">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                    <a href="{{ route('admin.programs.registrations', $program) }}" 
                                                       class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors" 
                                                       title="View Registrations">
                                                        <i class="fas fa-users text-sm"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" 
                                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this program? This action cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="delete-btn inline-flex items-center justify-center w-8 h-8 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors" 
                                                                title="Delete Program">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        <div class="mt-8 flex justify-center">
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 p-4">
                                {{ $programs->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-calendar-alt text-4xl text-purple-500"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">No programs found</h3>
                            <p class="text-gray-600 mb-8 text-lg">Get started by creating your first program.</p>
                            <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-plus mr-3"></i>
                                Create First Program
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card View -->
            <div id="cardView" class="hidden grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($programs as $program)
                    <div class="group bg-white/90 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-200/50 p-6 hover:shadow-2xl hover:bg-white transition-all duration-500 transform hover:-translate-y-2">
                        <!-- Program Header -->
                        @if($program->hasFlyer())
                            <div class="h-48 overflow-hidden rounded-2xl mb-6">
                                <img src="{{ $program->flyer_url }}" alt="{{ $program->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between mb-4">
                            @php
                                $typeColors = [
                                    'ergates_conference' => 'from-orange-500 to-red-500',
                                    'annual_retreat' => 'from-green-500 to-blue-500',
                                    'conference' => 'from-blue-500 to-purple-500',
                                    'workshop' => 'from-yellow-500 to-orange-500',
                                    'seminar' => 'from-purple-500 to-pink-500',
                                    'retreat' => 'from-green-500 to-teal-500',
                                    'other' => 'from-gray-500 to-slate-500'
                                ];
                                $typeColor = $typeColors[$program->type] ?? $typeColors['other'];
                            @endphp
                            <div class="w-16 h-16 bg-gradient-to-br {{ $typeColor }} rounded-2xl flex flex-col items-center justify-center text-white shadow-lg">
                                <div class="text-xs font-bold uppercase">{{ $program->start_date ? $program->start_date->format('M') : 'TBD' }}</div>
                                <div class="text-lg font-black">{{ $program->start_date ? $program->start_date->format('d') : '?' }}</div>
                            </div>
                            @php
                                $statusConfig = [
                                    'draft' => ['bg' => 'from-gray-100 to-gray-200', 'text' => 'text-gray-800'],
                                    'published' => ['bg' => 'from-green-100 to-emerald-200', 'text' => 'text-green-800'],
                                    'ongoing' => ['bg' => 'from-blue-100 to-cyan-200', 'text' => 'text-blue-800'],
                                    'completed' => ['bg' => 'from-purple-100 to-violet-200', 'text' => 'text-purple-800'],
                                    'cancelled' => ['bg' => 'from-red-100 to-rose-200', 'text' => 'text-red-800']
                                ];
                                $config = $statusConfig[$program->status] ?? $statusConfig['draft'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gradient-to-r {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ ucfirst($program->status) }}
                            </span>
                        </div>

                        <!-- Program Title -->
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-700 transition-colors mb-3">{{ $program->name }}</h3>
                        
                        <!-- Program Description -->
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $program->description ?: 'No description available.' }}</p>
                        
                        <!-- Program Details -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-purple-600"></i>
                                </div>
                                <span>{{ $program->date_range }}</span>
                            </div>
                            @if($program->venue)
                                <div class="flex items-center text-sm text-gray-600">
                                    <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-map-marker-alt text-pink-600"></i>
                                    </div>
                                    <span>{{ $program->venue }}</span>
                                </div>
                            @endif
                            <div class="flex items-center text-sm text-gray-600">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                                <span>{{ $program->registrations->count() }} registered</span>
                            </div>
                        </div>

                        <!-- Program Actions -->
                        <div class="space-y-3">
                            <a href="{{ route('admin.programs.show', $program) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                            <div class="grid grid-cols-3 gap-2">
                                <a href="{{ route('admin.programs.edit', $program) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.programs.registrations', $program) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                                    <i class="fas fa-users"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" 
                                      class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Program Footer -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $program->formatted_type }}</span>
                                @if($program->registration_fee > 0)
                                    <span class="font-semibold text-green-600">₵{{ number_format($program->registration_fee, 2) }}</span>
                                @else
                                    <span class="font-semibold text-blue-600">Free</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
<!-- JavaScript for Interactive Features -->
<script>
let currentView = 'table';
let filtersVisible = false;

function toggleView() {
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    const viewIcon = document.getElementById('viewIcon');
    const viewText = document.getElementById('viewText');
    
    if (currentView === 'table') {
        tableView.classList.add('hidden');
        cardView.classList.remove('hidden');
        viewIcon.className = 'fas fa-table mr-2';
        viewText.textContent = 'Table View';
        currentView = 'card';
    } else {
        cardView.classList.add('hidden');
        tableView.classList.remove('hidden');
        viewIcon.className = 'fas fa-th-large mr-2';
        viewText.textContent = 'Card View';
        currentView = 'table';
    }
}

function toggleFilters() {
    const filters = document.getElementById('advancedFilters');
    filtersVisible = !filtersVisible;
    
    if (filtersVisible) {
        filters.classList.remove('hidden');
        filters.classList.add('animate-fadeIn');
    } else {
        filters.classList.add('hidden');
        filters.classList.remove('animate-fadeIn');
    }
}

function exportPrograms() {
    // Create export URL with current filters
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    
    // Create temporary link and trigger download
    const exportUrl = `{{ route('admin.programs.index') }}?${params.toString()}`;
    window.open(exportUrl, '_blank');
}

// Real-time search with debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const searchValue = this.value;
        const url = new URL(window.location.href);
        
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        
        // Update URL without page reload
        window.history.pushState({}, '', url);
        
        // Trigger search after 500ms delay
        if (searchValue.length >= 2 || searchValue.length === 0) {
            window.location.href = url;
        }
    }, 500);
});

// Enhanced animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    const cards = document.querySelectorAll('.group');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add loading states to buttons (exclude delete buttons)
    const buttons = document.querySelectorAll('button[type="submit"]:not(.delete-btn)');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.disabled) {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                this.disabled = true;
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            }
        });
    });
});

// Add custom CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Enhanced table hover effects */
    tbody tr:hover {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Progress bar animations */
    .progress-bar {
        transition: width 0.6s ease-in-out;
    }
    
    /* Button hover effects */
    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Card animations */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    /* Status badge animations */
    .status-badge {
        transition: all 0.2s ease;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
`;
document.head.appendChild(style);
</script>

@endsection
