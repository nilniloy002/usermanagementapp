<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $student->student_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .invoice-details { width: 100%; margin-bottom: 20px; }
        .student-info, .payment-info { width: 48%; float: left; }
        .payment-info { float: right; }
        .clear { clear: both; }
        .amount-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .amount-table th, .amount-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .amount-table th { background-color: #f2f2f2; }
        .total-row { background-color: #e8f4fd; font-weight: bold; }
        .footer { margin-top: 30px; border-top: 1px solid #333; padding-top: 10px; text-align: center; font-size: 10px; }
        .watermark { opacity: 0.1; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 72px; color: #000; }
    </style>
</head>
<body>
    <div class="watermark">STS INSTITUTE</div>
    
    <div class="header">
        <h1>STS INSTITUTE</h1>
        <h2>INVOICE</h2>
        <p>Invoice No: INV-{{ $student->student_id }}-{{ date('Ymd') }}</p>
    </div>

    <div class="invoice-details">
        <div class="student-info">
            <h3>Student Details:</h3>
            <p><strong>Name:</strong> {{ $student->name }}</p>
            <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
            <p><strong>Course:</strong> {{ $student->course->course_name ?? 'N/A' }}</p>
            <p><strong>Batch:</strong> {{ $student->batch->batch_code ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $student->email }}</p>
            <p><strong>Mobile:</strong> {{ $student->mobile }}</p>
        </div>

        <div class="payment-info">
            <h3>Invoice Details:</h3>
            <p><strong>Invoice Date:</strong> {{ date('F d, Y') }}</p>
            <p><strong>Application No:</strong> {{ $student->application_number }}</p>
            <p><strong>Approval Date:</strong> {{ $student->approved_at->format('F d, Y') }}</p>
            <p><strong>Payment Method:</strong> {{ $student->payment_method_name }}</p>
            @if($student->transaction_id)
            <p><strong>Transaction ID:</strong> {{ $student->transaction_id }}</p>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <table class="amount-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (BDT)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Course Fee - {{ $student->course->course_name ?? 'N/A' }}</td>
                <td>BDT {{ number_format($student->course->course_fee ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td>- BDT {{ number_format($student->discount_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Total Payable</strong></td>
                <td><strong>BDT {{ number_format($student->total_payable, 2) }}</strong></td>
            </tr>
            <tr>
                <td>Deposit Paid</td>
                <td>- BDT {{ number_format($student->deposit_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Due Amount</strong></td>
                <td><strong>BDT {{ number_format($student->due_amount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($student->due_amount > 0 && $student->next_due_date)
    <div style="background-color: #fff3cd; padding: 10px; border: 1px solid #ffeaa7; margin: 10px 0;">
        <strong>Next Due Date:</strong> {{ $student->next_due_date->format('F d, Y') }}
    </div>
    @endif

    @if($student->remarks)
    <div style="margin: 10px 0;">
        <strong>Remarks:</strong> {{ $student->remarks }}
    </div>
    @endif

    <div class="footer">
        <p><strong>STS Institute</strong></p>
        <p>Email: regmockteststs@sts.institute | Website: www.stsit.institute</p>
        <p>This is a computer-generated invoice. No signature required.</p>
    </div>
</body>
</html>