@extends('layouts.app')

@section('page-title', __('Payment Receipt'))
@section('page-heading', __('Payment Receipt'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('student-admissions.payment-invoices') }}">@lang('Payment Invoices')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Receipt')
    </li>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <!-- Receipt Header -->
                    <div class="text-center mb-4">
                        <h3 class="text-success">
                            <i class="fas fa-file-invoice-dollar"></i> Payment Receipt
                        </h3>
                        <h4 class="text-primary">{{ $payment->payment_category }}</h4>
                    </div>

                    <!-- Receipt Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0">Invoice Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Invoice Reference:</strong> {{ $payment->id }}</p>
                                    <p><strong>Date:</strong> {{ $payment->created_at->format('d-m-Y h:i A') }}</p>
                                    <p><strong>Payment Method:</strong> 
                                        <span class="badge badge-{{ 
                                            $payment->payment_method == 'cash' ? 'success' : 
                                            ($payment->payment_method == 'bkash' ? 'primary' : 'info') 
                                        }}">
                                            {{ $payment->payment_method_name }}
                                        </span>
                                    </p>
                                    @if($payment->transaction_id)
                                        <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                                    @endif
                                    @if($payment->serial_number)
                                        <p><strong>Serial Number:</strong> {{ $payment->serial_number }}</p>
                                    @endif
                                    <p><strong>Received by:</strong> {{ $payment->payment_received_by }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white py-2">
                                    <h6 class="mb-0">Payment Summary</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Due Amount:</td>
                                            <td class="text-right">৳{{ number_format($payment->due_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Deposit Amount:</td>
                                            <td class="text-right text-success">৳{{ number_format($payment->deposit_amount, 2) }}</td>
                                        </tr>
                                        @if($payment->discount_amount > 0)
                                        <tr>
                                            <td>Discount:</td>
                                            <td class="text-right text-danger">-৳{{ number_format($payment->discount_amount, 2) }}</td>
                                        </tr>
                                        @endif
                                        <tr class="table-warning">
                                            <td><strong>Balance Due:</strong></td>
                                            <td class="text-right"><strong>৳{{ number_format(max(0, $payment->due_amount - $payment->deposit_amount), 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Information -->
                    @if($payment->studentAdmission)
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0">Student Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $payment->studentAdmission->name }}</p>
                                    <p><strong>Student ID:</strong> {{ $payment->studentAdmission->student_id ?? 'N/A' }}</p>
                                    <p><strong>Mobile:</strong> {{ $payment->studentAdmission->mobile }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Email:</strong> {{ $payment->studentAdmission->email ?? 'N/A' }}</p>
                                    <p><strong>Application No:</strong> {{ $payment->studentAdmission->application_number }}</p>
                                    <p><strong>Address:</strong> {{$payment->studentAdmission->address}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Purpose and Remarks -->
                    @if($payment->purpose || $payment->remarks)
                    <div class="card border-secondary mb-4">
                        <div class="card-header bg-secondary text-white py-2">
                            <h6 class="mb-0">Additional Information</h6>
                        </div>
                        <div class="card-body">
                            @if($payment->purpose)
                                <p><strong>Purpose:</strong> {{ $payment->purpose }}</p>
                            @endif
                            @if($payment->remarks)
                                <p><strong>Remarks:</strong> {{ $payment->remarks }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        <a href="{{ route('student-admissions.download-payment-invoice-pdf', $payment->id) }}" 
                           class="btn btn-success mr-2">
                            <i class="fas fa-download mr-2"></i>Download PDF
                        </a>
                        <button onclick="window.print()" class="btn btn-primary mr-2">
                            <i class="fas fa-print mr-2"></i>Print Receipt
                        </button>
                        <a href="{{ route('student-admissions.payment-invoice-form') }}" 
                           class="btn btn-info">
                            <i class="fas fa-plus mr-2"></i>Create New Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('styles')
<style>
@media print {
    .breadcrumbs, .card-header, .btn {
        display: none !important;
    }
    .card {
        border: none !important;
    }
}
</style>
@endsection