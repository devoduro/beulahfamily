@extends('layouts.app')

@section('title', "Today's Birthdays")

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-birthday-cake text-success me-2"></i>
                Today's Birthday Celebrants
            </h1>
            <p class="text-muted mb-0">{{ \Carbon\Carbon::today()->format('l, F j, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('birthdays.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to All Birthdays
            </a>
            <a href="{{ route('birthdays.upcoming') }}" class="btn btn-info">
                <i class="fas fa-calendar-week me-1"></i>
                Upcoming Birthdays
            </a>
        </div>
    </div>

    @if($birthdays->count() > 0)
        <!-- Celebration Banner -->
        <div class="alert alert-success border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <div class="text-center text-white">
                <h2 class="mb-3">
                    🎉 Happy Birthday! 🎉
                </h2>
                <p class="h5 mb-0">
                    We have {{ $birthdays->count() }} special {{ $birthdays->count() == 1 ? 'celebration' : 'celebrations' }} today!
                </p>
            </div>
        </div>

        <!-- Birthday Celebrants Grid -->
        <div class="row">
            @foreach($birthdays as $celebrant)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-lg border-0 h-100" style="background: linear-gradient(135deg, #fff 0%, #f8f9fc 100%);">
                    <div class="card-body text-center p-4">
                        <!-- Profile Picture -->
                        <div class="mb-3">
                            @if($celebrant->photo)
                                <img src="{{ asset('storage/' . $celebrant->photo) }}" 
                                     class="rounded-circle border border-3 border-success shadow" 
                                     width="100" height="100" 
                                     alt="{{ $celebrant->full_name }}"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center border border-3 border-success shadow mx-auto" 
                                     style="width: 100px; height: 100px;">
                                    <span class="text-white font-weight-bold h3 mb-0">
                                        {{ substr($celebrant->first_name, 0, 1) }}{{ substr($celebrant->last_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Name and Age -->
                        <h4 class="card-title text-gray-800 mb-2">{{ $celebrant->full_name }}</h4>
                        <div class="mb-3">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-birthday-cake me-1"></i>
                                Turning {{ $celebrant->age }} Today!
                            </span>
                        </div>

                        <!-- Member Details -->
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="text-muted small">Chapter</div>
                                    <div class="font-weight-bold">{{ $celebrant->chapter }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small">Gender</div>
                                <div class="font-weight-bold">{{ ucfirst($celebrant->gender) }}</div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        @if($celebrant->phone)
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-phone me-1"></i>
                                {{ $celebrant->phone }}
                            </small>
                        </div>
                        @endif

                        @if($celebrant->email)
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i>
                                {{ $celebrant->email }}
                            </small>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" 
                                    onclick="sendWishes({{ $celebrant->id }}, '{{ $celebrant->full_name }}')">
                                <i class="fas fa-gift me-2"></i>
                                Send Birthday Wishes
                            </button>
                            <div class="btn-group" role="group">
                                <a href="{{ route('birthdays.show', $celebrant) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    View Details
                                </a>
                                <a href="{{ route('members.show', $celebrant) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-user me-1"></i>
                                    Full Profile
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Birthday Decoration -->
                    <div class="position-absolute top-0 end-0 p-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Birthday Actions -->
        <div class="card shadow mb-4">
            <div class="card-header bg-gradient-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-tasks me-2"></i>
                    Birthday Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-success h-100">
                            <div class="card-body">
                                <h6 class="text-success">
                                    <i class="fas fa-envelope me-2"></i>
                                    Send Group Email
                                </h6>
                                <p class="text-muted small mb-3">Send birthday wishes to all today's celebrants via email</p>
                                <button class="btn btn-success btn-sm" onclick="sendGroupWishes('email')">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    Send Email to All
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-info h-100">
                            <div class="card-body">
                                <h6 class="text-info">
                                    <i class="fas fa-sms me-2"></i>
                                    Send Group SMS
                                </h6>
                                <p class="text-muted small mb-3">Send birthday wishes to all today's celebrants via SMS</p>
                                <button class="btn btn-info btn-sm" onclick="sendGroupWishes('sms')">
                                    <i class="fas fa-mobile-alt me-1"></i>
                                    Send SMS to All
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-warning h-100">
                            <div class="card-body">
                                <h6 class="text-warning">
                                    <i class="fas fa-download me-2"></i>
                                    Export List
                                </h6>
                                <p class="text-muted small mb-3">Download today's birthday celebrants list</p>
                                <button class="btn btn-warning btn-sm" onclick="exportTodaysBirthdays()">
                                    <i class="fas fa-file-excel me-1"></i>
                                    Export to Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- No Birthdays Today -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">No Birthdays Today</h3>
                <p class="text-muted mb-4">
                    There are no birthday celebrations today. Check back tomorrow or view upcoming birthdays.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('birthdays.upcoming') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-week me-1"></i>
                        View Upcoming Birthdays
                    </a>
                    <a href="{{ route('birthdays.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>
                        View All Birthdays
                    </a>
                </div>
            </div>
        </div>
    @endif
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
            <form id="wishesForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="wishesMessage" class="form-label">Birthday Message</label>
                        <textarea class="form-control" id="wishesMessage" name="message" rows="4" required>🎉 Happy Birthday! 🎂

May this special day bring you joy, laughter, and wonderful memories. May God bless you with many more years of health, happiness, and prosperity.

Wishing you all the best on your birthday!

From your church family at Beulah Family</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Send Options</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendEmail" name="send_email" value="1" checked>
                            <label class="form-check-label" for="sendEmail">
                                <i class="fas fa-envelope me-1"></i>
                                Send via Email
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendSms" name="send_sms" value="1">
                            <label class="form-check-label" for="sendSms">
                                <i class="fas fa-sms me-1"></i>
                                Send via SMS
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

@push('scripts')
<script>
// Send Birthday Wishes Function
function sendWishes(memberId, memberName) {
    const modal = new bootstrap.Modal(document.getElementById('wishesModal'));
    const form = document.getElementById('wishesForm');
    const modalTitle = document.getElementById('wishesModalLabel');
    
    modalTitle.innerHTML = `<i class="fas fa-gift me-2"></i>Send Birthday Wishes to ${memberName}`;
    form.action = `/birthdays/${memberId}/wishes`;
    
    modal.show();
}

// Send Group Wishes Function
function sendGroupWishes(type) {
    const celebrantCount = {{ $birthdays->count() }};
    const typeText = type === 'email' ? 'email' : 'SMS';
    
    if (confirm(`Are you sure you want to send birthday wishes via ${typeText} to all ${celebrantCount} celebrant(s)?`)) {
        // Implementation for group wishes
        alert(`Group ${typeText} birthday wishes feature will be implemented soon!`);
    }
}

// Export Today's Birthdays Function
function exportTodaysBirthdays() {
    // Implementation for export
    alert('Export feature will be implemented soon!');
}

// Handle individual wishes form submission
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

// Add some birthday animation
document.addEventListener('DOMContentLoaded', function() {
    // Add floating animation to birthday cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.animation = 'fadeInUp 0.6s ease-out';
        }, index * 100);
    });
});

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
`;
document.head.appendChild(style);
</script>
@endpush
