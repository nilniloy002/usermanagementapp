@extends('layouts.app')

@section('page-title', __('Pending Student Admissions'))
@section('page-heading', __('Pending Student Admissions'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Pending Student Admissions')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-borderless" id="student-admissions-table">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Application No.')</th>
                        <th class="min-width-100">@lang('Photo')</th>
                        <th class="min-width-150">@lang('Student Details')</th>
                        <th class="min-width-100">@lang('Course')</th>
                        <th class="min-width-100">@lang('Payment Method')</th>
                        <th class="min-width-100">@lang('Status')</th>
                        <th class="text-center min-width-150">@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($applications))
                        @foreach ($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->application_number }}</strong>
                                    @if($application->student_id)
                                        <br><small class="text-success">ID: {{ $application->student_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($application->photo_data)
                                        @php
                                            $filename = basename($application->photo_data);
                                            $filePath = public_path('student_photos/' . $filename);
                                            $imageUrl = asset('student_photos/' . $filename);
                                            $fileExists = file_exists($filePath);
                                        @endphp
                                        
                                        @if($fileExists)
                                            <img src="{{ $imageUrl }}" 
                                                alt="Student Photo" 
                                                class="img-thumbnail" 
                                                width="150" 
                                                height="150"
                                                style="object-fit: cover; border-radius: 2px;">
                                        @else
                                            <div class="no-photo bg-light rounded d-flex align-items-center justify-content-center" 
                                                style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                                <i class="fas fa-user text-muted"></i>
                                                <small class="text-muted ml-1">File missing</small>
                                            </div>
                                        @endif
                                    @else
                                        <div class="no-photo bg-light rounded d-flex align-items-center justify-content-center" 
                                            style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $application->name }}</strong><br>
                                    <small class="text-muted">
                                        Mobile: {{ $application->mobile }}<br>
                                        Email: {{ $application->email }}<br>
                                        Guardian: {{ $application->emergency_mobile }}
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $application->course_name }}</strong><br>
                                    <span class="text-success">৳{{ number_format($application->course_fee, 2) }}</span>
                                    @if($application->batch_code && $application->batch_code != 'N/A')
                                        <br><small class="text-info">Batch: {{ $application->batch_code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $application->payment_method_name }}</span>
                                    @if($application->transaction_id)
                                        <br><small>Txn: {{ $application->transaction_id }}</small>
                                    @endif
                                    @if($application->serial_number)
                                        <br><small>Ref: {{ $application->serial_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $application->status == 'approved' ? 'success' : 
                                        ($application->status == 'rejected' ? 'danger' : 'warning') 
                                    }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    @if($application->approved_at)
                                        <br><small>{{ $application->approved_at->format('d-m-Y') }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('student-admissions.show', $application) }}" 
                                       class="btn btn-icon btn-sm btn-info" 
                                       title="@lang('View Application')" 
                                       data-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
@if($application->status == 'pending')
    <button type="button" 
            class="btn btn-icon btn-sm btn-success" 
            title="@lang('Approve Admission')"
            data-toggle="modal" 
            data-target="#admissionModal"
            data-application-id="{{ $application->id }}"
            data-course-id="{{ $application->course_id }}"
            data-course-fee="{{ $application->course->course_fee ?? 0 }}"
            data-student-name="{{ $application->name }}"
            data-application-number="{{ $application->application_number }}"
            data-course-name="{{ $application->course->course_name ?? 'N/A' }}">
        <i class="fas fa-user-graduate"></i>
    </button>
@endif
@if($application->status == 'approved')
    <!-- ID Card Buttons -->
    <a href="{{ route('student-admissions.id-card', $application) }}" 
       class="btn btn-icon btn-sm btn-primary" 
       title="@lang('View ID Card')"
       data-toggle="tooltip">
        <i class="fas fa-id-card"></i>
    </a>
    <a href="{{ route('student-admissions.download-id-card', $application) }}" 
       class="btn btn-icon btn-sm btn-success" 
       title="@lang('Download ID Card')"
       data-toggle="tooltip">
        <i class="fas fa-download"></i>
    </a>
@endif

                                

                                    <!-- Delete Button -->
                                    <a href="{{ route('student-admissions.destroy', $application) }}" 
                                       class="btn btn-icon btn-sm btn-danger" 
                                       title="@lang('Delete Application')"
                                       data-toggle="tooltip"
                                       data-method="DELETE"
                                       data-confirm-title="@lang('Please Confirm')"
                                       data-confirm-text="@lang('Are you sure that you want to delete this application?')"
                                       data-confirm-delete="@lang('Yes, delete it!')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">
                                <em>@lang('No student applications found.')</em>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (count($applications))
                <div class="row">
                    <div class="col-md-12">
                        {{ $applications->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Admission Modal -->
<!-- Admission Modal - SIMPLIFIED -->
<div class="modal fade" id="admissionModal" tabindex="-1" role="dialog" aria-labelledby="admissionModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="admissionModalLabel">
                    <i class="fas fa-user-graduate mr-2"></i>Approve Admission
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Data will be loaded here by JavaScript -->
                <div id="modalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading application data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#student-admissions-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            "pageLength": 25,
            "order": [[0, "desc"]]
        });

        // Get CSRF token
        function getCsrfToken() {
            return $('meta[name="csrf-token"]').attr('content');
        }

        // Helper function to generate approve URL
        function getApproveUrl(applicationId) {
            const baseUrl = '{{ url("/") }}';
            return `${baseUrl}/student-admissions/${applicationId}/approve`;
        }

        function numberFormat(number) {
            return parseFloat(number).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        // Handle button click - LOAD MODAL CONTENT DYNAMICALLY
        $(document).on('click', '.btn-success[data-target="#admissionModal"]', function(e) {
            e.preventDefault();
            
            console.log('=== LOADING MODAL DATA ===');
            
            // Get data from button
            const applicationId = $(this).data('application-id');
            const courseFee = $(this).data('course-fee');
            const studentName = $(this).data('student-name');
            const applicationNumber = $(this).data('application-number');
            const courseName = $(this).data('course-name');
            
            console.log('Application Data:', {
                studentName: studentName,
                applicationNumber: applicationNumber,
                courseName: courseName,
                courseFee: courseFee,
                applicationId: applicationId
            });

            // Generate modal content HTML with CORRECT URL
            const modalContent = `
                <form id="admissionForm" method="POST" action="${getApproveUrl(applicationId)}">
                    @csrf
                    
                    <!-- Student Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-user mr-2"></i>Student Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold text-dark">Student Name</label>
                                        <p class="form-control-plaintext">${studentName}</p>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold text-dark">Application No.</label>
                                        <p class="form-control-plaintext">${applicationNumber}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Course Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold text-dark">Course Name</label>
                                        <p class="form-control-plaintext">${courseName}</p>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark">Course Fee</label>
                                        <p class="form-control-plaintext text-success font-weight-bold">৳${numberFormat(courseFee)}</p>
                                        <input type="hidden" id="courseFee" value="${courseFee}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Batch Selection -->
                    <div class="form-group">
                        <label for="batchSelect" class="font-weight-bold">Select Batch <span class="text-danger">*</span></label>
                        <select class="form-control" id="batchSelect" name="batch_id" required style="width: 100%;">
                            <option value="">Loading batches...</option>
                        </select>
                        <div id="batchInfo" class="mt-2 p-2 bg-light rounded"></div>
                    </div>

                    <!-- Financial Information -->
                    <div class="card border-warning mt-4">
                        <div class="card-header bg-warning text-dark py-2">
                            <h6 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Financial Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="discountAmount" class="font-weight-bold">Discount Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">৳</span>
                                            </div>
                                            <input type="number" class="form-control" id="discountAmount" name="discount_amount" 
                                                   step="0.01" min="0" value="0" placeholder="0.00">
                                        </div>
                                        <small class="form-text text-muted">Discount from course fee</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="depositAmount" class="font-weight-bold">Deposit Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">৳</span>
                                            </div>
                                            <input type="number" class="form-control" id="depositAmount" name="deposit_amount" 
                                                   step="0.01" min="0" required placeholder="0.00">
                                        </div>
                                        <small class="form-text text-muted">Amount paid now</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dueAmount" class="font-weight-bold">Due Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-light">৳</span>
                                            </div>
                                            <input type="number" class="form-control" id="dueAmount" name="due_amount" 
                                                   step="0.01" readonly style="background-color: #f8f9fa;">
                                        </div>
                                        <small class="form-text text-muted">Auto-calculated</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Due Date Field -->
                            <div class="row" id="dueDateSection" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nextDueDate" class="font-weight-bold">Next Due Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="nextDueDate" name="next_due_date">
                                        <small class="form-text text-muted">When the due amount should be paid</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Calculation Summary -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="alert alert-info py-2">
                                        <div class="row text-center">
                                            <div class="col-md-3 border-right">
                                                <small class="text-muted">Course Fee</small>
                                                <div class="font-weight-bold text-dark" id="summaryCourseFee">৳${numberFormat(courseFee)}</div>
                                            </div>
                                            <div class="col-md-3 border-right">
                                                <small class="text-muted">Discount</small>
                                                <div class="font-weight-bold text-danger" id="summaryDiscount">-৳0.00</div>
                                            </div>
                                            <div class="col-md-3 border-right">
                                                <small class="text-muted">Total Payable</small>
                                                <div class="font-weight-bold text-primary" id="summaryTotalPayable">৳${numberFormat(courseFee)}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Deposit</small>
                                                <div class="font-weight-bold text-success" id="summaryDeposit">৳0.00</div>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <div class="text-center">
                                            <small class="text-muted">Remaining Due</small>
                                            <div class="font-weight-bold text-warning" id="summaryDue">৳${numberFormat(courseFee)}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group mt-4">
                        <label for="remarks" class="font-weight-bold">Remarks / Notes</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" 
                                  placeholder="Any additional notes or remarks about this admission..."></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success" id="approveBtn">
                            <i class="fas fa-user-graduate mr-2"></i>Approve Admission & Generate Student ID
                        </button>
                    </div>
                </form>
            `;

            // Inject content into modal
            $('#modalContent').html(modalContent);
            
            // Load batches after content is injected
            loadBatches(applicationId, courseFee);
            
            // Initialize event listeners
            initializeEventListeners(courseFee);
            
            // Show modal
            $('#admissionModal').modal('show');
            
            console.log('=== MODAL LOADED SUCCESSFULLY ===');
        });

       function loadBatches(applicationId, courseFee) {
    console.log('Loading batches for application:', applicationId);
    
    const baseUrl = '{{ url("/") }}';
    const batchesUrl = `${baseUrl}/student-admissions/${applicationId}/batches`;
    
    $.ajax({
        url: batchesUrl,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
        },
        success: function (response) {
            console.log('Batches loaded:', response);
            
            const batchSelect = $('#batchSelect');
            batchSelect.empty();
            
            if (response && response.length > 0) {
                batchSelect.append('<option value="">Select a batch</option>');
                $.each(response, function (index, batch) {
                    const isAvailable = batch.available_seats > 0;
                    batchSelect.append(
                        '<option value="' + batch.id + '" ' +
                        'data-available="' + batch.available_seats + '" ' +
                        'data-total="' + batch.total_seat + '" ' +
                        (!isAvailable ? 'disabled' : '') + '>' + 
                        batch.batch_code + ' (Available: ' + batch.available_seats + '/' + batch.total_seat + ')' +
                        (!isAvailable ? ' - FULL' : '') +
                        '</option>'
                    );
                });
            } else {
                batchSelect.append('<option value="">No available batches</option>');
                $('#batchInfo').html('<div class="alert alert-warning p-2 mb-0">No batches available for this course.</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Batch load error:', error);
            $('#batchSelect').html('<option value="">Error loading batches</option>');
        }
    });
}
        function initializeEventListeners(courseFee) {
            // Batch selection change
            $(document).on('change', '#batchSelect', function() {
                const selectedBatch = $('#batchSelect option:selected');
                const availableSeats = selectedBatch.data('available');
                const totalSeats = selectedBatch.data('total');
                
                if (availableSeats !== undefined) {
                    const seatInfo = 'Available seats: <span class="badge badge-success">' + availableSeats + '</span> / ' + 
                                  'Total seats: <span class="badge badge-info">' + totalSeats + '</span>';
                    $('#batchInfo').html(seatInfo);
                } else {
                    $('#batchInfo').html('');
                }
            });

            // Financial calculations
            $(document).on('input', '#discountAmount, #depositAmount', function() {
                const courseFeeVal = parseFloat($('#courseFee').val()) || 0;
                const discount = parseFloat($('#discountAmount').val()) || 0;
                const deposit = parseFloat($('#depositAmount').val()) || 0;
                
                const totalPayable = courseFeeVal - discount;
                const dueAmount = Math.max(0, totalPayable - deposit);
                
                $('#summaryCourseFee').text('৳' + numberFormat(courseFeeVal));
                $('#summaryDiscount').text('-৳' + numberFormat(discount));
                $('#summaryTotalPayable').text('৳' + numberFormat(totalPayable));
                $('#summaryDeposit').text('৳' + numberFormat(deposit));
                $('#summaryDue').text('৳' + numberFormat(dueAmount));
                
                $('#dueAmount').val(dueAmount.toFixed(2));
                
                if (dueAmount > 0) {
                    $('#dueDateSection').show();
                    $('#nextDueDate').prop('required', true);
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    $('#nextDueDate').attr('min', tomorrow.toISOString().split('T')[0]);
                } else {
                    $('#dueDateSection').hide();
                    $('#nextDueDate').prop('required', false);
                }
            });

            // Form submission
            $(document).on('submit', '#admissionForm', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const submitBtn = $('#approveBtn');
                const originalText = submitBtn.html();
                
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#admissionModal').modal('hide');
                            alert('✅ ' + response.message + ' Student ID: ' + response.student_id);
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        } else {
                            alert('❌ ' + response.message);
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function (xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
                            let errorMessage = 'Please fix the following errors:\n';
                            $.each(response.errors, function (key, errors) {
                                errorMessage += '• ' + errors[0] + '\n';
                            });
                            alert('❌ ' + errorMessage);
                        } else {
                            alert('❌ An error occurred. Please try again.');
                        }
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        }
    });
</script>

<style>
.form-control-plaintext {
    font-weight: 500;
    color: #495057;
    background-color: #f8f9fa;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
}
.card-header h6 {
    font-size: 0.9rem;
}
</style>
@endsection