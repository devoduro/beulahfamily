@extends('components.app-layout')

@section('title', 'Birthday Celebrants')
@section('subtitle', 'Celebrate and manage member birthdays')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Quick Action Buttons -->
        <div class="flex flex-wrap gap-4 justify-end">
            <a href="{{ route('birthdays.today') }}" class="group bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                <i class="fas fa-calendar-day group-hover:rotate-12 transition-transform duration-300"></i>
                <span class="font-semibold">Today's Birthdays</span>
                <span class="bg-white bg-opacity-30 px-2 py-1 rounded-lg text-sm">{{ $todayBirthdays->count() }}</span>
            </a>
            <a href="{{ route('birthdays.upcoming') }}" class="group bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-2">
                <i class="fas fa-calendar-week group-hover:rotate-12 transition-transform duration-300"></i>
                <span class="font-semibold">Upcoming</span>
                <span class="bg-white bg-opacity-30 px-2 py-1 rounded-lg text-sm">{{ $upcomingBirthdays->count() }}</span>
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Members Card -->
            <div class="group bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-1">{{ $stats['total_members'] }}</div>
                    <div class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Members</div>
                </div>
            </div>

            <!-- Today's Birthdays Card -->
            <div class="group bg-gradient-to-br from-green-500 via-green-600 to-emerald-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                            <i class="fas fa-birthday-cake text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-1">{{ $stats['today_birthdays'] }}</div>
                    <div class="text-green-100 text-sm font-medium uppercase tracking-wider">Today's Birthdays</div>
                </div>
            </div>

            <!-- This Week Card -->
            <div class="group bg-gradient-to-br from-purple-500 via-purple-600 to-violet-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                            <i class="fas fa-calendar-week text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-1">{{ $stats['this_week_birthdays'] }}</div>
                    <div class="text-purple-100 text-sm font-medium uppercase tracking-wider">This Week</div>
                </div>
            </div>

            <!-- Average Age Card -->
            <div class="group bg-gradient-to-br from-pink-500 via-pink-600 to-rose-700 rounded-3xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-all duration-300">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-1">{{ $stats['average_age'] }}</div>
                    <div class="text-pink-100 text-sm font-medium uppercase tracking-wider">Average Age</div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-3xl shadow-xl p-6">
            <div class="flex items-center mb-6">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mr-4">
                    <i class="fas fa-filter text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Filter Birthdays</h2>
            </div>
            
            <form method="GET" action="{{ route('birthdays.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Month Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                        <select name="month" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">All Months</option>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chapter Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Chapter</label>
                        <select name="chapter" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">All Chapters</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter }}" {{ request('chapter') == $chapter ? 'selected' : '' }}>{{ $chapter }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Search Name</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('birthdays.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Members List -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-birthday-cake mr-3"></i>
                    Birthday Celebrants ({{ $members->total() }})
                </h2>
            </div>

            @if($members->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Birthday</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Age</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Next Birthday</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Chapter</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($members as $member)
                        <tr class="hover:bg-blue-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold mr-3">
                                        {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $member->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                    <span class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('F j') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                    {{ $member->age }} years
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($member->days_until_birthday == 0)
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800 flex items-center w-fit">
                                        <i class="fas fa-birthday-cake mr-1"></i> Today!
                                    </span>
                                @else
                                    <span class="text-gray-600">{{ $member->days_until_birthday }} days</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-900">{{ $member->chapter ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('birthdays.show', $member) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-eye mr-2"></i>View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $members->links() }}
            </div>
            @else
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-birthday-cake text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Birthdays Found</h3>
                <p class="text-gray-500">Try adjusting your filters or search criteria.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
