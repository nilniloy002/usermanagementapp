@extends('layouts.app')

@section('page-title', __('Student Invoice'))
@section('page-heading', __('Student Invoice'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('student-admissions.index') }}">@lang('Student Admissions')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Student Invoice')
    </li>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice mr-2"></i>Student Invoice
                </h5>
                <div>
                    <a href="{{ route('student-admissions.download-invoice', $student->id) }}" 
                       class="btn btn-sm btn-light" target="_blank">
                        <i class="fas fa-download mr-1"></i> Download PDF
                    </a>
                    <!-- <a href="{{ route('student-admissions.show', $student->id) }}" 
                       class="btn btn-sm btn-light ml-2">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Details
                    </a> -->
                </div>
            </div>
            <div class="card-body">
                <!-- Company Header -->
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <!-- <h5 class="text-primary">STS INSTITUTE</h5> -->
                         <img src="https://sourceforces-img.sgp1.cdn.digitaloceanspaces.com/sts-logo.png" width="100" alt="STS Institute" class="logo">
                        <div class="text-muted">
                            134/1 Hauque Mansion (2nd floor), C&B Road<br>
                            Narsingdi, Narsingdi Sadar 1600<br>
                            Phone: 01901402202 | 01901402203<br>
                            Email: info@sts.institute<br>
                            Website: www.sts.institute
                        </div>
                    </div>
                    <div class="col-sm-6 text-right">
                        <h3 class="text-success">INVOICE</h3>
                        <div class="mt-3">
                            <strong>Date:</strong> {{ now()->format('F d, Y') }}<br>
                            <strong>Time:</strong> {{ now()->format('h:i A') }}
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Student & Invoice Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">BILL TO</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="120"><strong>Student Name:</strong></td>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Student ID:</strong></td>
                                <td>{{ $student->student_id ?? 'N/A' }}</td>
                            </tr>
                           
                            <tr>
                                <td><strong>Mobile:</strong></td>
                                <td>{{ $student->mobile }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $student->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Course:</strong></td>
                                <td>{{ $student->course_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Batch:</strong></td>
                                <td>{{ $student->batch_code ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">INVOICE DETAILS</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                
                                <td><strong>Invoice Date:</strong></td>
                                <td>{{ $student->approved_at ? $student->approved_at->format('d-m-Y') : 'N/A' }}</td>
                            </tr>
                             <tr>
                                <td><strong>Application No:</strong></td>
                                <td>{{ $student->application_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Payment Method:</strong></td>
                                <td>
                                    <span class="badge badge-{{ 
                                        ($student->payment->payment_method ?? '') == 'cash' ? 'success' : 
                                        (($student->payment->payment_method ?? '') == 'bkash' ? 'primary' : 'info') 
                                    }}">
                                        {{ ucfirst($student->payment->payment_method ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                            @if($student->payment && $student->payment->transaction_id)
                            <tr>
                                <td><strong>Transaction ID:</strong></td>
                                <td>{{ $student->payment->transaction_id }}</td>
                            </tr>
                            @endif
                            @if($student->payment && $student->payment->serial_number)
                            <tr>
                                <td><strong>Serial No:</strong></td>
                                <td>{{ $student->payment->serial_number }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Payment Status:</strong></td>
                                <td>
                                    @if(($student->payment->due_amount ?? 0) > 0)
                                        <span class="badge badge-warning">PARTIALLY PAID</span>
                                    @else
                                        <span class="badge badge-success">FULLY PAID</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Received By:</strong></td>
                                <td>{{ $student->payment->payment_received_by ?? 'System' }}</td>
                            </tr>
                            
                        </table>
                    </div>
                </div>

                <!-- Payment Details Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="70%">Description</th>
                                        <th width="30%" class="text-right">Amount (BDT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $courseFee = $student->course_fee ?? 0;
                                        $depositAmount = $student->payment->deposit_amount ?? 0;
                                        $discountAmount = $student->payment->discount_amount ?? 0;
                                        $dueAmount = $student->payment->due_amount ?? 0;
                                        $totalPayable = $courseFee - $discountAmount;
                                    @endphp
                                    
                                    <tr>
                                        <td>Course Fee - {{ $student->course_name }}</td>
                                        <td class="text-right">{{ number_format($courseFee, 2) }}</td>
                                    </tr>
                                    
                                    @if($discountAmount > 0)
                                    <tr>
                                        <td>Discount Applied</td>
                                        <td class="text-right text-danger">- {{ number_format($discountAmount, 2) }}</td>
                                    </tr>
                                    @endif
                                    
                                    <tr class="font-weight-bold">
                                        <td>TOTAL PAYABLE</td>
                                        <td class="text-right">{{ number_format($totalPayable, 2) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Amount Paid</td>
                                        <td class="text-right text-success">- {{ number_format($depositAmount, 2) }}</td>
                                    </tr>
                                    
                                    <tr class="font-weight-bold {{ $dueAmount > 0 ? 'text-warning' : 'text-success' }}">
                                        <td>BALANCE DUE</td>
                                        <td class="text-right">{{ number_format($dueAmount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Next Due Date -->
                @if(($student->payment->due_amount ?? 0) > 0 && ($student->payment->next_due_date ?? null))
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <strong>Next Due Date:</strong> {{ \Carbon\Carbon::parse($student->payment->next_due_date)->format('F d, Y') }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Remarks -->
                @if($student->payment && $student->payment->remarks)
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="alert alert-secondary">
                            <strong>Remarks:</strong> {{ $student->payment->remarks }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Signature Section -->
                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="text-center">
                            <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;"></div>
                            <div class="mt-2">Student Signature</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto;"></div>
                            <div class="mt-2">Authorized By</div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-center mb-3">TERMS & CONDITIONS</h6>
                                <div class="small">
                                    <p class="mb-1 text-danger font-weight-bold">1. All payments made for admission are non-refundable under any circumstances.</p>
                                    <p class="mb-1 text-danger font-weight-bold">2. The admitted course fee cannot be merged, transferred, or replaced with another course.</p>
                                    <p class="mb-1">3. Personal communication, relationships, or direct contact with any of our trainers are strictly prohibited.</p>
                                    <p class="mb-1">4. All students must treat fellow students, trainers, and staff respectfully.</p>
                                    <p class="mb-1">5. Discrimination based on race, gender, religion, or other characteristics is against our policy.</p>
                                    <p class="mb-1">6. Cyberbullying, online harassment, or any threatening behavior is strictly prohibited.</p>
                                    <p class="mb-1">7. Students are entitled to a one-year membership to retake the course if needed.</p>
                                    <p class="mb-1">8. Recording any part of a class or session in any format is strictly prohibited.</p>
                                </div>
                                <div class="mt-3 text-center small">
                                    By signing this form, you acknowledge and agree to abide by the above terms. Violation of these terms may result in disciplinary action, including immediate expulsion.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="row mt-4">
                    <div class="col-md-12 text-center text-muted small">
                        <div>This is a computer-generated invoice. No signature required for digital copies.</div>
                        <div class="mt-1">&copy; {{ date('Y') }} STS Institute. All rights reserved.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Print functionality
        $('#printBtn').click(function() {
            window.print();
        });
    });
</script>

<style>
@media print {
    .btn, .breadcrumb, .card-header .btn, .footer {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-header {
        background-color: #fff !important;
        color: #000 !important;
        border-bottom: 2px solid #000 !important;
    }
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background-color: #fff !important;
    }
}
.table-sm td {
    padding: 0.25rem 0;
}
.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endsection