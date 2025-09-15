@extends('components.app-layout')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $program->name }}</h1>
            <p class="text-gray-600">Program Details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.programs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Programs
            </a>
            <a href="{{ route('admin.programs.edit', $program) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit Program
            </a>
            <a href="{{ route('admin.programs.registrations', $program) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-users mr-2"></i>View Registrations
            </a>
        </div>
    </div>

    <!-- Program Status Alert -->
    @if($program->status === 'draft')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Draft Program</h3>
                    <p class="text-sm text-yellow-700 mt-1">This program is in draft mode and not visible to the public. Publish it to allow registrations.</p>
                </div>
            </div>
        </div>
    @elseif($program->status === 'cancelled')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Cancelled Program</h3>
                    <p class="text-sm text-red-700 mt-1">This program has been cancelled.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Registrations</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['approved'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Approved</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Pending</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-dollar-sign text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">₵{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-500">Total Revenue</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Program Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Program Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Name</label>
                            <p class="text-sm text-gray-900">{{ $program->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($program->type) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'published' => 'bg-green-100 text-green-800',
                                    'ongoing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-purple-100 text-purple-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$program->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($program->status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Fee</label>
                            <p class="text-sm text-gray-900">
                                @if($program->registration_fee > 0)
                                    ₵{{ number_format($program->registration_fee, 2) }}
                                @else
                                    <span class="text-green-600 font-medium">Free</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <p class="text-sm text-gray-900">{{ $program->start_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <p class="text-sm text-gray-900">{{ $program->end_date->format('M d, Y') }}</p>
                        </div>

                        @if($program->start_time)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <p class="text-sm text-gray-900">{{ $program->start_time->format('g:i A') }}</p>
                        </div>
                        @endif

                        @if($program->end_time)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <p class="text-sm text-gray-900">{{ $program->end_time->format('g:i A') }}</p>
                        </div>
                        @endif

                        @if($program->venue)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                            <p class="text-sm text-gray-900">
                                <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                {{ $program->venue }}
                            </p>
                        </div>
                        @endif

                        @if($program->max_participants)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Participants</label>
                            <p class="text-sm text-gray-900">{{ number_format($program->max_participants) }}</p>
                        </div>
                        @endif

                        @if($program->registration_deadline)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline</label>
                            <p class="text-sm text-gray-900">{{ $program->registration_deadline->format('M d, Y g:i A') }}</p>
                        </div>
                        @endif

                        @if($program->contact_email)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                            <p class="text-sm text-gray-900">
                                <a href="mailto:{{ $program->contact_email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $program->contact_email }}
                                </a>
                            </p>
                        </div>
                        @endif

                        @if($program->contact_phone)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <p class="text-sm text-gray-900">
                                <a href="tel:{{ $program->contact_phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $program->contact_phone }}
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($program->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $program->description }}</div>
                    </div>
                    @endif

                    @if($program->terms_and_conditions)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Terms and Conditions</label>
                        <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $program->terms_and_conditions }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- File Upload Settings -->
            @if($program->allow_file_uploads)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">File Upload Settings</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max File Size</label>
                            <p class="text-sm text-gray-900">{{ $program->max_file_size ?? 100 }} MB</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Files</label>
                            <p class="text-sm text-gray-900">{{ $program->max_files ?? 5 }} files</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Allowed Types</label>
                            <p class="text-sm text-gray-900">
                                @if($program->allowed_file_types)
                                    {{ implode(', ', $program->allowed_file_types) }}
                                @else
                                    PDF, Images, Audio, Video
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Business Type Distribution -->
            @if(isset($businessTypeDistribution) && count($businessTypeDistribution) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Business Types</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($businessTypeDistribution as $type => $count)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700">{{ $type }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Registrations -->
            @if(isset($recentRegistrations) && $recentRegistrations->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Registrations</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recentRegistrations as $registration)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-building text-blue-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $registration->business_name }}</p>
                                <p class="text-sm text-gray-500">{{ $registration->contact_name }}</p>
                                <p class="text-xs text-gray-400">{{ $registration->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0">
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
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.programs.registrations', $program) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            View all registrations →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if($program->status === 'draft')
                        <form method="POST" action="{{ route('admin.programs.update', $program) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="published">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                                <i class="fas fa-eye mr-2"></i>Publish Program
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('programs.show', $program) }}" target="_blank" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm text-center">
                            <i class="fas fa-external-link-alt mr-2"></i>View Public Page
                        </a>

                        <a href="{{ route('admin.programs.registrations.export', $program) }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors text-sm text-center">
                            <i class="fas fa-download mr-2"></i>Export Registrations
                        </a>

                        <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" onsubmit="return confirm('Are you sure you want to delete this program? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                                <i class="fas fa-trash mr-2"></i>Delete Program
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
