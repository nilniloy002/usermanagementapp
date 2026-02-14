@extends('layouts.app')

@section('page-title', __('Student Admissions'))
@section('page-heading', __('Student Admissions'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Student Admissions')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('student-admissions.index') }}" method="GET" id="filterForm">
            <div class="row">
                <!-- Course Filter -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="course_id" class="form-label">Course</label>
                        <select class="form-control select2" id="course_id" name="course_id">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                    {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->course_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Batch Filter -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="batch_id" class="form-label">Batch</label>
                        <select class="form-control select2" id="batch_id" name="batch_id">
                            <option value="">All Batches</option>
                            @if($selectedCourseId)
                                <!-- Show only batches for selected course -->
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" 
                                        {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->batch_code }}
                                    </option>
                                @endforeach
                            @else
                                <!-- Show all batches with course names -->
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" 
                                        {{ request('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->batch_code }} ({{ $batch->course->course_name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <!-- Payment Status Filter -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">All</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                Paid in Full
                            </option>
                            <option value="due" {{ request('payment_status') == 'due' ? 'selected' : '' }}>
                                Has Due Amount
                            </option>
                        </select>
                    </div>
                </div>
                
                <!-- Search -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search" class="form-label">Search</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Name, ID, Mobile, Email..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="col-md-1">
                    <div class="form-group d-flex align-items-end">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary" title="Apply Filters">
                                <i class="fas fa-filter"></i>
                            </button>
                            <a href="{{ route('student-admissions.index') }}" 
                               class="btn btn-secondary" title="Reset Filters">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Filters -->
            @if(request()->anyFilled(['course_id', 'batch_id', 'payment_status', 'search']))
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap align-items-center">
                        <small class="text-muted mr-2">Active Filters:</small>
                        @if(request('course_id'))
                            @php
                                $course = \Vanguard\Course::find(request('course_id'));
                            @endphp
                            @if($course)
                            <span class="badge badge-info mr-2 mb-1">
                                Course: {{ $course->course_name }}
                                <a href="{{ route('student-admissions.index', array_merge(request()->except('course_id'), ['page' => 1])) }}" 
                                   class="text-white ml-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                        @endif
                        
                        @if(request('batch_id'))
                            @php
                                $batch = \Vanguard\Batch::find(request('batch_id'));
                            @endphp
                            @if($batch)
                            <span class="badge badge-info mr-2 mb-1">
                                Batch: {{ $batch->batch_code }}
                                <a href="{{ route('student-admissions.index', array_merge(request()->except('batch_id'), ['page' => 1])) }}" 
                                   class="text-white ml-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                        @endif
                        
                        @if(request('payment_status'))
                            <span class="badge badge-{{ request('payment_status') == 'due' ? 'warning' : 'success' }} mr-2 mb-1">
                                Payment: {{ ucfirst(request('payment_status')) }}
                                <a href="{{ route('student-admissions.index', array_merge(request()->except('payment_status'), ['page' => 1])) }}" 
                                   class="text-white ml-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        
                        @if(request('search'))
                            <span class="badge badge-secondary mr-2 mb-1">
                                Search: "{{ request('search') }}"
                                <a href="{{ route('student-admissions.index', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                   class="text-white ml-1">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

    <!-- Student List Card -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">
                    Students ({{ $applications->total() }} found)
                    @if(request()->anyFilled(['course_id', 'batch_id', 'payment_status', 'search']))
                        <small class="text-muted"> - Filtered</small>
                    @endif
                </h5>
                
                <!-- Export Buttons -->
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-download mr-1"></i> Export
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" id="exportCSV">
                            <i class="fas fa-file-csv mr-2"></i> CSV
                        </a>
                        <a class="dropdown-item" href="#" id="exportPDF">
                            <i class="fas fa-file-pdf mr-2"></i> PDF
                        </a>
                        <a class="dropdown-item" href="#" id="exportExcel">
                            <i class="fas fa-file-excel mr-2"></i> Excel
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-borderless" id="student-admissions-table">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Invoice No.')</th>
                        <th class="min-width-80">@lang('Photo')</th>
                        <th class="min-width-150">@lang('Student Details')</th>
                        <th class="min-width-120">@lang('Course')</th>
                        <th class="min-width-120">@lang('Payment Info')</th>
                        <th class="min-width-80">@lang('Status')</th>
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
                                            $imageUrl = asset('student_photos/' . $filename);
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                            alt="Student Photo" 
                                            class="img-thumbnail" 
                                            width="150" 
                                            height="200"
                                            style="object-fit: cover; border-radius: 2px;"
                                            onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjNmM3NTdkIiBkPSJNMTIgMkM2LjQ4IDIgMiA2LjQ4IDIgMTJzNC40OCAxMCAxMCAxMCAxMC00LjQ4IDEwLTEwUzE3LjUyIDIgMTIgMnptMCAzYzEuNjYgMCAzIDEuMzQgMyAzcy0xLjM0IDMtMyAzLTMtMS4zNC0zLTMgMS4zNC0zIDMtM3ptMCAxNC4yYy0yLjUgMC00LjcxLTEuMjgtNi0zLjIyLjAzLTEuOTkgNC0zLjA4IDYtMy4wOCAxLjk5IDAgNS45NyAxLjA5IDYgMy4wOC0xLjI5IDEuOTQtMy41IDMuMjItNiAzLjIyeiIvPjwvc3ZnPg=='">
                                    @else
                                        <div class="no-photo bg-light rounded d-flex align-items-center justify-content-center" 
                                            style="width: 60px; height: 60px; border: 1px solid #dee2e6;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $application->name }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-phone-alt mr-1"></i>{{ $application->mobile }}<br>
                                        <i class="fas fa-envelope mr-1"></i>{{ $application->email }}<br>
                                        <i class="fas fa-user-shield mr-1"></i>{{ $application->emergency_mobile }}
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $application->course_name }}</strong><br>
                                    <span class="text-success font-weight-bold">৳{{ number_format($application->course_fee, 2) }}</span>
                                    @if($application->batch_code && $application->batch_code != 'N/A')
                                        <br><small class="text-info"><i class="fas fa-users mr-1"></i>{{ $application->batch_code }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($application->payment)
                                        <!-- Payment Method -->
                                        <span class="badge badge-{{ 
                                            $application->payment->payment_method == 'cash' ? 'success' : 
                                            ($application->payment->payment_method == 'bkash' ? 'primary' : 'info') 
                                        }}">
                                            {{ $application->payment->payment_method_name }}
                                        </span>
                                        
                                        <!-- Payment Received By -->
                                        @if($application->payment->payment_received_by)
                                            <br><small class="text-muted">
                                                <i class="fas fa-user-check mr-1"></i>Received by: {{ $application->payment->payment_received_by }}
                                            </small>
                                        @endif
                                        
                                        <!-- Transaction ID -->
                                        @if($application->payment->transaction_id)
                                            <br><small><i class="fas fa-receipt mr-1"></i>{{ $application->payment->transaction_id }}</small>
                                        @endif
                                        
                                        <!-- Serial Number -->
                                        @if($application->payment->serial_number)
                                            <br><small><i class="fas fa-hashtag mr-1"></i>{{ $application->payment->serial_number }}</small>
                                        @endif
                                        
                                        <!-- Financial Summary -->
                                        <div class="mt-2">
                                            @if($application->payment->deposit_amount > 0)
                                                <small class="d-block text-success">
                                                    <i class="fas fa-money-bill-wave mr-1"></i>Deposit: ৳{{ number_format($application->payment->deposit_amount, 2) }}
                                                </small>
                                            @endif
                                            
                                            @if($application->payment->discount_amount > 0)
                                                <small class="d-block text-danger">
                                                    <i class="fas fa-tag mr-1"></i>Discount: -৳{{ number_format($application->payment->discount_amount, 2) }}
                                                </small>
                                            @endif
                                            
                                            @if($application->payment->due_amount > 0)
                                                <small class="d-block text-warning">
                                                    <i class="fas fa-clock mr-1"></i>Due: ৳{{ number_format($application->payment->due_amount, 2) }}
                                                </small>
                                            @else
                                                <small class="d-block text-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Paid in Full
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="badge badge-secondary">No Payment</span>
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
                                        <br><small class="text-muted">{{ $application->approved_at->format('d-m-Y') }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- View Button -->
                                    <a href="{{ route('student-admissions.show', $application) }}" 
                                       class="btn btn-icon btn-sm btn-info" 
                                       title="@lang('View Application')" 
                                       data-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Approve Button (only for pending applications) -->
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
                                    
                                    <!-- ID Card Buttons (only for approved applications) -->
                                    @if($application->status == 'approved')
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

                                    <!-- Status Update Dropdown -->
                                    <div class="btn-group">
                                        <button type="button" 
                                                class="btn btn-icon btn-sm btn-secondary dropdown-toggle" 
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" 
                                                aria-expanded="false"
                                                title="@lang('Update Status')">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <form action="{{ route('student-admissions.update-status', $application) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        name="status" 
                                                        value="approved" 
                                                        class="dropdown-item text-success {{ $application->status == 'approved' ? 'disabled' : '' }}">
                                                    <i class="fas fa-check mr-2"></i>Approve
                                                </button>
                                                <button type="submit" 
                                                        name="status" 
                                                        value="rejected" 
                                                        class="dropdown-item text-danger {{ $application->status == 'rejected' ? 'disabled' : '' }}">
                                                    <i class="fas fa-times mr-2"></i>Reject
                                                </button>
                                                <button type="submit" 
                                                        name="status" 
                                                        value="pending" 
                                                        class="dropdown-item text-warning {{ $application->status == 'pending' ? 'disabled' : '' }}">
                                                    <i class="fas fa-clock mr-2"></i>Pending
                                                </button>
                                            </form>
                                        </div>
                                    </div>

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

            <!-- Pagination with filter preservation -->
            @if (count($applications))
                <div class="row">
                    <div class="col-md-12">
                        {{ $applications->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Admission Modal -->
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
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Clear search button
        $('#clearSearch').click(function() {
            $('#search').val('');
            $('#filterForm').submit();
        });

        // Dynamic batch loading based on course selection (AJAX)
$('#course_id').change(function() {
    const courseId = $(this).val();
    const batchSelect = $('#batch_id');
    
    if (courseId) {
        batchSelect.html('<option value="">Loading batches...</option>');
        
        $.ajax({
            url: '/student-admissions/batches-by-course/' + courseId,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                batchSelect.html('<option value="">All Batches</option>');
                
                if (response.success && response.batches.length > 0) {
                    $.each(response.batches, function(index, batch) {
                        batchSelect.append('<option value="' + batch.id + '">' + batch.batch_code + '</option>');
                    });
                } else {
                    batchSelect.append('<option value="">No batches available</option>');
                }
                
                // Reinitialize Select2
                batchSelect.select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading batches:', error);
                batchSelect.html('<option value="">Error loading batches</option>');
                batchSelect.select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            }
        });
    } else {
        // If no course selected, show all active batches
        batchSelect.html('<option value="">All Batches</option>');
        
        // Load all active batches
        $.ajax({
            url: '/batches/active',
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success && response.batches.length > 0) {
                    $.each(response.batches, function(index, batch) {
                        batchSelect.append('<option value="' + batch.id + '">' + batch.batch_code + ' (' + (batch.course_name || 'N/A') + ')</option>');
                    });
                }
                
                // Reinitialize Select2
                batchSelect.select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            },
            error: function() {
                batchSelect.select2({
                    theme: 'bootstrap4',
                    width: '100%'
                });
            }
        });
    }
});

// Remove auto-submit for course and batch filters
$('#course_id, #batch_id, #payment_status').change(function() {
    // Don't auto-submit, let user click filter button
});

// Update the filter form submission to handle AJAX loading
$('#filterForm').submit(function(e) {
    // Prevent auto-submit when course changes
    // Let the form submit normally when filter button is clicked
});

        // Export functionality
        $('#exportCSV').click(function(e) {
            e.preventDefault();
            exportData('csv');
        });
        
        $('#exportPDF').click(function(e) {
            e.preventDefault();
            exportData('pdf');
        });
        
        $('#exportExcel').click(function(e) {
            e.preventDefault();
            exportData('excel');
        });

        function exportData(type) {
            let url = new URL(window.location.href);
            url.searchParams.set('export', type);
            window.open(url.toString(), '_blank');
        }

        // Initialize DataTable with filter preservation
        const table = $('#student-admissions-table').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'btn btn-sm btn-outline-secondary'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-sm btn-outline-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm btn-outline-danger'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-sm btn-outline-info'
                }
            ],
            "pageLength": 20,
            "order": [[0, "desc"]],
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Search within results:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries available",
                "infoFiltered": "(filtered from _MAX_ total entries)"
            },
            "initComplete": function() {
                // Add search box to DataTable
                this.api().columns().every(function() {
                    var column = this;
                    if (column.index() === 2 || column.index() === 0 || column.index() === 3) {
                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search...">')
                            .appendTo($(column.header()))
                            .on('keyup change clear', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    }
                });
            }
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
                        <select class="form-control select2" id="batchSelect" name="batch_id" required style="width: 100%;">
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
            
            // Initialize Select2 for batch select in modal
            $('#batchSelect').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
            
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
            const batchesUrl = `${baseUrl}/student-admissions/${applicationId}/course-batches`;
            
            console.log('Batch URL:', batchesUrl);
            
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
                    
                    // Reinitialize Select2
                    batchSelect.select2({
                        theme: 'bootstrap4',
                        width: '100%'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Batch load error:', error, xhr.responseText);
                    $('#batchSelect').html('<option value="">Error loading batches</option>');
                    $('#batchSelect').select2({
                        theme: 'bootstrap4',
                        width: '100%'
                    });
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
                        console.log('Approval response:', response);
                        if (response.success) {
                            $('#admissionModal').modal('hide');
                            
                            // Show success notification
                            Swal.fire({
                                icon: 'success',
                                title: 'Admission Approved',
                                text: 'Student ID: ' + response.student_id,
                                confirmButtonText: 'OK',
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function (xhr) {
                        console.error('Approval error:', xhr.responseText);
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
                            let errorMessage = 'Please fix the following errors:\n';
                            $.each(response.errors, function (key, errors) {
                                errorMessage += '• ' + errors[0] + '\n';
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred. Please try again.',
                                confirmButtonText: 'OK'
                            });
                        }
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        }

        // Tooltip initialization
        $('[data-toggle="tooltip"]').tooltip();
        
        // Confirm delete
        $('a[data-method="DELETE"]').click(function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            const title = $(this).data('confirm-title');
            const text = $(this).data('confirm-text');
            const confirmText = $(this).data('confirm-delete');
            
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: href,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        success: function() {
                            window.location.reload();
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete application. Please try again.'
                            });
                        }
                    });
                }
            });
        });
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
.no-photo {
    font-size: 0.8rem;
}
.badge {
    font-size: 0.75em;
}
.table th {
    font-weight: 600;
}
.btn-group .dropdown-toggle::after {
    margin-left: 0.2em;
}
/* Filter section styles */
.input-group-append .btn {
    border-left: 0;
}
.select2-container--bootstrap4 .select2-selection--single {
    height: calc(1.5em + 0.75rem + 2px);
}
.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: calc(1.5em + 0.75rem);
}
.badge a {
    text-decoration: none;
    opacity: 0.8;
}
.badge a:hover {
    opacity: 1;
}
</style>
@endsection