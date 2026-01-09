<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Invoice - {{ $payment->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.1;
            color: #000;
            margin: 0;
        }
        
        .invoice-container {
            margin: 0 auto;
        }
        
        .header {
            display: table;
            width: 100%;
            margin-bottom: 5mm;
            border-bottom: 1mm solid #192335;
            padding-bottom: 5mm;
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
            height: 15mm;
            margin-bottom: 2mm;
        }
        
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #192335;
            margin-bottom: 1mm;
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
            margin-bottom: 2mm;
        }
        
        .invoice-number {
            font-size: 10pt;
            color: #666;
            margin-bottom: 1mm;
        }
        
        .details-section {
            display: table;
            width: 100%;
            margin-bottom: 5mm;
        }
        
        .bill-to {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 5mm;
        }
        
        .invoice-details {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #192335;
            margin-bottom: 3mm;
            padding-bottom: 1mm;
            border-bottom: 0.5pt solid #192335;
        }
        
        .detail-row {
            margin-bottom: 2mm;
        }
        
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 40mm;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5mm 0;
        }
        
        .items-table th {
            background: #192335;
            color: white;
            font-weight: bold;
            padding: 1mm;
            text-align: left;
            border: 0.5pt solid #192335;
        }
        
        .items-table td {
            padding: 1mm 2mm;
            border: 0.5pt solid #ddd;
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
        
        .payment-info-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 2mm;
            color: #192335;
        }
        
        .payment-info-grid {
            display: table;
            width: 100%;
        }
        
        .payment-info-cell {
            display: table-cell;
            width: 33.33%;
        }
        
        .terms-section {
            margin: 3mm 0;
            padding: 1mm;
        }
        
        .terms-title {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3mm;
            color: #192335;
        }
        
        .terms-content {
            font-size: 8pt;
            line-height: 1.3;
            margin-bottom: 3mm;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 5mm;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        
        .signature-line {
            width: 80mm;
            height: 0.5pt;
            background: #000;
            margin: 10mm auto 1mm;
        }
        
        .footer {
            margin-top: 10mm;
            padding-top: 3mm;
            border-top: 0.5pt solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        .footer-title {
            font-weight: bold;
            margin-bottom: 1mm;
            color: #192335;
        }
        
        .status-badge {
            display: inline-block;
            padding: 1mm 3mm;
            background: #28a745;
            color: white;
            font-weight: bold;
            font-size: 8pt;
            border-radius: 1mm;
        }
        
        .status-partial {
            background: #ffc107;
            color: #000;
        }
        
        .status-pending {
            background: #dc3545;
            color: white;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                width: 100%;
                height: 100%;
            }
        }
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
                <div class="invoice-title">PAYMENT INVOICE</div>
                <!-- <div class="invoice-number">Invoice #{{ $payment->id }}</div>
                <div>Date: {{ $payment->created_at->format('F d, Y') }}</div>
                <div>Time: {{ $payment->created_at->format('h:i A') }}</div> -->
            </div>
        </div>
        
        <!-- Bill To & Invoice Details -->
        <div class="details-section">
            <div class="bill-to">
                <div class="section-title">BILL TO</div>
                <div class="detail-row">
                    <span class="detail-label">Student Name:</span> {{ $student->name ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Student ID:</span> {{ $student->student_id ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Application No:</span> {{ $student->application_number ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mobile:</span> {{ $student->mobile ?? 'N/A' }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span> {{ $student->email ?? 'N/A' }}
                </div>
            </div>
            
            <div class="invoice-details">
                <div class="section-title">INVOICE DETAILS</div>
                <div class="detail-row">
                    <span class="detail-label">Invoice Date:</span> {{ $payment->created_at->format('F d, Y') }}
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Category:</span> {{ $payment->payment_category }}
                </div>
                @if($payment->purpose)
                <div class="detail-row">
                    <span class="detail-label">Purpose:</span> {{ $payment->purpose }}
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span> {{ ucfirst($payment->payment_method) }}
                </div>
                @if($payment->transaction_id)
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span> {{ $payment->transaction_id }}
                </div>
                @endif
                @if($payment->serial_number)
                <div class="detail-row">
                    <span class="detail-label">Serial No:</span> {{ $payment->serial_number }}
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    @if($payment->due_amount <= 0)
                        <span class="status-badge">FULLY PAID</span>
                    @elseif($payment->deposit_amount > 0)
                        <span class="status-badge status-partial">PARTIALLY PAID</span>
                    @else
                        <span class="status-badge status-pending">PENDING</span>
                    @endif
                </div>
                <div class="detail-row">
                    <span class="detail-label">Received By:</span> {{ $payment->payment_received_by ?? 'System' }}
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
                <!-- <tr>
                    <td>{{ $payment->payment_category }}</td>
                    <td class="text-right amount">{{ number_format($payment->due_amount, 2) }}</td>
                </tr> -->

                 <tr>
                    <td>Deposit Paid- {{ $payment->payment_category }} ({{ $payment->purpose }})</td>
                    <td class="text-right amount"> {{ number_format($payment->deposit_amount, 2) }}</td>
                </tr>

                @if($payment->discount_amount > 0)
                <tr>
                    <td>Discount Applied</td>
                    <td class="text-right amount">- {{ number_format($payment->discount_amount, 2) }}</td>
                </tr>
                @endif
                <!-- <tr class="total-row">
                    <td><strong>TOTAL DUE</strong></td>
                    <td class="text-right amount"><strong>{{ number_format($payment->due_amount, 2) }}</strong></td>
                </tr> -->
                <!-- <tr>
                    <td>Amount Paid</td>
                    <td class="text-right amount">- {{ number_format($payment->deposit_amount, 2) }}</td>
                </tr>
                <tr class="due-row">
                    <td><strong>BALANCE DUE</strong></td>
                    <td class="text-right amount"><strong>{{ number_format(max(0, $payment->due_amount - $payment->deposit_amount), 2) }}</strong></td>
                </tr> -->


               
                <tr class="due-row">
                    <td><strong>DUE AMOUNT</strong></td>
                    <td class="text-right amount"><strong>{{ number_format($payment->due_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Additional Information -->
        @if($payment->remarks)
        <div style="margin: 3mm 0; padding: 3mm; border: 0.5pt solid #b8daff; background: #e8f4fd;">
            <strong>Remarks:</strong> {{ $payment->remarks }}
        </div>
        @endif
        
        <!-- Terms & Conditions -->
        <div class="terms-section">
            <div class="terms-title">TERMS & CONDITIONS</div>
            <div class="terms-content">
                <span style="color:red; font-weight:bold;">
                    1. All payments made for admission are non-refundable under any circumstances.
                </span><br>
                <span style="color:red; font-weight:bold;">
                    2. The admitted course fee cannot be merged, transferred, or replaced with another course.
                </span><br>
                <!-- 1. All payments made for admission are non-refundable under any circumstances.<br> -->
                <!-- 2. The admitted course fee cannot be merged, transferred, or replaced with another course.<br> -->
                3. Personal communication, relationships, or direct contact with any of our trainers are strictly prohibited.<br>
                4. All students must treat fellow students, trainers, and staff respectfully.<br>
                5. Discrimination based on race, gender, religion, or other characteristics is against our policy.<br>
                6. Cyberbullying, online harassment, or any threatening behavior is strictly prohibited.<br>
                7. Students are entitled to a one-year membership to retake the course if needed.<br>
                8. Recording any part of a class or session in any format is strictly prohibited.<br><br>
                
                By signing this form, you acknowledge and agree to abide by the above terms. Violation of these terms may result in disciplinary action, including immediate expulsion.
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
            <!-- <div class="footer-title">For any queries, please contact:</div>
            <div>Customer Service: 01914-442202 | IELTS Department: 01914-442203</div>
            <div>Email: regmockteststs@sts.institute | Website: www.stsit.institute</div> -->
            <div style="margin-top: 2mm; font-style: italic;">
                <!-- This is a computer-generated invoice. No signature required for digital copies. -->
                This is a computer-generated invoice.
            </div>
        </div>
    </div>
</body>
</html>