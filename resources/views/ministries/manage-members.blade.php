@extends('components.app-layout')

@section('title', 'Manage Members - ' . $ministry->name)
@section('subtitle', 'Add, remove, and manage ministry members')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('ministries.show', $ministry) }}" class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Ministry
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">Manage Members</h1>
                        <p class="text-gray-600 mt-1">{{ $ministry->name }} Ministry</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $ministry->members->count() }} members
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Add New Member -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Add New Member</h2>
                
                <form id="addMemberForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">Select Member</label>
                        <select name="member_id" id="member_id" required class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Choose a member...</option>
                            @foreach($availableMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->full_name }} ({{ $member->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role" id="role" required class="block w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="member">Member</option>
                            <option value="coordinator">Coordinator</option>
                            <option value="assistant_leader">Assistant Leader</option>
                            <option value="leader">Leader</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add Member
                    </button>
                </form>
            </div>

            <!-- Current Members -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Current Members</h2>
                
                @if($ministry->members->count() > 0)
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach($ministry->members as $member)
                            <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-xl" id="member-{{ $member->id }}">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                        @if($member->photo)
                                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->full_name }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <i class="fas fa-user text-white"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $member->full_name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $member->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <select class="role-select text-sm border border-gray-300 rounded-lg px-2 py-1" data-member-id="{{ $member->id }}" data-original-role="{{ $member->pivot->role }}">
                                        <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>Member</option>
                                        <option value="coordinator" {{ $member->pivot->role === 'coordinator' ? 'selected' : '' }}>Coordinator</option>
                                        <option value="assistant_leader" {{ $member->pivot->role === 'assistant_leader' ? 'selected' : '' }}>Assistant Leader</option>
                                        <option value="leader" {{ $member->pivot->role === 'leader' ? 'selected' : '' }}>Leader</option>
                                    </select>
                                    <button class="remove-member text-red-600 hover:text-red-800 p-1" data-member-id="{{ $member->id }}" title="Remove member">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No members yet</h3>
                        <p class="text-gray-500">Add members to this ministry using the form on the left.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addMemberForm = document.getElementById('addMemberForm');
    const messageContainer = document.getElementById('message-container');

    // Add member form submission
    addMemberForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
        submitButton.disabled = true;

        fetch('{{ route("ministries.members.add", $ministry) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Member added successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showMessage(data.message || 'Failed to add member', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred while adding the member', 'error');
        })
        .finally(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });

    // Role change handling
    document.querySelectorAll('.role-select').forEach(select => {
        select.addEventListener('change', function() {
            const memberId = this.dataset.memberId;
            const newRole = this.value;
            const originalRole = this.dataset.originalRole;
            
            if (newRole === originalRole) return;

            fetch(`{{ route("ministries.members.update", ["ministry" => $ministry, "member" => ":memberId"]) }}`.replace(':memberId', memberId), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    role: newRole,
                    is_active: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Member role updated successfully!', 'success');
                    this.dataset.originalRole = newRole;
                } else {
                    showMessage(data.message || 'Failed to update role', 'error');
                    this.value = originalRole; // Revert selection
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred while updating the role', 'error');
                this.value = originalRole; // Revert selection
            });
        });
    });

    // Remove member handling
    document.querySelectorAll('.remove-member').forEach(button => {
        button.addEventListener('click', function() {
            const memberId = this.dataset.memberId;
            const memberElement = document.getElementById(`member-${memberId}`);
            
            if (confirm('Are you sure you want to remove this member from the ministry?')) {
                fetch(`{{ route("ministries.members.remove", ["ministry" => $ministry, "member" => ":memberId"]) }}`.replace(':memberId', memberId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('Member removed successfully!', 'success');
                        memberElement.remove();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showMessage(data.message || 'Failed to remove member', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while removing the member', 'error');
                });
            }
        });
    });

    function showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `px-6 py-4 rounded-lg shadow-lg mb-4 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        messageDiv.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        messageContainer.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
});
</script>
@endsection
