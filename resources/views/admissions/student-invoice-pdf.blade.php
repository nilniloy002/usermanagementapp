<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $student->student_id ?? 'N/A' }}</title>
    <style>
        /* Basic CSS that DomPDF can handle */
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.1;
            color: #000;
            margin: 0;
            padding: 10px;
        }
        
        .invoice-container {
            width: 100%;
            margin: 0 auto;
        }
        
        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #192335;
            padding-bottom: 10px;
        }
        
        .left-header {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .right-header {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }
        
        .logo {
            height: 40px;
            margin-bottom: 5px;
        }
        
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #192335;
            margin-bottom: 3px;
        }
        
        .company-address {
            font-size: 8pt;
            color: #666;
            line-height: 1.3;
        }
        
        .invoice-title {
            font-size: 20pt;
            font-weight: bold;
            color: #192335;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 10pt;
            color: #666;
            margin-bottom: 2px;
        }
        
        /* Details Section */
        .details-section {
            margin-bottom: 15px;
            display: table;
            width: 100%;
        }
        
        .bill-to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .invoice-details {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #192335;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #192335;
        }
        
        .detail-row {
            margin-bottom: 3px;
        }
        
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .items-table th {
            background: #192335;
            color: white;
            font-weight: bold;
            padding: 5px;
            text-align: left;
            border: 1px solid #192335;
        }
        
        .items-table td {
            padding: 5px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .total-row {
            background: #f5f5f5;
            font-weight: bold;
        }
        
        .due-row {
            background: #fff3cd;
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .amount {
            font-family: DejaVu Sans Mono, monospace;
        }
        
        /* Payment Info */
        .payment-info-grid {
            display: table;
            width: 100%;
        }
        
        .payment-info-cell {
            display: table-cell;
            width: 33.33%;
        }
        
        /* Payment Received */
        .payment-received {
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ddd;
            background: #e8f4fd;
        }
        
        /* Terms Section */
        .terms-section {
            margin: 10px 0;
            padding: 5px;
        }
        
        .terms-title {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            color: #192335;
        }
        
        .terms-content {
            font-size: 8pt;
            line-height: 1.3;
            margin-bottom: 8px;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        
        .signature-line {
            width: 80%;
            height: 1px;
            background: #000;
            margin: 20px auto 5px;
        }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            background: #28a745;
            color: white;
            font-weight: bold;
            font-size: 8pt;
            border-radius: 3px;
        }
        
        .status-partial {
            background: #ffc107;
            color: #000;
        }
        
        /* Print-specific */
        @media print {
            body {
                padding: 0;
            }
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-muted {
            color: #6c757d;
        }

        .small {
            font-size: 9px;
        }

        .mt-2 { margin-top: 5px; }
        .mt-3 { margin-top: 10px; }
        .mt-4 { margin-top: 15px; }
        .mt-5 { margin-top: 20px; }
        .mb-2 { margin-bottom: 5px; }
        .mb-3 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 15px; }
        .text-center { text-align: center; }
        .font-weight-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="left-header">
                <img src="{{ public_path('assets/img/sts-logo.png') }}" alt="STS Institute" class="logo">
                <div class="company-address">
                    134/1 Hauque Mansion (2nd floor), C&B Road<br>
                    Narsingdi, Narsingdi Sadar 1600<br>
                    Phone: 01901402202 | 01901402203<br>
                    Email: info@sts.institute<br>
                    Website: www.sts.institute
                </div>
            </div>
            
            <div class="right-header">
                <div class="invoice-title">INVOICE</div>
                <!-- <div class="invoice-number">INV-{{ $student->student_id ?? $student->id }}-{{ date('Ymd') }}</div>
                <div>Date: {{ now()->format('F d, Y') }}</div>
                <div>Time: {{ now()->format('h:i A') }}</div> -->
            </div>
        </div>
        
        <!-- Bill To & Invoice Details -->
        <div class="details-section">
             <div class="bill-to">
                <div class="section-title">BILL TO</div>
                <div class="detail-row">
                    <span class="detail-label">Student ID:</span> {{ $student->student_id }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Name:</span> {{ $student->name }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Course:</span> {{ $student->course->course_name ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Batch:</span> {{ $student->batch->batch_code ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mobile:</span> {{ $student->mobile }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span> {{ $student->email }}
                </div>
            </div>
            
            <div class="invoice-details">
                <div class="section-title">INVOICE DETAILS</div>
                <div class="detail-row">
                    <span class="detail-label">Invoice Date:</span> {{ date('F d, Y') }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Application No:</span> {{ $student->application_number }}
                </div>
                <!-- <div class="detail-row">
                    <span class="detail-label">Approval Date:</span> {{ $student->approved_at->format('F d, Y') }}
                </div> -->
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span> {{ $student->payment_method_name }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span>
                     @if($student->due_amount > 0)
                        <span class="status-badge status-partial">PARTIALLY PAID</span>
                    @else
                        <span class="status-badge">FULLY PAID</span>
                    @endif
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Received By:</span> {{ Auth::user()->first_name ?? 'System Administrator' }}
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 70%">Description</th>
                    <th style="width: 30%" class="text-right">Amount (BDT)</th>
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
                    <td class="text-right amount">{{ number_format($courseFee, 2) }}</td>
                </tr>
                
                @if($discountAmount > 0)
                <tr>
                    <td>Discount Applied</td>
                    <td class="text-right amount text-danger">- {{ number_format($discountAmount, 2) }}</td>
                </tr>
                @endif
                
                <tr class="total-row">
                    <td>TOTAL PAYABLE</td>
                    <td class="text-right amount">{{ number_format($totalPayable, 2) }}</td>
                </tr>
                
                <tr>
                    <td>Amount Paid</td>
                    <td class="text-right amount text-success">- {{ number_format($depositAmount, 2) }}</td>
                </tr>
                
                <tr class="due-row">
                    <td>BALANCE DUE</td>
                    <td class="text-right amount">{{ number_format($dueAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Next Due Date -->
        @if(($student->payment->due_amount ?? 0) > 0 && ($student->payment->next_due_date ?? null))
        <div style="margin: 10px 0; padding: 8px; border: 1px solid #ffd54f; background: #fff3cd;">
            <strong>Next Due Date:</strong> {{ \Carbon\Carbon::parse($student->payment->next_due_date)->format('F d, Y') }}
        </div>
        @endif
        
        <!-- Remarks -->
        @if($student->payment && $student->payment->remarks)
        <div style="margin: 10px 0; padding: 8px; border: 1px solid #b8daff; background: #e8f4fd;">
            <strong>Remarks:</strong> {{ $student->payment->remarks }}
        </div>
        @endif
        
        <!-- Terms & Conditions -->
        <div class="terms-section">
            <div class="terms-title">TERMS & CONDITIONS</div>
            <div class="terms-content">
                <p style="color:red; font-weight:bold; margin:0;">1. All payments made for admission are non-refundable under any circumstances.</p>
                <p style="color:red; font-weight:bold; margin:3px 0;">2. The admitted course fee cannot be merged, transferred, or replaced with another course.</p>
                <p style="margin:3px 0;">3. Personal communication, relationships, or direct contact with any of our trainers are strictly prohibited.</p>
                <p style="margin:3px 0;">4. All students must treat fellow students, trainers, and staff respectfully.</p>
                <p style="margin:3px 0;">5. Discrimination based on race, gender, religion, or other characteristics is against our policy.</p>
                <p style="margin:3px 0;">6. Cyberbullying, online harassment, or any threatening behavior is strictly prohibited.</p>
                <p style="margin:3px 0;">7. Students are entitled to a one-year membership to retake the course if needed.</p>
                <p style="margin:3px 0;">8. Recording any part of a class or session in any format is strictly prohibited.</p>
            </div>
            
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Student Signature</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Authorized By</div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="small text-muted">
                This is a computer-generated invoice. No signature required for digital copies.
            </div>
            <div style="margin-top: 5px; font-size: 8pt;">
                &copy; {{ date('Y') }} STS Institute. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>