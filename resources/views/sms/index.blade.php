@extends('components.app-layout')

@section('title', 'SMS Messages')
@section('subtitle', 'Manage your bulk SMS communications')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">SMS Messages</h1>
                <p class="text-gray-600 mt-2">Track and manage your SMS communications</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="{{ route('sms.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>
                    Send New SMS
                </a>
                <button id="refresh-btn" class="inline-flex items-center px-4 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Sent</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-sent">{{ $smsMessages->where('status', 'sent')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-pending">{{ $smsMessages->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Failed</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-failed">{{ $smsMessages->where('status', 'failed')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Scheduled</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-scheduled">{{ $smsMessages->where('is_scheduled', true)->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Search messages...">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="sending" {{ request('status') === 'sending' ? 'selected' : '' }}>Sending</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Messages List -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">SMS Messages</h3>
            </div>

            @if($smsMessages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($smsMessages as $message)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg mr-3">
                                                <i class="fas fa-sms text-white text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $message->title }}</p>
                                                <p class="text-sm text-gray-500 truncate">{{ Str::limit($message->message, 50) }}</p>
                                                @if($message->template)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                        <i class="fas fa-template mr-1"></i>{{ $message->template->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">{{ $message->total_recipients }}</span> recipients
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ ucfirst(str_replace('_', ' ', $message->recipient_type)) }}
                                        </div>
                                        @if($message->status === 'sent')
                                            <div class="text-xs text-green-600">
                                                ✓ {{ $message->successful_sends }} sent
                                                @if($message->failed_sends > 0)
                                                    • ✗ {{ $message->failed_sends }} failed
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'sending' => 'bg-blue-100 text-blue-800',
                                                'sent' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'cancelled' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $statusIcons = [
                                                'pending' => 'fas fa-clock',
                                                'sending' => 'fas fa-spinner fa-spin',
                                                'sent' => 'fas fa-check-circle',
                                                'failed' => 'fas fa-exclamation-triangle',
                                                'cancelled' => 'fas fa-ban'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$message->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            <i class="{{ $statusIcons[$message->status] ?? 'fas fa-question' }} mr-1"></i>
                                            {{ $message->delivery_status }}
                                        </span>
                                        @if($message->is_scheduled && $message->status === 'pending')
                                            <div class="text-xs text-blue-600 mt-1">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ $message->scheduled_at->format('M d, Y g:i A') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($message->actual_cost)
                                            <span class="font-medium">GHS {{ number_format($message->actual_cost, 2) }}</span>
                                        @else
                                            <span class="text-gray-500">GHS {{ number_format($message->estimated_cost, 2) }}</span>
                                            <div class="text-xs text-gray-400">(estimated)</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $message->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs">{{ $message->created_at->format('g:i A') }}</div>
                                        @if($message->sent_at)
                                            <div class="text-xs text-green-600">
                                                Sent: {{ $message->sent_at->format('M d, g:i A') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('sms.show', $message->id) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($message->canBeCancelled())
                                                <button onclick="cancelSms({{ $message->id }})" 
                                                        class="text-red-600 hover:text-red-900 transition-colors">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @endif
                                            
                                            @if($message->status === 'sent' && $message->mnotify_message_id)
                                                <button onclick="getDeliveryReport({{ $message->id }})" 
                                                        class="text-green-600 hover:text-green-900 transition-colors">
                                                    <i class="fas fa-chart-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $smsMessages->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sms text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No SMS messages found</h3>
                    <p class="text-gray-500 mb-6">Get started by sending your first SMS message to your members.</p>
                    <a href="{{ route('sms.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Send Your First SMS
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function cancelSms(id) {
    if (confirm('Are you sure you want to cancel this SMS?')) {
        fetch(`/sms/${id}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the SMS.');
        });
    }
}

function getDeliveryReport(id) {
    fetch(`/sms/${id}/delivery-report`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // You can implement a modal or redirect to show detailed delivery report
                alert('Delivery report retrieved successfully. Check the SMS details page for more information.');
            } else {
                alert('Error retrieving delivery report.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while retrieving the delivery report.');
        });
}

document.getElementById('refresh-btn').addEventListener('click', function() {
    location.reload();
});
</script>
@endsection
