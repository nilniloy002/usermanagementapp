<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Revenue Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .summary-label {
            font-weight: bold;
            color: #333;
        }
        .summary-value {
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #343a40;
            color: white;
            text-align: left;
            padding: 8px;
            font-size: 11px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-warning {
            color: #ffc107;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .totals {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #333;
        }
        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
        }
        .total-label {
            font-weight: bold;
            min-width: 120px;
            text-align: right;
            margin-right: 10px;
        }
        .total-value {
            min-width: 100px;
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Revenue Report</h1>
        <h2>Period: {{ $startDate }} to {{ $endDate }}</h2>
        <p>Generated on: {{ $today }}</p>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Report Period:</span>
            <span class="summary-value">{{ $startDate }} to {{ $endDate }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Records:</span>
            <span class="summary-value">{{ $totalRecords }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Paid Amount:</span>
            <span class="summary-value text-success">BDT. {{ number_format($totalDeposit, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Discount:</span>
            <span class="summary-value text-danger">BDT. {{ number_format($totalDiscount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Due:</span>
            <span class="summary-value text-warning">BDT. {{ number_format($totalDue, 2) }}</span>
        </div>
    </div>

    <!-- Details Table -->
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice No.</th>
                <th>Student ID</th>
                <th>Name</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Due</th>
                <th>P.M</th>
                <th>R.B</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $student->application_number }}</td>
                <td>
                    @if($student->student_id)
                        <span class="badge badge-success">{{ $student->student_id }}</span>
                    @else
                        <span class="badge badge-secondary">N/A</span>
                    @endif
                </td>
                <td>{{ $student->name }}</td>
                <td class="text-right text-success">BDT. {{ number_format($student->payment->deposit_amount ?? 0, 2) }}</td>
                <td class="text-right text-danger">BDT. {{ number_format($student->payment->discount_amount ?? 0, 2) }}</td>
                <td class="text-right">
                    @if(($student->payment->due_amount ?? 0) > 0)
                        <span class="text-warning">BDT. {{ number_format($student->payment->due_amount ?? 0, 2) }}</span>
                    @else
                        <span class="text-success">Paid</span>
                    @endif
                </td>
                <td>
                    @if($student->payment->payment_method)
                        <span class="badge badge-info">{{ $student->payment->payment_method }}</span>
                    @else
                        <span class="badge badge-secondary">Admission</span>
                    @endif
                </td>
                <td>
                    @if($student->payment->payment_received_by)
                        <span class="badge badge-primary">{{ $student->payment->payment_received_by }}</span>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <div class="total-row">
            <span class="total-label">Total Paid:</span>
            <span class="total-value text-success">BDT. {{ number_format($totalDeposit, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Total Discount:</span>
            <span class="total-value text-danger">BDT. {{ number_format($totalDiscount, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Total Due:</span>
            <span class="total-value text-warning">BDT. {{ number_format($totalDue, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Report generated by Student Admission System</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>