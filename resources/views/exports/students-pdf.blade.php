<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Students Report</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #192335;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #192335;
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 11px;
        }
        .filters {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 10px;
        }
        .filters strong {
            color: #192335;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #192335;
            color: white;
            font-weight: bold;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
        .page-number:before {
            content: "Page " counter(page);
        }
        .summary {
            margin-top: 15px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        .summary table {
            width: auto;
            margin: 0 auto;
        }
        .summary td {
            padding: 3px 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/img/sts-logo.png') }}" width="100" alt="STS Institute" class="logo">

        <h1>STUDENT ADMISSIONS REPORT</h1>
        <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
        <p>Total Students: {{ $students->count() }}</p>
    </div>

    @if(!empty($filters))
    <div class="filters">
        <strong>Applied Filters:</strong>
        @foreach($filters as $key => $value)
            @if($value)
                <span>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                @if(!$loop->last) | @endif
            @endif
        @endforeach
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Inv. No.</th>
                <th>ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Course</th>
                <th class="text-right">Fee</th>
                <th class="text-right">Deposit</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Due</th>
                <th>Payment</th>
                <!-- <th>Status</th> -->
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $student->application_number }}</td>
                <td>{{ $student->student_id ?? 'N/A' }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->mobile }}</td>
                <td>{{ $student->course_name }}
                    <br><small>Batch: {{ $student->batch_code ?? 'N/A' }}</small>
                </td>
                <td class="text-right">{{ number_format($student->course_fee, 2) }}</td>
                <td class="text-right">{{ number_format($student->payment->deposit_amount ?? 0, 2) }}</td>
                <td class="text-right">{{ number_format($student->payment->discount_amount ?? 0, 2) }}</td>
                <td class="text-right">{{ number_format($student->payment->due_amount ?? 0, 2) }}</td>
                <td>{{ $student->payment->payment_method_name ?? 'N/A' }}</td>
                <!-- <td>{{ ucfirst($student->status) }}</td> -->
                <td>{{ $student->updated_at ? $student->updated_at->format('d-m-Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Students:</strong></td>
                <td>{{ $students->count() }}</td>
                <td style="width: 20px;"></td>
                <td><strong>Total Course Fee:</strong></td>
                <td>BDT {{ number_format($students->sum('course_fee'), 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Deposit:</strong></td>
                <td>BDT {{ number_format($students->sum(function($s) { return $s->payment->deposit_amount ?? 0; }), 2) }}</td>
                <td></td>
                <td><strong>Total Discount:</strong></td>
                <td>BDT {{ number_format($students->sum(function($s) { return $s->payment->discount_amount ?? 0; }), 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Due:</strong></td>
                <td>BDT {{ number_format($students->sum(function($s) { return $s->payment->due_amount ?? 0; }), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <span class="page-number"></span> | Generated by STS Institute
    </div>
</body>
</html>