@extends('layouts.app')

@section('page-title', __('Payment Invoice'))
@section('page-heading', __('Create Payment Invoice'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('student-admissions.index') }}">@lang('Student Admissions')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Payment Invoice')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>Payment Invoice Form
                    </h5>
                </div>
                <div class="card-body">
                    <form id="paymentInvoiceForm" method="POST" action="{{ route('student-admissions.store-payment-invoice') }}">
                        @csrf
                        
                        <!-- Student Type Selection -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Student Type <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="existingStudent" name="student_type" value="existing" 
                                                       class="custom-control-input" checked>
                                                <label class="custom-control-label" for="existingStudent">
                                                    <i class="fas fa-user-check mr-2"></i>Existing Student
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="newStudent" name="student_type" value="new" 
                                                       class="custom-control-input">
                                                <label class="custom-control-label" for="newStudent">
                                                    <i class="fas fa-user-plus mr-2"></i>New Student
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Search Section (for existing students) -->
                        <div class="row mb-4" id="studentSearchSection">
                            <div class="col-md-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white py-2">
                                        <h6 class="mb-0">
                                            <i class="fas fa-search mr-2"></i>Search Existing Student
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="studentSearch" class="font-weight-bold">
                                                Search by Student ID, Name, Mobile, or Application Number
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="studentSearch" 
                                                       placeholder="Type student ID, name, mobile, or application number...">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button" id="searchStudentBtn">
                                                        <i class="fas fa-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                            <div id="searchResults" class="mt-2" style="display: none;">
                                                <div class="list-group" id="studentResultsList"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Student ID Field -->
                        <input type="hidden" name="student_id" id="selectedStudentId">

                        <!-- Student Information Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white py-2">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user-graduate mr-2"></i>Student Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name" class="font-weight-bold">Student Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" 
                                                           required placeholder="Enter student full name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="mobile" class="font-weight-bold">Mobile Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="mobile" name="mobile" 
                                                           required placeholder="Enter mobile number">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="dob" class="font-weight-bold">Date of Birth</label>
                                                    <input type="date" class="form-control" id="dob" name="dob">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="gender" class="font-weight-bold">Gender</label>
                                                    <select class="form-control" id="gender" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="email" class="font-weight-bold">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" 
                                                           placeholder="Enter email address">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="emergency_mobile" class="font-weight-bold">Emergency Mobile</label>
                                                    <input type="text" class="form-control" id="emergency_mobile" name="emergency_mobile" 
                                                           placeholder="Emergency contact number">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="student_id_display" class="font-weight-bold">Student ID</label>
                                                    <input type="text" class="form-control" id="student_id_display" 
                                                           readonly placeholder="Will be auto-filled for existing students">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="address" class="font-weight-bold">Address</label>
                                                    <textarea class="form-control" id="address" name="address" 
                                                              rows="2" placeholder="Enter full address"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white py-2">
                                        <h6 class="mb-0">
                                            <i class="fas fa-money-bill-wave mr-2"></i>Payment Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_category" class="font-weight-bold">Payment Category <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="payment_category" name="payment_category" required>
                                                        <option value="">Select Payment Category</option>
                                                        @foreach($paymentCategories as $category)
                                                            <option value="{{ $category }}">{{ $category }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="purpose" class="font-weight-bold">Purpose/Description</label>
                                                    <input type="text" class="form-control" id="purpose" name="purpose" 
                                                           placeholder="Enter payment purpose (optional)">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="due_amount" class="font-weight-bold">Due Amount <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light">৳</span>
                                                        </div>
                                                        <input type="number" class="form-control" id="due_amount" name="due_amount" 
                                                               step="0.01" min="0" required placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="deposit_amount" class="font-weight-bold">Deposit Amount <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light">৳</span>
                                                        </div>
                                                        <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" 
                                                               step="0.01" min="0" required placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="discount_amount" class="font-weight-bold">Discount Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-light">৳</span>
                                                        </div>
                                                        <input type="number" class="form-control" id="discount_amount" name="discount_amount" 
                                                               step="0.01" min="0" value="0" placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Payment Method Section -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="cash" name="payment_method" value="cash" 
                                                                       class="custom-control-input" checked>
                                                                <label class="custom-control-label" for="cash">
                                                                    <i class="fas fa-money-bill mr-2"></i>Cash
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="bkash" name="payment_method" value="bkash" 
                                                                       class="custom-control-input">
                                                                <label class="custom-control-label" for="bkash">
                                                                    <i class="fas fa-mobile-alt mr-2"></i>bKash
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="bank" name="payment_method" value="bank" 
                                                                       class="custom-control-input">
                                                                <label class="custom-control-label" for="bank">
                                                                    <i class="fas fa-university mr-2"></i>Bank
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Dynamic Fields based on Payment Method -->
                                        <div class="row" id="transactionIdSection" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="transaction_id" class="font-weight-bold">Transaction ID <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                                                           placeholder="Enter bKash transaction ID">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row" id="serialNumberSection" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="serial_number" class="font-weight-bold">Serial Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="serial_number" name="serial_number" 
                                                           placeholder="Enter bank serial number">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="remarks" class="font-weight-bold">Remarks</label>
                                                    <textarea class="form-control" id="remarks" name="remarks" 
                                                              rows="2" placeholder="Any additional remarks..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <div class="row text-center">
                                        <div class="col-md-4 border-right">
                                            <small class="text-muted">Due Amount</small>
                                            <div class="font-weight-bold text-dark" id="summaryDue">৳0.00</div>
                                        </div>
                                        <div class="col-md-4 border-right">
                                            <small class="text-muted">Deposit Amount</small>
                                            <div class="font-weight-bold text-success" id="summaryDeposit">৳0.00</div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Balance</small>
                                            <div class="font-weight-bold text-warning" id="summaryBalance">৳0.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn">
                                    <i class="fas fa-check-circle mr-2"></i>Create Payment Invoice
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="successModalBody">
                    <!-- Content will be dynamically added -->
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="errorModalBody">
                    <!-- Content will be dynamically added -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
$(document).ready(function() {
    // Get CSRF token
    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }

    // Student type toggle
    $('input[name="student_type"]').change(function() {
        if ($(this).val() === 'existing') {
            $('#studentSearchSection').show();
            clearStudentFields();
        } else {
            $('#studentSearchSection').hide();
            $('#selectedStudentId').val('');
            $('#student_id_display').val('');
        }
    });

    // Clear student fields function
    function clearStudentFields() {
        $('#name').val('');
        $('#mobile').val('');
        $('#email').val('');
        $('#dob').val('');
        $('#gender').val('');
        $('#emergency_mobile').val('');
        $('#address').val('');
    }

    // Search student
    $('#searchStudentBtn').click(function() {
        const query = $('#studentSearch').val().trim();
        if (query.length < 2) {
            showErrorModal('Please enter at least 2 characters to search');
            return;
        }

        $.ajax({
            url: '{{ route("student-admissions.search-student") }}',
            method: 'GET',
            data: { query: query },
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            success: function(response) {
                const resultsList = $('#studentResultsList');
                resultsList.empty();
                
                if (response.length > 0) {
                    response.forEach(function(student) {
                        const item = `
                            <a href="#" class="list-group-item list-group-item-action student-item" 
                               data-id="${student.id}" 
                               data-student-id="${student.student_id || ''}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">${student.name}</h6>
                                    <small class="text-success">${student.student_id || 'No ID'}</small>
                                </div>
                                <p class="mb-1">
                                    <small>Mobile: ${student.mobile}</small> | 
                                    <small>Email: ${student.email || 'N/A'}</small>
                                </p>
                                <small>App No: ${student.application_number}</small>
                            </a>
                        `;
                        resultsList.append(item);
                    });
                    $('#searchResults').show();
                } else {
                    resultsList.append('<div class="list-group-item text-center">No students found</div>');
                    $('#searchResults').show();
                }
            },
            error: function() {
                showErrorModal('Error searching students. Please try again.');
            }
        });
    });

    // Select student from search results
    $(document).on('click', '.student-item', function(e) {
        e.preventDefault();
        const studentId = $(this).data('id');
        const studentDisplayId = $(this).data('student-id');
        
        // Load student details
        $.ajax({
            url: `{{ url('student-admissions') }}/${studentId}/details`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    const student = response.student;
                    
                    // Fill form fields
                    $('#selectedStudentId').val(studentId);
                    $('#student_id_display').val(student.student_id || '');
                    $('#name').val(student.name);
                    $('#mobile').val(student.mobile);
                    $('#email').val(student.email || '');
                    $('#dob').val(student.dob || '');
                    $('#gender').val(student.gender || '');
                    $('#emergency_mobile').val(student.emergency_mobile || '');
                    $('#address').val(student.address || '');
                    
                    // Set due amount from existing payment
                    if (student.due_amount > 0) {
                        $('#due_amount').val(student.due_amount);
                        updateSummary();
                    }
                    
                    // Close search results
                    $('#searchResults').hide();
                    $('#studentSearch').val('');
                    
                    // Show success notification
                    $('#successModal .modal-title').text('Student Selected');
                    $('#successModal .modal-body').html(`
                        <div class="text-center">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p>Student "<strong>${student.name}</strong>" selected successfully!</p>
                            <p><small>Student ID: ${student.student_id || 'N/A'}</small></p>
                        </div>
                    `);
                    $('#successModal').modal('show');
                }
            },
            error: function() {
                showErrorModal('Error loading student details. Please try again.');
            }
        });
    });

    // Payment method toggle
    $('input[name="payment_method"]').change(function() {
        const method = $(this).val();
        
        // Hide all dynamic sections
        $('#transactionIdSection').hide();
        $('#serialNumberSection').hide();
        
        // Clear required attributes
        $('#transaction_id').prop('required', false);
        $('#serial_number').prop('required', false);
        
        // Show relevant section based on method
        if (method === 'bkash') {
            $('#transactionIdSection').show();
            $('#transaction_id').prop('required', true);
        } else if (method === 'bank') {
            $('#serialNumberSection').show();
            $('#serial_number').prop('required', true);
        }
    });

    // Update summary on amount changes
    function updateSummary() {
        const due = parseFloat($('#due_amount').val()) || 0;
        const deposit = parseFloat($('#deposit_amount').val()) || 0;
        const discount = parseFloat($('#discount_amount').val()) || 0;
        
        const balance = Math.max(0, due - deposit);
        
        $('#summaryDue').text('৳' + due.toFixed(2));
        $('#summaryDeposit').text('৳' + deposit.toFixed(2));
        $('#summaryBalance').text('৳' + balance.toFixed(2));
    }

    // Bind amount change events
    $('#due_amount, #deposit_amount, #discount_amount').on('input', updateSummary);

    // Form submission
    $('#paymentInvoiceForm').submit(function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
        
        // Prepare form data
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    // Show success modal
                    $('#successModal .modal-title').text('Success!');
                    $('#successModal .modal-body').html(`
                        <div class="text-center">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success mb-3">${response.message}</h5>
                            <p class="mb-4">Payment invoice has been created successfully.</p>
                            <div class="mt-4">
                                <a href="${response.redirect_url}" class="btn btn-success mr-2">
                                    <i class="fas fa-receipt mr-2"></i>View Receipt
                                </a>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetForm()">
                                    <i class="fas fa-plus mr-2"></i>Create Another Invoice
                                </button>
                            </div>
                        </div>
                    `);
                    $('#successModal').modal('show');
                } else {
                    showErrorModal(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = '<div class="text-left"><p class="mb-2">Please fix the following errors:</p><ul>';
                    $.each(response.errors, function (key, errors) {
                        errorMessage += '<li>' + errors[0] + '</li>';
                    });
                    errorMessage += '</ul></div>';
                    showErrorModal('Validation Error', errorMessage);
                } else {
                    showErrorModal('An error occurred. Please try again.');
                }
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Function to show error modal
    function showErrorModal(title, message = '') {
        $('#errorModal .modal-title').text(title);
        $('#errorModal .modal-body').html(message || 'Something went wrong. Please try again.');
        $('#errorModal').modal('show');
    }

    // Function to reset form
    function resetForm() {
        $('#paymentInvoiceForm')[0].reset();
        $('#studentSearchSection').show();
        $('#selectedStudentId').val('');
        $('#student_id_display').val('');
        $('input[name="student_type"][value="existing"]').prop('checked', true);
        $('input[name="payment_method"][value="cash"]').prop('checked', true);
        $('#transactionIdSection').hide();
        $('#serialNumberSection').hide();
        $('#transaction_id').prop('required', false);
        $('#serial_number').prop('required', false);
        $('#searchResults').hide();
        $('#studentSearch').val('');
        clearStudentFields();
        updateSummary();
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i>Create Payment Invoice');
    }

    // Initialize summary
    updateSummary();
});
</script>

<style>
.student-item:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}
.custom-control-label {
    cursor: pointer;
}
.form-control:readonly {
    background-color: #f8f9fa;
}
#searchResults {
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endsection