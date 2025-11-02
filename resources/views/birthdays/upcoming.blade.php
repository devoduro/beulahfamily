@extends('layouts.app')

@section('title', 'Upcoming Birthdays')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-week text-info me-2"></i>
                Upcoming Birthdays
            </h1>
            <p class="text-muted mb-0">Next {{ $days }} days - {{ $birthdays->count() }} upcoming celebrations</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('birthdays.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i>
                Back to All Birthdays
            </a>
            <a href="{{ route('birthdays.today') }}" class="btn btn-success">
                <i class="fas fa-birthday-cake me-1"></i>
                Today's Birthdays
            </a>
        </div>
    </div>

    <!-- Days Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="mb-0">
                        <i class="fas fa-filter me-2"></i>
                        Filter by Time Period
                    </h6>
                </div>
                <div class="col-md-4">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('birthdays.upcoming', ['days' => 7]) }}" 
                           class="btn {{ $days == 7 ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            7 Days
                        </a>
                        <a href="{{ route('birthdays.upcoming', ['days' => 30]) }}" 
                           class="btn {{ $days == 30 ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            30 Days
                        </a>
                        <a href="{{ route('birthdays.upcoming', ['days' => 60]) }}" 
                           class="btn {{ $days == 60 ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            60 Days
                        </a>
                        <a href="{{ route('birthdays.upcoming', ['days' => 90]) }}" 
                           class="btn {{ $days == 90 ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            90 Days
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($birthdays->count() > 0)
        <!-- Upcoming Birthdays Timeline -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-timeline me-2"></i>
                    Birthday Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @php
                        $currentDate = null;
                        $groupedBirthdays = $birthdays->groupBy(function($birthday) {
                            return $birthday->next_birthday->format('Y-m-d');
                        });
                    @endphp

                    @foreach($groupedBirthdays as $date => $dayBirthdays)
                        @php
                            $birthdayDate = \Carbon\Carbon::parse($date);
                            $isToday = $birthdayDate->isToday();
                            $isTomorrow = $birthdayDate->isTomorrow();
                            $isThisWeek = $birthdayDate->diffInDays() <= 7;
                        @endphp

                        <div class="timeline-item {{ $isToday ? 'timeline-today' : ($isThisWeek ? 'timeline-week' : 'timeline-future') }}">
                            <div class="timeline-marker">
                                @if($isToday)
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($isTomorrow)
                                    <i class="fas fa-clock text-info"></i>
                                @elseif($isThisWeek)
                                    <i class="fas fa-calendar-day text-primary"></i>
                                @else
                                    <i class="fas fa-calendar text-secondary"></i>
                                @endif
                            </div>
                            
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <h5 class="mb-1">
                                        {{ $birthdayDate->format('l, F j, Y') }}
                                        @if($isToday)
                                            <span class="badge bg-warning text-dark ms-2">Today</span>
                                        @elseif($isTomorrow)
                                            <span class="badge bg-info ms-2">Tomorrow</span>
                                        @else
                                            <span class="badge bg-secondary ms-2">{{ $birthdayDate->diffForHumans() }}</span>
                                        @endif
                                    </h5>
                                    <small class="text-muted">{{ $dayBirthdays->count() }} celebration{{ $dayBirthdays->count() > 1 ? 's' : '' }}</small>
                                </div>

                                <div class="row">
                                    @foreach($dayBirthdays as $birthday)
                                    <div class="col-lg-6 col-xl-4 mb-3">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    @if($birthday->photo)
                                                        <img src="{{ asset('storage/' . $birthday->photo) }}" 
                                                             class="rounded-circle me-3" 
                                                             width="50" height="50" 
                                                             alt="{{ $birthday->full_name }}"
                                                             style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 50px; height: 50px;">
                                                            <span class="text-white font-weight-bold">
                                                                {{ substr($birthday->first_name, 0, 1) }}{{ substr($birthday->last_name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $birthday->full_name }}</h6>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <small class="text-muted">
                                                                Turning {{ $birthday->age + 1 }}
                                                            </small>
                                                            <span class="badge bg-outline-primary">{{ $birthday->chapter }}</span>
                                                        </div>
                                                        
                                                        <!-- Action Buttons -->
                                                        <div class="mt-2">
                                                            <div class="btn-group btn-group-sm" role="group">
                                                                <a href="{{ route('birthdays.show', $birthday) }}" 
                                                                   class="btn btn-outline-primary btn-sm" title="View Details">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('members.show', $birthday) }}" 
                                                                   class="btn btn-outline-info btn-sm" title="View Profile">
                                                                    <i class="fas fa-user"></i>
                                                                </a>
                                                                @if($birthday->days_until_birthday <= 7)
                                                                <button type="button" 
                                                                        class="btn btn-outline-success btn-sm" 
                                                                        title="Send Birthday Wishes"
                                                                        onclick="sendWishes({{ $birthday->id }}, '{{ $birthday->full_name }}')">
                                                                    <i class="fas fa-gift"></i>
                                                                </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    This Week
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $birthdays->filter(function($b) { return $b->days_until_birthday <= 7; })->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Next 30 Days
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $birthdays->filter(function($b) { return $b->days_until_birthday <= 30; })->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Average Age
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ round($birthdays->avg('age'), 1) }} years
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Upcoming
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $birthdays->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-birthday-cake fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- No Upcoming Birthdays -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">No Upcoming Birthdays</h3>
                <p class="text-muted mb-4">
                    There are no birthdays in the next {{ $days }} days. Try extending the time period or check all birthdays.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('birthdays.upcoming', ['days' => 90]) }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-1"></i>
                        Extend to 90 Days
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

Wishing you a wonderful birthday filled with joy, laughter, and God's abundant blessings. May this new year of your life bring you happiness, good health, and prosperity.

Have a fantastic celebration!

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

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-left: 40px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 40px;
    width: 2px;
    height: calc(100% + 10px);
    background: #e3e6f0;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 10px;
    width: 20px;
    height: 20px;
    background: white;
    border: 2px solid #e3e6f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    z-index: 1;
}

.timeline-today .timeline-marker {
    border-color: #ffc107;
    background: #fff3cd;
}

.timeline-week .timeline-marker {
    border-color: #0d6efd;
    background: #cfe2ff;
}

.timeline-future .timeline-marker {
    border-color: #6c757d;
    background: #f8f9fa;
}

.timeline-content {
    background: white;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    padding: 20px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.timeline-header {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e3e6f0;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.3s ease;
}
</style>
@endpush

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

// Add entrance animations
document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    timelineItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            item.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
@endpush
