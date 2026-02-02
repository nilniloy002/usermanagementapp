@extends('layouts.app')

@section('page-title', __('Daily Revenue Report'))
@section('page-heading', __('Daily Revenue Report'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('student-admissions.index') }}">@lang('Student Admissions')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Daily Revenue Report')
    </li>
@stop

@section('content')
    @include('partials.messages')

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('student-admissions.daily-revenue') }}" method="GET" id="filterForm">
                <div class="row align-items-end">
                    <!-- Date Range -->
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="{{ request('start_date') ?? date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="{{ request('end_date') ?? date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                    
                    <!-- Reset Button -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <a href="{{ route('student-admissions.daily-revenue') }}" 
                               class="btn btn-secondary btn-block">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                    
                    <!-- Export Buttons -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="btn-group btn-block">
                                <a href="{{ route('student-admissions.daily-revenue.pdf', request()->all()) }}" 
                                   class="btn btn-outline-danger" target="_blank" title="Export PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <!-- <a href="{{ route('student-admissions.daily-revenue.excel', request()->all()) }}" 
                                   class="btn btn-outline-success" target="_blank" title="Export Excel">
                                    <i class="fas fa-file-excel"></i>
                                </a> -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters -->
                @if(request()->anyFilled(['start_date', 'end_date', 'payment_category']))
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap align-items-center">
                            <small class="text-muted mr-2">Active Filters:</small>
                            
                            @if(request('start_date') && request('end_date'))
                                <span class="badge badge-info mr-2 mb-1">
                                    {{ request('start_date') }} to {{ request('end_date') }}
                                    <a href="{{ route('student-admissions.daily-revenue', array_merge(request()->except(['start_date', 'end_date']), ['page' => 1])) }}" 
                                       class="text-white ml-1">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            
                            @if(request('payment_category'))
                                <span class="badge badge-info mr-2 mb-1">
                                    Category: {{ request('payment_category') }}
                                    <a href="{{ route('student-admissions.daily-revenue', array_merge(request()->except('payment_category'), ['page' => 1])) }}" 
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

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">
                        <i class="fas fa-money-bill-wave mr-2"></i>Total Paid Amount
                    </h5>
                    <h3 class="text-success">৳{{ number_format($totalDeposit, 2) }}</h3>
                    <p class="text-muted mb-0">
                        @if(request('start_date') && request('end_date'))
                            {{ request('start_date') }} - {{ request('end_date') }}
                        @else
                            Today's Revenue
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">
                        <i class="fas fa-tag mr-2"></i>Total Discount
                    </h5>
                    <h3 class="text-warning">৳{{ number_format($totalDiscount, 2) }}</h3>
                    <p class="text-muted mb-0">Discount Given</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h5 class="card-title text-danger">
                        <i class="fas fa-clock mr-2"></i>Total Due
                    </h5>
                    <h3 class="text-danger">৳{{ number_format($totalDue, 2) }}</h3>
                    <p class="text-muted mb-0">Pending Amount</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Report Card -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">
                    Revenue Details 
                    <small class="text-muted">
                        ({{ $students->total() }} records)
                        @if(request('start_date') && request('end_date'))
                            from {{ request('start_date') }} to {{ request('end_date') }}
                        @endif
                    </small>
                </h5>
                
                <!-- Additional Export Buttons -->
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Print
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-borderless" id="daily-revenue-table">
                    <thead>
                    <tr>
                        <th class="min-width-80">@lang('Date')</th>
                        <th class="min-width-100">@lang('Invoice No.')</th>
                        <th class="min-width-150">@lang('Name')</th>
                        <th class="min-width-100 text-right">@lang('Paid')</th>
                        <th class="min-width-100 text-right">@lang('Discount')</th>
                        <th class="min-width-100 text-right">@lang('Due')</th>
                        <th class="min-width-120">@lang('P.M')</th>
                        <th class="min-width-120">@lang('R.B')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($students))
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    {{ $student->created_at->format('d-m-Y') }}<br>
                                    <small class="text-muted">{{ $student->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $student->application_number }}</strong>
                                </td>
                            
                                <td>
                                    <strong>{{ $student->name }}</strong><br>
                                    <small class="text-muted">
                                          <span class="badge badge-success">{{ $student->student_id }}</span>
                                    </small>
                                </td>
                                <td class="text-right">
                                    <span class="text-success font-weight-bold">
                                        ৳{{ number_format($student->payment->deposit_amount ?? 0, 2) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="text-danger font-weight-bold">
                                        ৳{{ number_format($student->payment->discount_amount ?? 0, 2) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    @if(($student->payment->due_amount ?? 0) > 0)
                                        <span class="text-warning font-weight-bold">
                                            ৳{{ number_format($student->payment->due_amount ?? 0, 2) }}
                                        </span>
                                    @else
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Paid
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->payment->payment_method)
                                        <span class="badge badge-info">
                                            {{ $student->payment->payment_method }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Admission</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->payment->payment_received_by)
                                        <span class="badge badge-primary">
                                            {{ $student->payment->payment_received_by }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-center">
                                <em>@lang('No revenue records found for the selected date range.')</em>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    
                    <!-- Totals Row -->
                    @if (count($students))
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="3" class="text-right font-weight-bold">
                                <strong>GRAND TOTAL:</strong>
                            </td>
                            <td class="text-right font-weight-bold text-success">
                                <strong>৳{{ number_format($totalDeposit, 2) }}</strong>
                            </td>
                            <td class="text-right font-weight-bold text-danger">
                                <strong>৳{{ number_format($totalDiscount, 2) }}</strong>
                            </td>
                            <td class="text-right font-weight-bold text-warning">
                                <strong>৳{{ number_format($totalDue, 2) }}</strong>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <!-- Pagination with filter preservation -->
            @if (count($students))
                <div class="row">
                    <div class="col-md-12">
                        {{ $students->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#daily-revenue-table').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "pageLength": 50,
            "order": [[0, "desc"]],
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Search within results:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries available",
                "infoFiltered": "(filtered from _MAX_ total entries)"
            }
        });

        // Date range validation
        $('#start_date, #end_date').change(function() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            
            if (startDate && endDate) {
                if (startDate > endDate) {
                    alert('Start date cannot be greater than end date!');
                    $(this).val('');
                }
            }
        });

        // Auto-submit form on category change
        $('#payment_category').change(function() {
            $('#filterForm').submit();
        });

        // Tooltip initialization
        $('[data-toggle="tooltip"]').tooltip();
        
        // Set default dates to today if empty
        if (!$('#start_date').val()) {
            $('#start_date').val('{{ date("Y-m-d") }}');
        }
        if (!$('#end_date').val()) {
            $('#end_date').val('{{ date("Y-m-d") }}');
        }
    });
</script>

<style>
.table th {
    font-weight: 600;
}
.badge {
    font-size: 0.75em;
}
.card-title {
    font-size: 1rem;
}
.table tfoot td {
    font-size: 1.1rem;
    border-top: 2px solid #dee2e6;
}
</style>
@endsection