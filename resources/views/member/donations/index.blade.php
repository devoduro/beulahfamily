@extends('member.layouts.app')

@section('title', 'My Donations')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Donations</h1>
            <p class="text-gray-600">Track your giving history and make new donations</p>
        </div>
        <a href="{{ route('member.donations.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Make Donation
        </a>
    </div>

    <!-- Donation Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-hand-holding-heart text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Donations</p>
                    <p class="text-2xl font-bold text-gray-900">₵0.00</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">This Year</p>
                    <p class="text-2xl font-bold text-gray-900">₵0.00</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Count</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Donation History</h2>
                <div class="flex space-x-2">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option>All Types</option>
                        <option>Tithe</option>
                        <option>Offering</option>
                        <option>Special</option>
                    </select>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option>This Year</option>
                        <option>Last Year</option>
                        <option>All Time</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hand-holding-heart text-green-600 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No donations yet</h3>
                <p class="text-gray-500 mb-6">Start your giving journey by making your first donation.</p>
                <a href="{{ route('member.donations.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-heart mr-2"></i>Make Your First Donation
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
