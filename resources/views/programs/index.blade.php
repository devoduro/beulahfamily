@extends('components.app-layout')

@section('title', 'Programs & Conferences')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 text-primary mb-3">Programs & Conferences</h1>
        <p class="lead text-muted">Join our upcoming programs and conferences</p>
    </div>

    @if($programs->count() > 0)
        <div class="row">
            @foreach($programs as $program)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1">{{ $program->name }}</h5>
                                    <span class="badge bg-light text-dark">{{ ucfirst($program->type) }}</span>
                                </div>
                                @if($program->registration_fee > 0)
                                    <div class="text-end">
                                        <strong>₵{{ number_format($program->registration_fee, 2) }}</strong>
                                    </div>
                                @else
                                    <span class="badge bg-success">FREE</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-body">
                            @if($program->description)
                                <p class="card-text text-muted">{{ Str::limit($program->description, 120) }}</p>
                            @endif
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <span>{{ $program->date_range }}</span>
                                </div>
                                
                                @if($program->time_range)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <span>{{ $program->time_range }}</span>
                                    </div>
                                @endif
                                
                                @if($program->venue)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <span>{{ $program->venue }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Registration Stats -->
                            @php $stats = $program->getRegistrationStats(); @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Registrations:</span>
                                    <span class="fw-bold">
                                        {{ $stats['approved'] }}
                                        @if($program->max_participants)
                                            / {{ $program->max_participants }}
                                        @endif
                                    </span>
                                </div>
                                
                                @if($program->max_participants)
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ ($stats['approved'] / $program->max_participants) * 100 }}%"></div>
                                    </div>
                                    @if($stats['remaining_slots'] !== null && $stats['remaining_slots'] <= 10)
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Only {{ $stats['remaining_slots'] }} spots remaining!
                                        </small>
                                    @endif
                                @endif
                            </div>

                            <!-- Registration Deadline -->
                            @if($program->registration_deadline)
                                <div class="alert alert-info py-2">
                                    <small>
                                        <i class="fas fa-clock me-1"></i>
                                        Registration closes: {{ $program->registration_deadline->format('M j, Y') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-2">
                                <a href="{{ route('programs.show', $program) }}" class="btn btn-outline-primary flex-fill">
                                    <i class="fas fa-info-circle me-1"></i>Details
                                </a>
                                
                                @if($program->isRegistrationOpen())
                                    <a href="{{ route('programs.register', $program) }}" class="btn btn-primary flex-fill">
                                        <i class="fas fa-user-plus me-1"></i>Register
                                    </a>
                                @else
                                    <button class="btn btn-secondary flex-fill" disabled>
                                        <i class="fas fa-lock me-1"></i>Closed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-calendar-alt fa-4x text-gray-300 mb-4"></i>
            <h3 class="text-gray-600">No Programs Available</h3>
            <p class="text-muted">Check back later for upcoming programs and conferences.</p>
        </div>
    @endif
</div>
@endsection
