@extends('components.app-layout')

@section('title', 'Register for ' . $program->name)

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $program->name }}</h4>
                            <p class="mb-0 opacity-75">Business Registration Form</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-light text-dark fs-6">{{ $program->date_range }}</div>
                            @if($program->venue)
                                <br><small class="opacity-75">{{ $program->venue }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($program->description)
                        <p class="text-muted">{{ $program->description }}</p>
                    @endif
                    
                    @if($program->registration_fee > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Registration Fee: <strong>₵{{ number_format($program->registration_fee, 2) }}</strong>
                        </div>
                    @endif

                    @if($program->registration_deadline)
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            Registration Deadline: <strong>{{ $program->registration_deadline->format('M j, Y') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">ERGATES Conference 2025 - Business Registration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('programs.register.store', $program) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Member Selection (Optional) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Member Information (Optional)
                                </h6>
                                <div class="form-group">
                                    <label for="member_id" class="form-label">Are you a church member?</label>
                                    <select class="form-control @error('member_id') is-invalid @enderror" id="member_id" name="member_id">
                                        <option value="">Select if you are a member</option>
                                        @foreach($members as $member)
                                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                                {{ $member->full_name }} - {{ $member->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('member_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-building me-2"></i>Business Information
                                </h6>
                            </div>

                            <!-- Business Name -->
                            <div class="col-12 mb-3">
                                <label for="business_name" class="form-label">Name of Business <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                       id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Business Type -->
                            <div class="col-md-6 mb-3">
                                <label for="business_type" class="form-label">Business Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('business_type') is-invalid @enderror" 
                                        id="business_type" name="business_type" required>
                                    <option value="">Select Business Type</option>
                                    @foreach($businessTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('business_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('business_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Other Business Type -->
                            <div class="col-md-6 mb-3" id="other_business_type_div" style="display: none;">
                                <label for="business_type_other" class="form-label">Specify Other Business Type</label>
                                <input type="text" class="form-control @error('business_type_other') is-invalid @enderror" 
                                       id="business_type_other" name="business_type_other" value="{{ old('business_type_other') }}">
                                @error('business_type_other')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Services Offered -->
                            <div class="col-12 mb-3">
                                <label for="services_offered" class="form-label">Services Offered <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('services_offered') is-invalid @enderror" 
                                          id="services_offered" name="services_offered" rows="3" required>{{ old('services_offered') }}</textarea>
                                @error('services_offered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Business Address -->
                            <div class="col-12 mb-3">
                                <label for="business_address" class="form-label">Business Address or Location <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('business_address') is-invalid @enderror" 
                                          id="business_address" name="business_address" rows="2" required>{{ old('business_address') }}</textarea>
                                @error('business_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-phone me-2"></i>Contact Information
                                </h6>
                            </div>

                            <!-- Contact Name -->
                            <div class="col-md-6 mb-3">
                                <label for="contact_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_name') is-invalid @enderror" 
                                       id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required>
                                @error('contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Business Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="business_phone" class="form-label">Business Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('business_phone') is-invalid @enderror" 
                                       id="business_phone" name="business_phone" value="{{ old('business_phone') }}" required>
                                @error('business_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- WhatsApp -->
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp_number" class="form-label">WhatsApp</label>
                                <input type="tel" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                       id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                                       placeholder="If different from Business Phone number">
                                @error('whatsapp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- File Upload Section -->
                        @if($program->allow_file_uploads)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-upload me-2"></i>Attach Business Flyer or Video
                                </h6>
                                <div class="form-group">
                                    <label for="files" class="form-label">Upload Files</label>
                                    <input type="file" class="form-control @error('files.*') is-invalid @enderror" 
                                           id="files" name="files[]" multiple 
                                           accept=".pdf,.jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.mp3,.wav">
                                    <div class="form-text">
                                        Upload up to {{ $program->max_files }} supported files: PDF, audio, image, or video. 
                                        Max {{ $program->max_file_size }} MB per file.
                                    </div>
                                    @error('files.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Additional Information
                                </h6>
                            </div>

                            <!-- Special Offers -->
                            <div class="col-12 mb-3">
                                <label for="special_offers" class="form-label">Special Offers or Promotions for Conference Attendees</label>
                                <textarea class="form-control @error('special_offers') is-invalid @enderror" 
                                          id="special_offers" name="special_offers" rows="3">{{ old('special_offers') }}</textarea>
                                @error('special_offers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Additional Info -->
                            <div class="col-12 mb-3">
                                <label for="additional_info" class="form-label">Additional Information</label>
                                <textarea class="form-control @error('additional_info') is-invalid @enderror" 
                                          id="additional_info" name="additional_info" rows="3">{{ old('additional_info') }}</textarea>
                                @error('additional_info')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        @if($program->terms_and_conditions)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">Terms and Conditions</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="small">{{ $program->terms_and_conditions }}</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="accept_terms" required>
                                            <label class="form-check-label" for="accept_terms">
                                                I agree to the terms and conditions <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('programs.show', $program) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Program
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Registration
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const businessTypeSelect = document.getElementById('business_type');
    const otherBusinessTypeDiv = document.getElementById('other_business_type_div');
    const otherBusinessTypeInput = document.getElementById('business_type_other');

    function toggleOtherBusinessType() {
        if (businessTypeSelect.value === 'other') {
            otherBusinessTypeDiv.style.display = 'block';
            otherBusinessTypeInput.required = true;
        } else {
            otherBusinessTypeDiv.style.display = 'none';
            otherBusinessTypeInput.required = false;
            otherBusinessTypeInput.value = '';
        }
    }

    businessTypeSelect.addEventListener('change', toggleOtherBusinessType);
    
    // Check on page load
    toggleOtherBusinessType();
});
</script>
@endsection
