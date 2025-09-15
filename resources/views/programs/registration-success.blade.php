@extends('components.app-layout')

@section('title', 'Registration Successful')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-4">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="h2 text-success mb-3">Registration Successful!</h1>
                <p class="lead text-muted">Thank you for registering for {{ $program->name }}</p>
            </div>

            <!-- Registration Details -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Registration Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Program Information</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Program:</strong></td>
                                    <td>{{ $program->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $program->date_range }}</td>
                                </tr>
                                @if($program->time_range)
                                <tr>
                                    <td><strong>Time:</strong></td>
                                    <td>{{ $program->time_range }}</td>
                                </tr>
                                @endif
                                @if($program->venue)
                                <tr>
                                    <td><strong>Venue:</strong></td>
                                    <td>{{ $program->venue }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Registration ID:</strong></td>
                                    <td><span class="badge bg-primary">#{{ $registration->id }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Business Information</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Business:</strong></td>
                                    <td>{{ $registration->business_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>{{ $registration->formatted_business_type }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contact:</strong></td>
                                    <td>{{ $registration->contact_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $registration->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $registration->business_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $registration->status_color }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($registration->hasUploadedFiles())
                    <div class="mt-3">
                        <h6 class="text-primary">Uploaded Files</h6>
                        <p class="text-success">
                            <i class="fas fa-check me-1"></i>
                            {{ $registration->getFileUploadSummary() }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list-check me-2"></i>What's Next?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Registration Status</h6>
                            <p class="text-muted">
                                Your registration is currently <strong>pending review</strong>. 
                                Our team will review your application and notify you of the status.
                            </p>
                            
                            @if($program->registration_fee > 0)
                            <h6 class="text-primary">Payment Information</h6>
                            <p class="text-muted">
                                Registration Fee: <strong>₵{{ number_format($program->registration_fee, 2) }}</strong><br>
                                Payment instructions will be sent to you once your registration is approved.
                            </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Contact Information</h6>
                            @if($program->contact_email || $program->contact_phone)
                                <p class="text-muted">
                                    If you have any questions, please contact us:
                                </p>
                                @if($program->contact_email)
                                    <p class="mb-1">
                                        <i class="fas fa-envelope me-2"></i>
                                        <a href="mailto:{{ $program->contact_email }}">{{ $program->contact_email }}</a>
                                    </p>
                                @endif
                                @if($program->contact_phone)
                                    <p class="mb-1">
                                        <i class="fas fa-phone me-2"></i>
                                        <a href="tel:{{ $program->contact_phone }}">{{ $program->contact_phone }}</a>
                                    </p>
                                @endif
                            @else
                                <p class="text-muted">
                                    If you have any questions, please contact the church administration.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mb-4">
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('programs.registration.show', [$program, $registration]) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>View Registration Details
                    </a>
                    
                    <a href="{{ route('programs.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-calendar me-2"></i>View Other Programs
                    </a>
                    
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>Print Confirmation
                    </button>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="alert alert-warning">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i>Important Notes
                </h6>
                <ul class="mb-0">
                    <li>Please save your Registration ID: <strong>#{{ $registration->id }}</strong></li>
                    <li>You will receive email notifications about your registration status</li>
                    <li>Keep your contact information updated for important announcements</li>
                    @if($program->registration_deadline)
                        <li>Registration deadline: {{ $program->registration_deadline->format('M j, Y') }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .alert-warning {
        display: none !important;
    }
}
</style>
@endsection
