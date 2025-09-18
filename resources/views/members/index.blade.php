@extends('components.app-layout')

@section('title', 'Members')
@section('subtitle', 'Manage church members and their information')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Analytics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <div class="group bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 group-hover:scale-110 transition-all duration-300 shadow-lg">
                            <i class="fas fa-users text-3xl group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Members</p>
                            <p class="text-4xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['total_members'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-blue-100">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line mr-2 group-hover:animate-pulse"></i>
                            <span class="text-sm font-medium">Active community</span>
                        </div>
                        <div class="w-3 h-3 bg-blue-300 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>

            <div class="group bg-gradient-to-br from-green-500 via-green-600 to-emerald-700 rounded-3xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 group-hover:scale-110 transition-all duration-300 shadow-lg">
                            <i class="fas fa-user-plus text-3xl group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-green-100 text-sm font-medium uppercase tracking-wider">New This Month</p>
                            <p class="text-4xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['new_members'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-green-100">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus mr-2 group-hover:animate-pulse"></i>
                            <span class="text-sm font-medium">Recent additions</span>
                        </div>
                        <div class="w-3 h-3 bg-green-300 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>

            <div class="group bg-gradient-to-br from-purple-500 via-purple-600 to-violet-700 rounded-3xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 group-hover:scale-110 transition-all duration-300 shadow-lg">
                            <i class="fas fa-home text-3xl group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-purple-100 text-sm font-medium uppercase tracking-wider">Total Families</p>
                            <p class="text-4xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['total_families'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-purple-100">
                        <div class="flex items-center">
                            <i class="fas fa-heart mr-2 group-hover:animate-pulse"></i>
                            <span class="text-sm font-medium">Family units</span>
                        </div>
                        <div class="w-3 h-3 bg-purple-300 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>

            <div class="group bg-gradient-to-br from-orange-500 via-orange-600 to-red-600 rounded-3xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 group-hover:scale-110 transition-all duration-300 shadow-lg">
                            <i class="fas fa-hands-helping text-3xl group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-orange-100 text-sm font-medium uppercase tracking-wider">Active Ministries</p>
                            <p class="text-4xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['active_ministries'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-orange-100">
                        <div class="flex items-center">
                            <i class="fas fa-pray mr-2 group-hover:animate-pulse"></i>
                            <span class="text-sm font-medium">Service areas</span>
                        </div>
                        <div class="w-3 h-3 bg-orange-300 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chapter Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="group bg-gradient-to-br from-cyan-500 via-cyan-600 to-blue-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                        <i class="fas fa-city text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-cyan-100 text-sm font-medium uppercase tracking-wide">ACCRA Chapter</p>
                        <p class="text-3xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['chapter_stats']['ACCRA'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-cyan-100">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        <span class="text-sm font-medium">Members</span>
                    </div>
                    <div class="w-2 h-2 bg-cyan-300 rounded-full animate-pulse"></div>
                </div>
            </div>

            <div class="group bg-gradient-to-br from-teal-500 via-teal-600 to-green-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                        <i class="fas fa-mountain text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-teal-100 text-sm font-medium uppercase tracking-wide">KUMASI Chapter</p>
                        <p class="text-3xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['chapter_stats']['KUMASI'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-teal-100">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        <span class="text-sm font-medium">Members</span>
                    </div>
                    <div class="w-2 h-2 bg-teal-300 rounded-full animate-pulse"></div>
                </div>
            </div>

            <div class="group bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                        <i class="fas fa-map-marker-alt text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-indigo-100 text-sm font-medium uppercase tracking-wide">NEW JESSY Chapter</p>
                        <p class="text-3xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['chapter_stats']['NEW JESSY'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-indigo-100">
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        <span class="text-sm font-medium">Members</span>
                    </div>
                    <div class="w-2 h-2 bg-indigo-300 rounded-full animate-pulse"></div>
                </div>
            </div>

            <div class="group bg-gradient-to-br from-rose-500 via-pink-600 to-red-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                        <i class="fas fa-graduation-cap text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-rose-100 text-sm font-medium uppercase tracking-wide">STUDENTS Chapter</p>
                        <p class="text-3xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $stats['chapter_stats']['STUDENTS'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-rose-100">
                    <div class="flex items-center">
                        <i class="fas fa-user-graduate mr-2"></i>
                        <span class="text-sm font-medium">Students</span>
                    </div>
                    <div class="w-2 h-2 bg-rose-300 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Header with Actions -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="space-y-3">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">Church Members</h1>
                        <p class="text-gray-600 text-lg mt-1">Manage your congregation members and their details</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- View Toggle -->
                <div class="flex items-center bg-white/80 backdrop-blur-sm rounded-2xl p-1 shadow-lg border border-white/50">
                    <button onclick="toggleView('grid')" id="grid-btn" class="flex items-center px-4 py-2 rounded-xl transition-all duration-200 bg-blue-600 text-white shadow-md">
                        <i class="fas fa-th-large mr-2"></i>
                        Grid
                    </button>
                    <button onclick="toggleView('list')" id="list-btn" class="flex items-center px-4 py-2 rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-list mr-2"></i>
                        List
                    </button>
                </div>
                
                <!-- Export Dropdown -->
                <div class="relative" id="export-dropdown">
                    <button onclick="toggleExportDropdown()" class="flex items-center px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-download mr-2"></i>
                        Export
                        <i class="fas fa-chevron-down ml-2 text-sm"></i>
                    </button>
                    
                    <div id="export-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 z-50">
                        <div class="py-2">
                            <button onclick="exportMembers('csv')" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center transition-colors duration-150">
                                <i class="fas fa-file-csv text-green-600 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">CSV Export</div>
                                    <div class="text-xs text-gray-500">Comma-separated values</div>
                                </div>
                            </button>
                            
                            <button onclick="exportMembers('excel')" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center transition-colors duration-150">
                                <i class="fas fa-file-excel text-green-600 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Excel Export</div>
                                    <div class="text-xs text-gray-500">Microsoft Excel format</div>
                                </div>
                            </button>
                            
                            <button onclick="exportMembers('pdf')" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center transition-colors duration-150">
                                <i class="fas fa-file-pdf text-red-600 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">PDF Export</div>
                                    <div class="text-xs text-gray-500">Printable directory</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('members.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                    <i class="fas fa-user-plus mr-3 text-lg"></i>
                    Add Member
                    <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
                </a>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="group bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-500">
            <form method="GET" action="{{ route('members.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Search Members</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400 text-lg"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-12 pr-4 py-4 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md placeholder-gray-400 text-lg" placeholder="Search by name, email, phone...">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Membership Status</label>
                        <div class="relative">
                            <select name="status" class="block w-full px-4 py-4 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md appearance-none">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="visitor" {{ request('status') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800">Membership Type</label>
                        <div class="relative">
                            <select name="type" class="block w-full px-4 py-4 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md appearance-none">
                                <option value="">All Types</option>
                                <option value="full" {{ request('type') == 'full' ? 'selected' : '' }}>Full Member</option>
                                <option value="associate" {{ request('type') == 'associate' ? 'selected' : '' }}>Associate</option>
                                <option value="youth" {{ request('type') == 'youth' ? 'selected' : '' }}>Youth</option>
                                <option value="child" {{ request('type') == 'child' ? 'selected' : '' }}>Child</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-search mr-2"></i>
                            Search
                        </button>
                    </div>
                </div>
                
                <!-- Advanced Filters (Collapsible) -->
                <div x-data="{ showAdvanced: false }" class="border-t border-gray-200/30 pt-6">
                    <button type="button" @click="showAdvanced = !showAdvanced" class="flex items-center px-4 py-2 bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 hover:from-indigo-100 hover:to-purple-100 font-semibold rounded-xl transition-all duration-300">
                        <i class="fas fa-sliders-h mr-2"></i>
                        Advanced Filters
                        <i class="fas fa-chevron-down ml-2 transition-transform duration-300" :class="{ 'rotate-180': showAdvanced }"></i>
                    </button>
                    
                    <div x-show="showAdvanced" x-transition class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Gender</label>
                            <select name="gender" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                                <option value="">All Genders</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Age Range</label>
                            <select name="age_range" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                                <option value="">All Ages</option>
                                <option value="0-12" {{ request('age_range') == '0-12' ? 'selected' : '' }}>Children (0-12)</option>
                                <option value="13-25" {{ request('age_range') == '13-25' ? 'selected' : '' }}>Youth (13-25)</option>
                                <option value="26-59" {{ request('age_range') == '26-59' ? 'selected' : '' }}>Adults (26-59)</option>
                                <option value="60-120" {{ request('age_range') == '60-120' ? 'selected' : '' }}>Seniors (60+)</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Chapter</label>
                            <select name="chapter" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                                <option value="">All Chapters</option>
                                <option value="ACCRA" {{ request('chapter') == 'ACCRA' ? 'selected' : '' }}>ACCRA</option>
                                <option value="KUMASI" {{ request('chapter') == 'KUMASI' ? 'selected' : '' }}>KUMASI</option>
                                <option value="NEW JESSY" {{ request('chapter') == 'NEW JESSY' ? 'selected' : '' }}>NEW JESSY</option>
                                <option value="STUDENTS" {{ request('chapter') == 'STUDENTS' ? 'selected' : '' }}>STUDENTS</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Family</label>
                            <select name="family_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                                <option value="">All Families</option>
                                @foreach($families ?? [] as $family)
                                    <option value="{{ $family->id }}" {{ request('family_id') == $family->id ? 'selected' : '' }}>
                                        {{ $family->family_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-800">Ministry</label>
                            <select name="ministry_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                                <option value="">All Ministries</option>
                                @foreach($ministries ?? [] as $ministry)
                                    <option value="{{ $ministry->id }}" {{ request('ministry_id') == $ministry->id ? 'selected' : '' }}>
                                        {{ $ministry->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filters Display -->
                @if(request()->hasAny(['search', 'status', 'type', 'gender', 'age_range', 'chapter', 'family_id', 'ministry_id']))
                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-200/30">
                        <span class="text-sm font-medium text-gray-700">Active filters:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Search: "{{ request('search') }}"
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Status: {{ ucfirst(request('status')) }}
                                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        @if(request('type'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Type: {{ ucfirst(request('type')) }}
                                <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        @if(request('chapter'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                Chapter: {{ request('chapter') }}
                                <a href="{{ request()->fullUrlWithQuery(['chapter' => null]) }}" class="ml-1 text-cyan-600 hover:text-cyan-800">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        <a href="{{ route('members.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            <i class="fas fa-times-circle mr-1"></i>Clear all
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Members Display Container -->
        <div id="members-container" class="space-y-8">
            <!-- Grid View (Default) -->
            <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @forelse($members ?? [] as $member)
                    <div class="member-card group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8 hover:shadow-2xl transition-all duration-500 hover:bg-white/90 hover:-translate-y-2 hover:scale-105">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 overflow-hidden">
                                        @if($member->photo_path ?? false)
                                            <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-white text-xl"></i>
                                        @endif
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-5 h-5 {{ ($member->membership_status ?? 'active') === 'active' ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : 'bg-gradient-to-r from-gray-400 to-gray-500' }} rounded-full border-2 border-white"></div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-700 transition-colors duration-300">{{ $member->full_name ?? 'John Doe' }}</h3>
                                    <p class="text-sm text-gray-500 font-medium">{{ $member->member_id ?? 'M2025001' }}</p>
                                    @if($member->date_of_birth)
                                        <p class="text-xs text-gray-400">Age: {{ \Carbon\Carbon::parse($member->date_of_birth)->age }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('members.show', $member->id ?? 1) }}" class="p-3 text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-300 hover:scale-110" title="View Details">
                                    <i class="fas fa-eye text-lg"></i>
                                </a>
                                <a href="{{ route('members.edit', $member->id ?? 1) }}" class="p-3 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-300 hover:scale-110" title="Edit Member">
                                    <i class="fas fa-edit text-lg"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if($member->email)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-blue-500"></i>
                                </div>
                                <span class="font-medium truncate">{{ $member->email }}</span>
                            </div>
                            @endif
                            @if($member->phone)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-100 to-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-phone text-green-500"></i>
                                </div>
                                <span class="font-medium">{{ $member->phone }}</span>
                            </div>
                            @endif
                            @if($member->family)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-100 to-pink-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-home text-purple-500"></i>
                                </div>
                                <span class="font-medium truncate">{{ $member->family->family_name }}</span>
                            </div>
                            @endif
                            @if($member->membership_date)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-r from-orange-100 to-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-orange-500"></i>
                                </div>
                                <span class="font-medium">Joined {{ $member->membership_date->format('M Y') }}</span>
                            </div>
                            @endif
                            @if($member->ministries && $member->ministries->count() > 0)
                            <div class="flex items-center text-gray-600">
                                <div class="w-8 h-8 bg-gradient-to-r from-yellow-100 to-amber-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-hands-helping text-yellow-500"></i>
                                </div>
                                <span class="font-medium text-sm">{{ $member->ministries->count() }} {{ Str::plural('Ministry', $member->ministries->count()) }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200/50">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-2xl text-xs font-bold {{ ($member->membership_status ?? 'active') === 'active' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800' : 'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800' }} shadow-sm">
                                    <i class="fas fa-circle mr-1 text-xs {{ ($member->membership_status ?? 'active') === 'active' ? 'text-green-500' : 'text-gray-500' }}"></i>
                                    {{ ucfirst($member->membership_status ?? 'Active') }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 text-xs font-bold rounded-2xl shadow-sm">
                                    {{ ucfirst($member->membership_type ?? 'Member') }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-cyan-100 to-blue-100 text-cyan-800 text-xs font-bold rounded-2xl shadow-sm border border-cyan-200">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $member->chapter ?? 'ACCRA' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-16 bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <i class="fas fa-users text-4xl text-blue-500"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">No members found</h3>
                            <p class="text-gray-600 text-lg mb-8">Get started by adding your first church member.</p>
                            <a href="{{ route('members.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                                <i class="fas fa-user-plus mr-3"></i>
                                Add First Member
                                <div class="ml-2 w-2 h-2 bg-white/30 rounded-full animate-pulse"></div>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- List View (Hidden by default) -->
            <div id="list-view" class="hidden space-y-4">
                @forelse($members ?? [] as $member)
                    <div class="member-row bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6 hover:shadow-xl transition-all duration-300 hover:bg-white/90">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-6 flex-1">
                                <!-- Photo and Basic Info -->
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-500 rounded-xl flex items-center justify-center shadow-md overflow-hidden">
                                            @if($member->photo_path ?? false)
                                                <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-user text-white"></i>
                                            @endif
                                        </div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 {{ ($member->membership_status ?? 'active') === 'active' ? 'bg-emerald-500' : 'bg-gray-500' }} rounded-full border-2 border-white"></div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ $member->full_name ?? 'John Doe' }}</h3>
                                        <p class="text-sm text-gray-500">{{ $member->member_id ?? 'M2025001' }}</p>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="hidden md:flex items-center space-x-6 flex-1">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-envelope text-blue-500 w-4"></i>
                                        <span class="text-sm text-gray-600 truncate max-w-48">{{ $member->email ?? 'No email' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-phone text-green-500 w-4"></i>
                                        <span class="text-sm text-gray-600">{{ $member->phone ?? 'No phone' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-home text-purple-500 w-4"></i>
                                        <span class="text-sm text-gray-600 truncate max-w-32">{{ $member->family->family_name ?? 'No family' }}</span>
                                    </div>
                                </div>

                                <!-- Status and Type -->
                                <div class="flex items-center space-x-2 flex-wrap gap-2">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ ($member->membership_status ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($member->membership_status ?? 'Active') }}
                                    </span>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ ($member->membership_type ?? 'member') === 'member' ? 'bg-blue-100 text-blue-800' : (($member->membership_type ?? 'member') === 'visitor' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ ucfirst($member->membership_type ?? 'Member') }}
                                    </span>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-cyan-100 to-blue-100 text-cyan-800 border border-cyan-200">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $member->chapter ?? 'ACCRA' }}
                                    </span>
                                    @if($member->date_of_birth)
                                        <span class="text-xs text-gray-500">Age: {{ \Carbon\Carbon::parse($member->date_of_birth)->age }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('members.show', $member->id ?? 1) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member->id ?? 1) }}" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit Member">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <i class="fas fa-users text-4xl text-blue-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">No members found</h3>
                        <p class="text-gray-600 text-lg mb-8">Get started by adding your first church member.</p>
                        <a href="{{ route('members.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105">
                            <i class="fas fa-user-plus mr-3"></i>
                            Add First Member
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if(isset($members) && $members->hasPages())
            <div class="flex justify-center mt-12">
                <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-4">
                    {{ $members->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle Functionality
    window.toggleView = function(viewType) {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const gridBtn = document.getElementById('grid-btn');
        const listBtn = document.getElementById('list-btn');
        
        if (viewType === 'grid') {
            gridView.classList.remove('hidden');
            listView.classList.add('hidden');
            
            // Update button styles
            gridBtn.className = 'flex items-center px-4 py-2 rounded-xl transition-all duration-200 bg-blue-600 text-white shadow-md';
            listBtn.className = 'flex items-center px-4 py-2 rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-100';
            
            // Store preference
            localStorage.setItem('membersViewType', 'grid');
        } else {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            
            // Update button styles
            listBtn.className = 'flex items-center px-4 py-2 rounded-xl transition-all duration-200 bg-blue-600 text-white shadow-md';
            gridBtn.className = 'flex items-center px-4 py-2 rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-100';
            
            // Store preference
            localStorage.setItem('membersViewType', 'list');
        }
    };
    
    // Load saved view preference
    const savedView = localStorage.getItem('membersViewType') || 'grid';
    toggleView(savedView);
    
    // Export Dropdown Toggle
    window.toggleExportDropdown = function() {
        const menu = document.getElementById('export-menu');
        menu.classList.toggle('hidden');
    };
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('export-dropdown');
        const menu = document.getElementById('export-menu');
        
        if (!dropdown.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
    
    // Export Members Functionality with format selection
    window.exportMembers = function(format = 'csv') {
        // Close the dropdown
        document.getElementById('export-menu').classList.add('hidden');
        
        // Get current filter parameters
        const urlParams = new URLSearchParams(window.location.search);
        const exportUrl = new URL('{{ route("members.export") }}', window.location.origin);
        
        // Add format parameter
        exportUrl.searchParams.set('format', format);
        
        // Add current filters to export URL
        for (const [key, value] of urlParams) {
            exportUrl.searchParams.set(key, value);
        }
        
        // Show loading indicator
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
        button.disabled = true;
        
        // Create a temporary link and trigger download
        const link = document.createElement('a');
        link.href = exportUrl.toString();
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reset button after a delay
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 2000);
    };
    
    // Real-time search functionality
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit form after 500ms of no typing
                this.form.submit();
            }, 500);
        });
    }
    
    // Enhanced filter interactions
    const filterSelects = document.querySelectorAll('select[name="status"], select[name="type"], select[name="gender"], select[name="age_range"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Auto-submit form when filter changes
            this.form.submit();
        });
    });
    
    // Smooth animations for member cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe member cards for animation
    document.querySelectorAll('.member-card, .member-row').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Quick actions for member cards
    document.querySelectorAll('.member-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput?.focus();
        }
        
        // G for grid view, L for list view
        if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
            if (e.key === 'g' || e.key === 'G') {
                toggleView('grid');
            } else if (e.key === 'l' || e.key === 'L') {
                toggleView('list');
            }
        }
    });
    
    // Add keyboard shortcut hints
    const shortcutHints = document.createElement('div');
    shortcutHints.className = 'fixed bottom-4 right-4 bg-black/80 text-white text-xs px-3 py-2 rounded-lg opacity-0 transition-opacity duration-300';
    shortcutHints.innerHTML = `
        <div class="space-y-1">
            <div><kbd class="bg-gray-700 px-1 rounded">Ctrl+K</kbd> Search</div>
            <div><kbd class="bg-gray-700 px-1 rounded">G</kbd> Grid view</div>
            <div><kbd class="bg-gray-700 px-1 rounded">L</kbd> List view</div>
        </div>
    `;
    document.body.appendChild(shortcutHints);
    
    // Show shortcuts on hover over view toggle
    const viewToggle = document.querySelector('.flex.items-center.bg-white\\/80');
    if (viewToggle) {
        viewToggle.addEventListener('mouseenter', () => {
            shortcutHints.style.opacity = '1';
        });
        
        viewToggle.addEventListener('mouseleave', () => {
            shortcutHints.style.opacity = '0';
        });
    }
});
</script>
@endsection
