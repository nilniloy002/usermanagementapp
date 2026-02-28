@extends('layouts.app')

@section('page-title', __('Payment Invoices'))
@section('page-heading', __('Payment Invoices'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Payment Invoices')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-filter mr-2"></i>Filter Payment Invoices
                <button class="btn btn-sm btn-link float-right" type="button" data-toggle="collapse" data-target="#filterCollapse">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </h6>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form action="{{ route('student-admissions.payment-invoices') }}" method="GET" id="filterForm">
                    <div class="row">
                        <!-- Payment Category Filter -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="payment_category" class="form-label">Payment Category</label>
                                <select class="form-control select2" id="payment_category" name="payment_category">
                                    <option value="">All Categories</option>
                                    @php
                                        $categories = [
                                            'Mock Tests',
                                            'Speaking Tests', 
                                            'Admission Due Collections',
                                            '2nd Semester Fee',
                                            '3rd Semester Fee',
                                            'Final Semester Fee',
                                            'Other'
                                        ];
                                    @endphp
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" 
                                            {{ request('payment_category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- Payment Method Filter -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-control" id="payment_method" name="payment_method">
                                    <option value="">All Methods</option>
                                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Received By Filter -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="received_by" class="form-label">Received By</label>
                                <input type="text" class="form-control" id="received_by" name="received_by" 
                                       placeholder="Receiver name" value="{{ request('received_by') }}">
                            </div>
                        </div>
                        
                        <!-- Search -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search" class="form-label">Search</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Student name, ID, mobile..." 
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
                        <div class="col-md-2">
                            <div class="form-group d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary" title="Apply Filters">
                                        <i class="fas fa-filter mr-1"></i> Apply
                                    </button>
                                    <a href="{{ route('student-admissions.payment-invoices') }}" 
                                       class="btn btn-secondary" title="Reset Filters">
                                        <i class="fas fa-redo mr-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <hr class="my-2">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                       value="{{ request('date_from') }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                       value="{{ request('date_to') }}" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group d-flex align-items-end">
                                <button type="button" class="btn btn-outline-info" id="clearDates">
                                    <i class="fas fa-calendar-times mr-1"></i> Clear Dates
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters -->
                    @if(request()->anyFilled(['payment_category', 'payment_method', 'received_by', 'search', 'date_from', 'date_to']))
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-center">
                                <small class="text-muted mr-2">Active Filters:</small>
                                
                                @if(request('payment_category'))
                                    <span class="badge badge-info mr-2 mb-1">
                                        Category: {{ request('payment_category') }}
                                        <a href="{{ route('student-admissions.payment-invoices', array_merge(request()->except('payment_category'), ['page' => 1])) }}" 
                                           class="text-white ml-1">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                
                                @if(request('payment_method'))
                                    <span class="badge badge-{{ request('payment_method') == 'cash' ? 'success' : (request('payment_method') == 'bkash' ? 'primary' : 'info') }} mr-2 mb-1">
                                        Method: {{ ucfirst(request('payment_method')) }}
                                        <a href="{{ route('student-admissions.payment-invoices', array_merge(request()->except('payment_method'), ['page' => 1])) }}" 
                                           class="text-white ml-1">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                
                                @if(request('received_by'))
                                    <span class="badge badge-secondary mr-2 mb-1">
                                        Receiver: "{{ request('received_by') }}"
                                        <a href="{{ route('student-admissions.payment-invoices', array_merge(request()->except('received_by'), ['page' => 1])) }}" 
                                           class="text-white ml-1">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                
                                @if(request('search'))
                                    <span class="badge badge-secondary mr-2 mb-1">
                                        Search: "{{ request('search') }}"
                                        <a href="{{ route('student-admissions.payment-invoices', array_merge(request()->except('search'), ['page' => 1])) }}" 
                                           class="text-white ml-1">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                                
                                @if(request('date_from') || request('date_to'))
                                    <span class="badge badge-primary mr-2 mb-1">
                                        Date: 
                                        @if(request('date_from') && request('date_to'))
                                            {{ request('date_from') }} to {{ request('date_to') }}
                                        @elseif(request('date_from'))
                                            From {{ request('date_from') }}
                                        @elseif(request('date_to'))
                                            Until {{ request('date_to') }}
                                        @endif
                                        <a href="{{ route('student-admissions.payment-invoices', array_merge(request()->except(['date_from', 'date_to']), ['page' => 1])) }}" 
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
    </div>

    <!-- Invoices List Card -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i>Payment Invoices
                        <span class="badge bg-primary">{{ $payments->total() }}</span>
                        @if(request()->anyFilled(['payment_category', 'payment_method', 'received_by', 'search', 'date_from', 'date_to']))
                            <small class="text-muted"> - Filtered</small>
                        @endif
                    </h5>
                </div>
                
                <!-- Create New Button -->
                <div>
                    <a href="{{ route('student-admissions.payment-invoice-form') }}" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Create New Invoice
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-borderless" id="payment-invoices-table">
                    <thead>
                        <tr>
                            <th width="80">Invoice ID</th>
                            <th width="200">Student Details</th>
                            <th width="150">Category</th>
                            <th width="120">Payment Method</th>
                            <th width="120">Amount</th>
                            <th width="120">Received By</th>
                            <th width="100">Date</th>
                            <th width="100" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (count($payments))
                        @foreach ($payments as $payment)
                            <tr>
                                <td>
                                    <strong>#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                    @if($payment->studentAdmission && $payment->studentAdmission->student_id)
                                        <br><small class="text-success">ID: {{ $payment->studentAdmission->student_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->studentAdmission)
                                        <strong>{{ $payment->studentAdmission->name }}</strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-phone-alt mr-1"></i>{{ $payment->studentAdmission->mobile }}<br>
                                            @if($payment->studentAdmission->course_name)
                                                <i class="fas fa-book mr-1"></i>{{ $payment->studentAdmission->course_name }}
                                            @endif
                                        </small>
                                    @else
                                        <em class="text-muted">Student not found</em>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $payment->payment_category }}</span>
                                    @if($payment->purpose)
                                        <br><small class="text-muted">{{$payment->purpose}}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $payment->payment_method == 'cash' ? 'success' : 
                                        ($payment->payment_method == 'bkash' ? 'primary' : 'info') 
                                    }}">
                                        {{ $payment->payment_method_name }}
                                    </span>
                                    @if($payment->transaction_id)
                                        <br><small><i class="fas fa-hashtag mr-1"></i>{{ $payment->transaction_id }}</small>
                                    @endif
                                    @if($payment->serial_number)
                                        <br><small><i class="fas fa-receipt mr-1"></i>{{ $payment->serial_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold text-success">
                                        ৳{{ number_format($payment->deposit_amount, 2) }}
                                    </div>
                                    @if($payment->due_amount > 0)
                                        <small class="text-warning">
                                            <i class="fas fa-clock mr-1"></i>Due: ৳{{ number_format($payment->due_amount, 2) }}
                                        </small>
                                    @else
                                        <small class="text-success">
                                            <i class="fas fa-check-circle mr-1"></i>Paid
                                        </small>
                                    @endif
                                    @if($payment->discount_amount > 0)
                                        <br><small class="text-danger">
                                            <i class="fas fa-tag mr-1"></i>Discount: ৳{{ number_format($payment->discount_amount, 2) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $payment->payment_received_by ?? 'N/A' }}<br>
                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    {{ $payment->created_at->format('d-m-Y') }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('student-admissions.payment-invoice-receipt', $payment->id) }}" 
                                           class="btn btn-outline-info" 
                                           title="View Receipt"
                                           data-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('student-admissions.download-payment-invoice-pdf', $payment->id) }}" 
                                           class="btn btn-outline-success" 
                                           title="Download PDF"
                                           data-toggle="tooltip"
                                           target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @if($payment->studentAdmission)
                                            <!-- <a href="{{ route('student-admissions.show', $payment->studentAdmission->id) }}" 
                                               class="btn btn-outline-primary" 
                                               title="View Student"
                                               data-toggle="tooltip">
                                                <i class="fas fa-user-graduate"></i>
                                            </a> -->
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                    <h5>No payment invoices found</h5>
                                    <p>Try adjusting your filters or create a new invoice</p>
                                    <a href="{{ route('student-admissions.payment-invoice-form') }}" 
                                       class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-plus mr-2"></i>Create Your First Invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination with filter preservation -->
            @if (count($payments) && $payments->hasPages())
                <div class="row mt-3">
                    <div class="col-md-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                {{ $payments->appends(request()->except('page'))->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Select...'
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Clear search button
        $('#clearSearch').click(function() {
            $('#search').val('');
            $('#filterForm').submit();
        });

        // Clear date range button
        $('#clearDates').click(function() {
            $('#date_from').val('');
            $('#date_to').val('');
            $('#filterForm').submit();
        });

        // Date validation
        $('#date_from, #date_to').on('change', function() {
            const dateFrom = $('#date_from').val();
            const dateTo = $('#date_to').val();
            
            if (dateFrom && dateTo && dateFrom > dateTo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Date Range Error',
                    text: 'From Date cannot be greater than To Date',
                    confirmButtonText: 'OK'
                });
                $(this).val('');
            }
        });

        // Set max date for date inputs
        $('#date_from, #date_to').attr('max', new Date().toISOString().split('T')[0]);

        // Auto-submit on filter change (optional)
        $('#payment_category, #payment_method, #received_by').change(function() {
            $('#filterForm').submit();
        });

        // Debounce search input
        let searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $('#filterForm').submit();
            }, 500);
        });

        // Initialize DataTable (optional - can be removed if using server-side filtering)
        const table = $('#payment-invoices-table').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "pageLength": 25,
            "order": [[6, "desc"]], // Sort by date column
            "responsive": true,
            "autoWidth": false,
            "searching": false, // Disable DataTable's internal search (use server-side)
            "paging": false, // Disable DataTable's pagination (use Laravel pagination)
            "info": false, // Disable DataTable's info display
            "language": {
                "emptyTable": "No payment invoices found"
            }
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
/* Date range styles */
#date_from, #date_to {
    background-color: #fff;
}
/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-group-sm {
        flex-wrap: wrap;
    }
    .btn-group-sm .btn {
        margin-bottom: 2px;
    }
    .table td, .table th {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    .card-header .btn-link {
        padding: 0;
    }
}
/* Action buttons styling */
.btn-group-sm .btn {
    margin: 0 2px;
}
.btn-outline-info, .btn-outline-success, .btn-outline-primary {
    border-width: 1px;
}
.btn-outline-info:hover, 
.btn-outline-success:hover,
.btn-outline-primary:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}
</style>
@endsection