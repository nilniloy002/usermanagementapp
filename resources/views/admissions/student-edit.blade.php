@extends('layouts.app')

@section('page-title', __('Edit Student Application'))
@section('page-heading', __('Edit Student Application'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('student-admissions.pending-student-index') }}">@lang('Pending Admissions')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Edit Application')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">
                <i class="fas fa-edit mr-2"></i>
                Edit Application: {{ $application->application_number }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('student-admissions.update', $application) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Personal Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-user mr-2"></i>Personal Information
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $application->name) }}" required>
                        </div>
                    </div>
                    
                   <!-- Replace the DOB field section (around lines 55-65) with: -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dob" class="font-weight-bold">Date of Birth <span class="text-danger">*</span><small>(dd/mm/yyyy)</small></label>
                            <input type="date" class="form-control" id="dob" name="dob" 
                                value="{{ old('dob', \Carbon\Carbon::parse($application->dob)->format('Y-m-d')) }}" 
                                max="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="gender" class="font-weight-bold">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="male" {{ old('gender', $application->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $application->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-phone mr-2"></i>Contact Information
                        </h6>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mobile" class="font-weight-bold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile" name="mobile" 
                                   value="{{ old('mobile', $application->mobile) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="emergency_mobile" class="font-weight-bold">Emergency Contact <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="emergency_mobile" name="emergency_mobile" 
                                   value="{{ old('emergency_mobile', $application->emergency_mobile) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $application->email) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address" class="font-weight-bold">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address', $application->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Educational Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-graduation-cap mr-2"></i>Educational Information
                        </h6>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="educational_background" class="font-weight-bold">Educational Background <span class="text-danger">*</span></label>
                            <select class="form-control" id="educational_background" name="educational_background" required>
                                <option value="SSC" {{ old('educational_background', $application->educational_background) == 'SSC' ? 'selected' : '' }}>SSC</option>
                                <option value="HSC" {{ old('educational_background', $application->educational_background) == 'HSC' ? 'selected' : '' }}>HSC</option>
                                <option value="bachelor" {{ old('educational_background', $application->educational_background) == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
                                <option value="masters" {{ old('educational_background', $application->educational_background) == 'masters' ? 'selected' : '' }}>Masters</option>
                                <option value="others" {{ old('educational_background', $application->educational_background) == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="otherEducationField" style="{{ old('educational_background', $application->educational_background) == 'others' ? '' : 'display: none;' }}">
                        <div class="form-group">
                            <label for="other_education" class="font-weight-bold">Specify Education <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="other_education" name="other_education" 
                                   value="{{ old('other_education', $application->other_education) }}"
                                   placeholder="e.g., Diploma, Certificate, etc.">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="academic_year" class="font-weight-bold">Academic Year <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="academic_year" name="academic_year" 
                                   value="{{ old('academic_year', $application->academic_year) }}" required
                                   placeholder="e.g., 2024-2025">
                        </div>
                    </div>
                </div>

                <!-- Course Selection -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-book mr-2"></i>Course Information
                        </h6>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="course_id" class="font-weight-bold">Select Course <span class="text-danger">*</span></label>
                            <select class="form-control" id="course_id" name="course_id" required>
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" 
                                        {{ old('course_id', $application->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }} (৳{{ number_format($course->course_fee, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Current Information</h6>
                                <p class="mb-1"><strong>Application No:</strong> {{ $application->application_number }}</p>
                                <p class="mb-1"><strong>Current Course:</strong> {{ $application->course_name }}</p>
                                <p class="mb-1"><strong>Course Fee:</strong> ৳{{ number_format($application->course_fee, 2) }}</p>
                                <p class="mb-0"><strong>Status:</strong> 
                                    <span class="badge badge-warning">{{ ucfirst($application->status) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fas fa-money-bill-wave mr-2"></i>Payment Information
                        </h6>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment_method" class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cash" {{ old('payment_method', $application->payment->payment_method ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bkash" {{ old('payment_method', $application->payment->payment_method ?? '') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                <option value="bank" {{ old('payment_method', $application->payment->payment_method ?? '') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="transactionIdField" style="{{ old('payment_method', $application->payment->payment_method ?? '') == 'bkash' ? '' : 'display: none;' }}">
                        <div class="form-group">
                            <label for="transaction_id" class="font-weight-bold">Transaction ID <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                                   value="{{ old('transaction_id', $application->payment->transaction_id ?? '') }}"
                                   placeholder="bKash transaction number">
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="serialNumberField" style="{{ old('payment_method', $application->payment->payment_method ?? '') == 'bank' ? '' : 'display: none;' }}">
                        <div class="form-group">
                            <label for="serial_number" class="font-weight-bold">Serial/Reference No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number" 
                                   value="{{ old('serial_number', $application->payment->serial_number ?? '') }}"
                                   placeholder="Bank deposit slip number">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('student-admissions.pending-student-index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Back to List
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Update Application
                                </button>
                                <a href="{{ route('student-admissions.show', $application) }}" 
                                   class="btn btn-info ml-2">
                                    <i class="fas fa-eye mr-2"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function() {
        // Show/hide other education field
        $('#educational_background').change(function() {
            if ($(this).val() === 'others') {
                $('#otherEducationField').show();
                $('#other_education').prop('required', true);
            } else {
                $('#otherEducationField').hide();
                $('#other_education').prop('required', false);
            }
        });

        // Show/hide payment method fields
        $('#payment_method').change(function() {
            const method = $(this).val();
            
            // Reset all fields
            $('#transactionIdField').hide();
            $('#serialNumberField').hide();
            $('#transaction_id').prop('required', false);
            $('#serial_number').prop('required', false);
            
            // Show relevant field
            if (method === 'bkash') {
                $('#transactionIdField').show();
                $('#transaction_id').prop('required', true);
            } else if (method === 'bank') {
                $('#serialNumberField').show();
                $('#serial_number').prop('required', true);
            }
        });

        // Trigger change events on page load
        $('#educational_background').trigger('change');
        $('#payment_method').trigger('change');
    });
</script>
@endsection