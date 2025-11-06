@extends('components.app-layout')

@section('title', 'SMS Campaign Details')
@section('subtitle', 'View campaign information and delivery status')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200/60 p-8 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-sms text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900">{{ $smsMessage->title }}</h1>
                    <p class="text-slate-600">Campaign ID: #{{ $smsMessage->id }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('sms.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
                @if($smsMessage->canBeCancelled())
                <button onclick="cancelCampaign({{ $smsMessage->id }})" class="px-4 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel Campaign
                </button>
                @endif
            </div>
        </div>

        <!-- Status Badge -->
        <div class="flex items-center space-x-4">
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold
                @if($smsMessage->status === 'sent') bg-green-100 text-green-800
                @elseif($smsMessage->status === 'sending') bg-blue-100 text-blue-800 animate-pulse
                @elseif($smsMessage->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($smsMessage->status === 'scheduled') bg-purple-100 text-purple-800
                @elseif($smsMessage->status === 'failed') bg-red-100 text-red-800
                @else bg-slate-100 text-slate-800
                @endif">
                <i class="fas fa-circle text-xs mr-2"></i>
                {{ ucfirst($smsMessage->status) }}
            </span>
            
            @if($smsMessage->is_scheduled)
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-purple-100 text-purple-800">
                <i class="fas fa-clock mr-2"></i>
                Scheduled for {{ $smsMessage->scheduled_at->format('M d, Y h:i A') }}
            </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Statistics Cards -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
                <span class="text-3xl font-black text-blue-900">{{ $smsMessage->total_recipients }}</span>
            </div>
            <p class="text-blue-700 font-semibold">Total Recipients</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <span class="text-3xl font-black text-green-900">{{ $smsMessage->successful_sends ?? 0 }}</span>
            </div>
            <p class="text-green-700 font-semibold">Successful</p>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white"></i>
                </div>
                <span class="text-3xl font-black text-purple-900">₵{{ number_format($smsMessage->actual_cost ?? $smsMessage->estimated_cost, 2) }}</span>
            </div>
            <p class="text-purple-700 font-semibold">Cost</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Message Details -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200/60 p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                Campaign Details
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-slate-600">Message Content</label>
                    <div class="mt-2 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <p class="text-slate-900 whitespace-pre-wrap">{{ $smsMessage->message }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Recipient Type</label>
                        <p class="text-slate-900 font-medium mt-1 capitalize">{{ str_replace('_', ' ', $smsMessage->recipient_type) }}</p>
                    </div>

                    @if($smsMessage->sender_name)
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Sender Name</label>
                        <p class="text-slate-900 font-medium mt-1">{{ $smsMessage->sender_name }}</p>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Created By</label>
                        <p class="text-slate-900 font-medium mt-1">{{ $smsMessage->sender->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-600">Created At</label>
                        <p class="text-slate-900 font-medium mt-1">{{ $smsMessage->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                @if($smsMessage->sent_at)
                <div>
                    <label class="text-sm font-semibold text-slate-600">Sent At</label>
                    <p class="text-slate-900 font-medium mt-1">{{ $smsMessage->sent_at->format('M d, Y h:i A') }}</p>
                </div>
                @endif

                @if($smsMessage->template)
                <div>
                    <label class="text-sm font-semibold text-slate-600">Template Used</label>
                    <p class="text-slate-900 font-medium mt-1">{{ $smsMessage->template->name }}</p>
                </div>
                @endif

                @if($smsMessage->mnotify_message_id)
                <div>
                    <label class="text-sm font-semibold text-slate-600">MNotify Message ID</label>
                    <p class="text-slate-900 font-medium mt-1 font-mono text-sm">{{ $smsMessage->mnotify_message_id }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Delivery Statistics -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200/60 p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                <i class="fas fa-chart-pie text-green-600 mr-3"></i>
                Delivery Statistics
            </h2>

            <div class="space-y-6">
                <!-- Progress Bar -->
                <div>
                    <div class="flex justify-between text-sm font-semibold text-slate-600 mb-2">
                        <span>Delivery Progress</span>
                        <span>{{ $smsMessage->total_recipients > 0 ? round(($smsMessage->successful_sends / $smsMessage->total_recipients) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $smsMessage->total_recipients > 0 ? ($smsMessage->successful_sends / $smsMessage->total_recipients) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <!-- Status Breakdown -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                        <div class="flex items-center justify-between">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            <span class="text-2xl font-black text-green-900">{{ $smsMessage->successful_sends ?? 0 }}</span>
                        </div>
                        <p class="text-green-700 font-semibold mt-2">Delivered</p>
                    </div>

                    <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                        <div class="flex items-center justify-between">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                            <span class="text-2xl font-black text-red-900">{{ $smsMessage->failed_sends ?? 0 }}</span>
                        </div>
                        <p class="text-red-700 font-semibold mt-2">Failed</p>
                    </div>
                </div>

                <!-- Cost Breakdown -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <h3 class="font-semibold text-slate-900 mb-3">Cost Breakdown</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Estimated Cost:</span>
                            <span class="font-semibold text-slate-900">₵{{ number_format($smsMessage->estimated_cost, 2) }}</span>
                        </div>
                        @if($smsMessage->actual_cost)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Actual Cost:</span>
                            <span class="font-semibold text-slate-900">₵{{ number_format($smsMessage->actual_cost, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-sm pt-2 border-t border-slate-300">
                            <span class="text-slate-600">Cost per SMS:</span>
                            <span class="font-semibold text-slate-900">₵{{ $smsMessage->total_recipients > 0 ? number_format(($smsMessage->actual_cost ?? $smsMessage->estimated_cost) / $smsMessage->total_recipients, 4) : '0.00' }}</span>
                        </div>
                    </div>
                </div>

                @if($smsMessage->status === 'failed' && $smsMessage->error_message)
                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                    <h3 class="font-semibold text-red-900 mb-2 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Error Message
                    </h3>
                    <p class="text-red-700 text-sm">{{ $smsMessage->error_message }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recipients List -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200/60 p-8 mt-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-900 flex items-center">
                <i class="fas fa-users text-purple-600 mr-3"></i>
                Recipients ({{ $smsMessage->recipients->count() }})
            </h2>
            
            @if($smsMessage->mnotify_message_id && $smsMessage->status === 'sent')
            <button onclick="refreshDeliveryReport()" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Refresh Status
            </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Recipient</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Sent At</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($smsMessage->recipients as $index => $recipient)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-4 text-sm text-slate-600">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                    {{ substr($recipient->recipient_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $recipient->recipient_name }}</p>
                                    @if($recipient->member)
                                    <p class="text-xs text-slate-500">{{ $recipient->member->chapter ?? 'N/A' }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-900 font-mono">{{ $recipient->phone_number }}</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($recipient->status === 'sent' || $recipient->status === 'delivered') bg-green-100 text-green-800
                                @elseif($recipient->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($recipient->status === 'failed') bg-red-100 text-red-800
                                @else bg-slate-100 text-slate-800
                                @endif">
                                {{ ucfirst($recipient->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-600">
                            {{ $recipient->sent_at ? $recipient->sent_at->format('M d, h:i A') : '-' }}
                        </td>
                        <td class="px-4 py-4 text-sm font-semibold text-slate-900">
                            {{ $recipient->cost ? '₵' . number_format($recipient->cost, 4) : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No recipients found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function cancelCampaign(id) {
    if (!confirm('Are you sure you want to cancel this SMS campaign?')) {
        return;
    }

    fetch(`/sms/${id}/cancel`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showNotification('Campaign cancelled successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            window.showNotification(data.message || 'Failed to cancel campaign', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showNotification('An error occurred', 'error');
    });
}

function refreshDeliveryReport() {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Refreshing...';

    fetch(`/sms/{{ $smsMessage->id }}/delivery-report`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.showNotification('Delivery report updated', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            window.showNotification('Failed to refresh report', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>
@endsection
