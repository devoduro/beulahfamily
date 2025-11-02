@extends('layouts.app')

@section('title', $member->full_name . ' - Birthday Details')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-birthday-cake text-primary me-2"></i>
                Birthday Details
            </h1>
            <p class="text-muted mb-0">{{ $member->full_name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('birthdays.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Birthdays
            </a>
            <a href="{{ route('members.show', $member) }}" class="btn btn-info">
                <i class="fas fa-user me-1"></i>
                Full Profile
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Birthday Information Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Birthday Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Picture -->
                        <div class="col-md-4 text-center mb-4">
                            @if($member->photo)
                                <img src="{{ asset('storage/' . $member->photo) }}" 
                                     class="rounded-circle border border-3 border-primary shadow-lg mb-3" 
                                     width="150" height="150" 
                                     alt="{{ $member->full_name }}"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center border border-3 border-primary shadow-lg mb-3 mx-auto" 
                                     style="width: 150px; height: 150px;">
                                    <span class="text-white font-weight-bold" style="font-size: 3rem;">
                                        {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            
                            <h4 class="text-gray-800 mb-1">{{ $member->full_name }}</h4>
                            <p class="text-muted mb-3">{{ $member->chapter }} Chapter</p>
                            
                            @if($member->days_until_birthday == 0)
                                <div class="alert alert-success">
                                    <h5 class="mb-0">🎉 Happy Birthday Today! 🎉</h5>
                                </div>
                            @elseif($member->days_until_birthday <= 7)
                                <div class="alert alert-warning">
                                    <h6 class="mb-0">Birthday in {{ $member->days_until_birthday }} day{{ $member->days_until_birthday > 1 ? 's' : '' }}!</h6>
                                </div>
                            @endif
                        </div>

                        <!-- Birthday Details -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="border-start border-4 border-primary ps-3">
                                        <h6 class="text-primary mb-1">Date of Birth</h6>
                                        <p class="h5 mb-0">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('F j, Y') }}</p>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('l') }}</small>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="border-start border-4 border-success ps-3">
                                        <h6 class="text-success mb-1">Current Age</h6>
                                        <p class="h5 mb-0">{{ $member->age }} Years Old</p>
                                        <small class="text-muted">Born {{ \Carbon\Carbon::parse($member->date_of_birth)->diffForHumans() }}</small>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="border-start border-4 border-info ps-3">
                                        <h6 class="text-info mb-1">Next Birthday</h6>
                                        <p class="h5 mb-0">{{ $member->next_birthday->format('F j, Y') }}</p>
                                        <small class="text-muted">{{ $member->next_birthday->format('l') }}</small>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="border-start border-4 border-warning ps-3">
                                        <h6 class="text-warning mb-1">Days Until Birthday</h6>
                                        <p class="h5 mb-0">
                                            @if($member->days_until_birthday == 0)
                                                Today! 🎉
                                            @else
                                                {{ $member->days_until_birthday }} Days
                                            @endif
                                        </p>
                                        <small class="text-muted">{{ $member->next_birthday->diffForHumans() }}</small>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="border-start border-4 border-secondary ps-3">
                                        <h6 class="text-secondary mb-1">Zodiac Sign</h6>
                                        <p class="h5 mb-0">{{ $member->zodiac_sign }}</p>
                                        <small class="text-muted">Astrological sign</small>
                                    </div>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="border-start border-4 border-dark ps-3">
                                        <h6 class="text-dark mb-1">Birth Month</h6>
                                        <p class="h5 mb-0">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('F') }}</p>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($member->date_of_birth)->format('M') }} birthdays</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-address-book me-2"></i>
                        Contact Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($member->email)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <div>
                                    <strong>Email</strong><br>
                                    <a href="mailto:{{ $member->email }}" class="text-decoration-none">{{ $member->email }}</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($member->phone)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-success me-3"></i>
                                <div>
                                    <strong>Phone</strong><br>
                                    <a href="tel:{{ $member->phone }}" class="text-decoration-none">{{ $member->phone }}</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($member->whatsapp_phone)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fab fa-whatsapp text-success me-3"></i>
                                <div>
                                    <strong>WhatsApp</strong><br>
                                    <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $member->whatsapp_phone) }}" 
                                       target="_blank" class="text-decoration-none">{{ $member->whatsapp_phone }}</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($member->address)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-info me-3 mt-1"></i>
                                <div>
                                    <strong>Address</strong><br>
                                    <span class="text-muted">{{ $member->address }}</span>
                                    @if($member->city), {{ $member->city }}@endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" 
                                onclick="sendWishes({{ $member->id }}, '{{ $member->full_name }}')">
                            <i class="fas fa-gift me-2"></i>
                            Send Birthday Wishes
                        </button>
                        
                        @if($member->email)
                        <a href="mailto:{{ $member->email }}?subject=Happy Birthday!" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>
                            Send Email
                        </a>
                        @endif

                        @if($member->phone)
                        <a href="tel:{{ $member->phone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone me-2"></i>
                            Call Member
                        </a>
                        @endif

                        @if($member->whatsapp_phone)
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $member->whatsapp_phone) }}?text=Happy Birthday!" 
                           target="_blank" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i>
                            WhatsApp
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Birthday Countdown -->
            @if($member->days_until_birthday > 0)
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clock me-2"></i>
                        Birthday Countdown
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="countdown-display">
                        <div class="countdown-number">{{ $member->days_until_birthday }}</div>
                        <div class="countdown-label">Day{{ $member->days_until_birthday > 1 ? 's' : '' }} Remaining</div>
                    </div>
                    <div class="progress mt-3">
                        @php
                            $totalDaysInYear = 365;
                            $daysPassed = $totalDaysInYear - $member->days_until_birthday;
                            $progressPercent = ($daysPassed / $totalDaysInYear) * 100;
                        @endphp
                        <div class="progress-bar bg-info" role="progressbar" 
                             style="width: {{ $progressPercent }}%" 
                             aria-valuenow="{{ $progressPercent }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">{{ round($progressPercent, 1) }}% through the year</small>
                </div>
            </div>
            @endif

            <!-- Member Stats -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Member Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <div class="text-muted small">Gender</div>
                                <div class="font-weight-bold">{{ ucfirst($member->gender ?? 'Not specified') }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-muted small">Chapter</div>
                            <div class="font-weight-bold">{{ $member->chapter }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <div class="text-muted small">Status</div>
                                <div class="font-weight-bold">
                                    <span class="badge bg-{{ $member->membership_status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($member->membership_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-muted small">Type</div>
                            <div class="font-weight-bold">{{ ucfirst($member->membership_type) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Birthday History -->
            @if($birthdayHistory->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>
                        Birthday History
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($birthdayHistory as $history)
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-birthday-cake text-success me-2"></i>
                        <div>
                            <small class="text-muted">{{ $history->created_at->format('M j, Y') }}</small><br>
                            <span class="font-weight-bold">{{ $history->description }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Birthday Wishes Modal -->
<div class="modal fade" id="wishesModal" tabindex="-1" aria-labelledby="wishesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="wishesModalLabel">
                    <i class="fas fa-gift me-2"></i>
                    Send Birthday Wishes
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="wishesForm" method="POST" action="{{ route('birthdays.send-wishes', $member) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="wishesMessage" class="form-label">Birthday Message</label>
                        <textarea class="form-control" id="wishesMessage" name="message" rows="5" required>Dear {{ $member->first_name }},

🎉 Happy Birthday! 🎂

On this special day, we celebrate you and all the joy you bring to our church family. May God bless you with another year of health, happiness, and spiritual growth.

May your birthday be filled with love, laughter, and wonderful memories. We're grateful to have you as part of our Beulah Family community.

Wishing you all the best on your {{ $member->age + ($member->days_until_birthday == 0 ? 0 : 1) }}{{ $member->days_until_birthday == 0 ? 'th' : 'th' }} birthday!

With love and prayers,
Your Church Family at Beulah Family</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Send Options</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendEmail" name="send_email" value="1" 
                                   {{ $member->email ? 'checked' : 'disabled' }}>
                            <label class="form-check-label" for="sendEmail">
                                <i class="fas fa-envelope me-1"></i>
                                Send via Email
                                @if(!$member->email)
                                    <small class="text-muted">(No email address)</small>
                                @endif
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendSms" name="send_sms" value="1"
                                   {{ $member->phone ? '' : 'disabled' }}>
                            <label class="form-check-label" for="sendSms">
                                <i class="fas fa-sms me-1"></i>
                                Send via SMS
                                @if(!$member->phone)
                                    <small class="text-muted">(No phone number)</small>
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i>
                        Send Birthday Wishes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.countdown-display {
    padding: 20px;
}

.countdown-number {
    font-size: 3rem;
    font-weight: bold;
    color: #5a5c69;
    line-height: 1;
}

.countdown-label {
    font-size: 0.9rem;
    color: #858796;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 5px;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
}

.border-start {
    border-left: 4px solid;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.3s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.countdown-number {
    animation: pulse 2s infinite;
}
</style>
@endpush

@push('scripts')
<script>
// Send Birthday Wishes Function
function sendWishes(memberId, memberName) {
    const modal = new bootstrap.Modal(document.getElementById('wishesModal'));
    modal.show();
}

// Handle wishes form submission
document.getElementById('wishesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending...';
    submitBtn.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.container-fluid').firstChild);
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('wishesModal')).hide();
        } else {
            throw new Error(data.message || 'Failed to send wishes');
        }
    })
    .catch(error => {
        // Show error message
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show';
        alert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${error.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.container-fluid').firstChild);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Add entrance animation
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
@endpush
