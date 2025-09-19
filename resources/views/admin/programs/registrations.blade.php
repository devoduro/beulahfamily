@extends('components.app-layout')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Program Registrations</h1>
            <p class="text-gray-600">{{ $program->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.programs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Programs
            </a>
            <a href="{{ route('admin.programs.registrations.export', $program) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Program Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $registrations->count() }}</div>
                    <div class="text-sm text-gray-500">Total Registrations</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $registrations->where('status', 'approved')->count() }}</div>
                    <div class="text-sm text-gray-500">Approved</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $registrations->where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-gray-500">Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $registrations->where('status', 'rejected')->count() }}</div>
                    <div class="text-sm text-gray-500">Rejected</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                    <select id="business_type" name="business_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        @foreach(\App\Models\ProgramRegistration::getBusinessTypeOptions() as $value => $label)
                            <option value="{{ $value }}" {{ request('business_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Business name, contact name..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if($registrations->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <form method="POST" action="{{ route('admin.programs.registrations.bulk-update', $program) }}" id="bulkForm">
                @csrf
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="selectAll" class="ml-2 text-sm text-gray-700">Select All</label>
                    </div>
                    <select name="bulk_status" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Bulk Action</option>
                        <option value="approved">Approve Selected</option>
                        <option value="rejected">Reject Selected</option>
                        <option value="pending">Mark as Pending</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Apply
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Registrations Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Registrations ({{ $registrations->count() }})</h3>
        </div>
        <div class="p-6">
            @if($registrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" id="headerCheckbox">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Files</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registrations as $registration)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="registration_ids[]" value="{{ $registration->id }}" 
                                               class="registration-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" form="bulkForm">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->business_name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($registration->services_offered, 50) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $registration->contact_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $registration->business_phone }}</div>
                                            <div class="text-sm text-gray-500">{{ $registration->business_email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $registration->getBusinessTypeLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'cancelled' => 'bg-gray-100 text-gray-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$registration->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($registration->uploaded_files && count($registration->uploaded_files) > 0)
                                            <span class="text-sm text-blue-600">
                                                <i class="fas fa-paperclip mr-1"></i>
                                                {{ count($registration->uploaded_files) }} files
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">No files</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $registration->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.programs.registrations.show', [$program, $registration]) }}" 
                                               class="text-blue-600 hover:text-blue-900" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($registration->status == 'pending')
                                                <form method="POST" action="{{ route('admin.programs.registrations.update-status', [$program, $registration]) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.programs.registrations.update-status', [$program, $registration]) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No registrations found</h3>
                    <p class="text-gray-500">No one has registered for this program yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const headerCheckbox = document.getElementById('headerCheckbox');
    const registrationCheckboxes = document.querySelectorAll('.registration-checkbox');

    // Handle select all functionality
    [selectAllCheckbox, headerCheckbox].forEach(checkbox => {
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                registrationCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                // Sync both select all checkboxes
                if (selectAllCheckbox && headerCheckbox) {
                    selectAllCheckbox.checked = this.checked;
                    headerCheckbox.checked = this.checked;
                }
            });
        }
    });

    // Handle individual checkbox changes
    registrationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.registration-checkbox:checked').length;
            const totalCount = registrationCheckboxes.length;
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === totalCount;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            }
            if (headerCheckbox) {
                headerCheckbox.checked = checkedCount === totalCount;
                headerCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            }
        });
    });
});
</script>
@endsection
