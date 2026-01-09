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

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>Payment Invoices
                    </h5>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('student-admissions.payment-invoice-form') }}" 
                       class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-2"></i>Create New Invoice
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-borderless" id="payment-invoices-table">
                    <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Student</th>
                        <th>Category</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Received By</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($payments))
                        @foreach ($payments as $payment)
                            <tr>
                                <td>
                                    <strong>#{{ $payment->id }}</strong>
                                    @if($payment->studentAdmission)
                                        <br><small>Student ID: {{ $payment->studentAdmission->student_id ?? 'N/A' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->studentAdmission)
                                        <strong>{{ $payment->studentAdmission->name }}</strong><br>
                                        <small>{{ $payment->studentAdmission->mobile }}</small>
                                    @else
                                        <em>Student not found</em>
                                    @endif
                                </td>
                                <td>
                                    {{ $payment->payment_category }}<br>
                                    <small class="text-muted">{{ $payment->purpose }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $payment->payment_method == 'cash' ? 'success' : 
                                        ($payment->payment_method == 'bkash' ? 'primary' : 'info') 
                                    }}">
                                        {{ $payment->payment_method_name }}
                                    </span>
                                    @if($payment->transaction_id)
                                        <br><small>Txn: {{ $payment->transaction_id }}</small>
                                    @endif
                                    @if($payment->serial_number)
                                        <br><small>Ref: {{ $payment->serial_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-success font-weight-bold">
                                        ৳{{ number_format($payment->deposit_amount, 2) }}
                                    </span><br>
                                    <small class="text-muted">
                                        Due: ৳{{ number_format($payment->due_amount, 2) }}
                                    </small>
                                </td>
                                <td>
                                    {{ $payment->payment_received_by }}<br>
                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                </td>
                                <td>{{ $payment->created_at->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('student-admissions.payment-invoice-receipt', $payment->id) }}" 
                                       class="btn btn-icon btn-sm btn-info" 
                                       title="View Receipt">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('student-admissions.download-payment-invoice-pdf', $payment->id) }}" 
                                       class="btn btn-icon btn-sm btn-success" 
                                       title="Download PDF">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                                <em>No payment invoices found.</em>
                                <br>
                                <a href="{{ route('student-admissions.payment-invoice-form') }}" class="btn btn-success btn-sm mt-2">
                                    <i class="fas fa-plus mr-2"></i>Create Your First Invoice
                                </a>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (count($payments))
                <div class="row">
                    <div class="col-md-12">
                        {{ $payments->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
<script>
$(document).ready(function() {
    $('#payment-invoices-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],
        "pageLength": 25,
        "order": [[0, "desc"]]
    });
});
</script>
@endsection