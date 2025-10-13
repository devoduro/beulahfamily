@extends('components.app-layout')

@section('title', 'Pending Approvals')
@section('subtitle', 'Review and approve member registrations')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <div class="space-y-3">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-user-clock text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-orange-900 to-yellow-900 bg-clip-text text-transparent">Pending Member Registrations</h1>
                    <p class="text-gray-600 text-lg mt-1">Review and approve/reject member registration requests</p>
                </div>
            </div>
        </div>
        <a href="{{ route('members.index') }}" class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm text-gray-700 font-semibold rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-300 border border-gray-200/50">
            <i class="fas fa-arrow-left mr-2 text-gray-500"></i>
            Back to Members
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="group bg-gradient-to-br from-yellow-500 via-amber-600 to-orange-600 rounded-3xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105 hover:rotate-1 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 group-hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="fas fa-clock text-3xl group-hover:rotate-12 transition-transform duration-300"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-yellow-100 text-sm font-medium uppercase tracking-wider">Pending Approval</p>
                        <p class="text-4xl font-bold group-hover:scale-110 transition-transform duration-300">{{ $pendingMembers->total() }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-yellow-100">
                    <div class="flex items-center">
                        <i class="fas fa-user-clock mr-2 group-hover:animate-pulse"></i>
                        <span class="text-sm font-medium">Awaiting review</span>
                    </div>
                    <div class="w-3 h-3 bg-yellow-300 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Members List -->
    @if($pendingMembers->count() > 0)
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden hover:shadow-2xl transition-all duration-500">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingMembers as $member)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 font-bold">{{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $member->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: {{ $member->member_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $member->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $member->chapter }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $member->created_at->format('M d, Y') }}
                                    <br>
                                    <span class="text-xs text-gray-400">{{ $member->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="viewMember({{ $member->id }})" 
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button onclick="approveMember({{ $member->id }})" 
                                            class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button onclick="showRejectModal({{ $member->id }}, '{{ $member->full_name }}')" 
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            <!-- Expandable Details Row -->
                            <tr id="member-details-{{ $member->id }}" class="hidden bg-gray-50">
                                <td colspan="5" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="font-semibold text-gray-700 mb-2">Personal Information</h4>
                                            <p><strong>Full Name:</strong> {{ $member->full_name }}</p>
                                            <p><strong>Date of Birth:</strong> {{ $member->date_of_birth?->format('M d, Y') ?? 'N/A' }}</p>
                                            <p><strong>Gender:</strong> {{ ucfirst($member->gender ?? 'N/A') }}</p>
                                            <p><strong>Marital Status:</strong> {{ ucfirst($member->marital_status ?? 'N/A') }}</p>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-700 mb-2">Contact & Location</h4>
                                            <p><strong>Email:</strong> {{ $member->email }}</p>
                                            <p><strong>Phone:</strong> {{ $member->phone }}</p>
                                            @if($member->whatsapp_phone)
                                                <p><strong>WhatsApp:</strong> {{ $member->whatsapp_phone }}</p>
                                            @endif
                                            <p><strong>Address:</strong> {{ $member->address ?? 'N/A' }}</p>
                                            <p><strong>City:</strong> {{ $member->city ?? 'N/A' }}, {{ $member->state ?? 'N/A' }}</p>
                                        </div>
                                        @if($member->occupation)
                                        <div>
                                            <h4 class="font-semibold text-gray-700 mb-2">Occupation</h4>
                                            <p><strong>Occupation:</strong> {{ $member->occupation }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $pendingMembers->links() }}
            </div>
        </div>
    @else
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-16 text-center hover:shadow-2xl transition-all duration-500">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-check-circle text-5xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-green-900 bg-clip-text text-transparent mb-3">All Caught Up!</h3>
                <p class="text-gray-600 text-lg">There are no pending member registrations at this time.</p>
                <div class="mt-8">
                    <a href="{{ route('members.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-2xl hover:from-blue-600 hover:to-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-users mr-2"></i>
                        View All Members
                    </a>
                </div>
            </div>
        </div>
    @endif

<!-- Rejection Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Reject Member Registration
            </h3>
            <p class="text-sm text-gray-500 mb-4">
                Are you sure you want to reject <strong id="memberName"></strong>'s registration?
            </p>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection *</label>
                    <textarea name="rejection_reason" 
                              required 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Provide a reason for rejecting this registration..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-times mr-2"></i>Reject
                    </button>
                    <button type="button" 
                            onclick="closeRejectModal()" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>
</div>

@push('scripts')
<script>
function viewMember(memberId) {
    const detailsRow = document.getElementById(`member-details-${memberId}`);
    detailsRow.classList.toggle('hidden');
}

function approveMember(memberId) {
    if (confirm('Are you sure you want to approve this member registration?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/members/${memberId}/approve`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function showRejectModal(memberId, memberName) {
    document.getElementById('memberName').textContent = memberName;
    document.getElementById('rejectForm').action = `/members/${memberId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}

// Close modal on outside click
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endpush
@endsection
