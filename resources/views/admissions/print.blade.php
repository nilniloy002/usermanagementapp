<!-- print.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ url('assets/img/icons/stsicon.png') }}"/>
    <title>Money Receipt - {{ $admission->bill_id }}</title>

    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0.5cm;
            line-height: 1.6;
            color: #000000;
        }
        

        header {
            text-align: left;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 150px; /* Adjust the width as needed */
            height: auto;
        }

        h1 {
            margin-bottom: 20px;
            color: #000000;
        }

        .receipt-details {
            margin-bottom: 20px;
            font-size: 16px;
            color: #555;
        }

        .receipt-details p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #000000;
        }

        .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        padding: 10px 0;
    }

    .signature-section-left {
        display: inline-block;
        width: 45%;
        text-align: left;
        margin-top: 40px;
    }
    .signature-section-right {
        display: inline-block;
        width: 45%;
        text-align: right;
        margin-top: 40px;
    }

    .signature-section-left p {
        margin-top: 20px;
        font-weight: bold;
        color: #000000;
        text-decoration: overline; 
    }
    .signature-section-right p {
        margin-top: 20px;
        font-weight: bold;
        color: #000000;
        text-decoration: overline; 
    }
    </style>
</head>
<body onload="window.print();">
    @php
        $totalFee = $admission->course->course_fee + $admission->course->admission_fee;
        $totalPaid = $admission->payments->sum('paid_amount');
        $totalDiscount = $admission->payments->sum('discount_amount');
        $totalDue = $totalFee - ($totalPaid + $totalDiscount);
    @endphp

    <header>
        <img src="{{ url('assets/img/stslogo.png') }}" alt="STS IT Logo" class="logo">
        <div>
            <h1>Money Receipt</h1>
        </div>
    </header>

    <div class="receipt-details">
        <p><strong>Receipt Number:</strong> {{ $admission->bill_id }}</p>
        <p><strong>Date:</strong> {{ date("d-m-Y", strtotime($admission->admission_date)) }}</p>
    </div>

    <div>
        <p><strong>Student ID:</strong> {{ $admission->bill_id }}</p>
        <p><strong>Name:</strong> {{ $admission->student_name }}</p>
        <p><strong>Mobile:</strong> {{ $admission->phone_number }}</p>
        <p><strong>Guardian Mobile:</strong> {{ $admission->guardian_phone_number }}</p>
        <p><strong>Admission for:</strong> {{ $admission->course->course_name }} Course | <strong>Batch:</strong> {{ $admission->batch_code }}</p>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th >@lang('Purpose')</th>
                <th >@lang('Amount')</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>@lang('Total Fee')</strong> <br></td>
                <td>{{ $admission->course->course_fee + $admission->course->admission_fee }} Tk.</td>
            </tr>

            @foreach($admission->payments as $payment)
                <tr>
                    <td><strong>{{ $payment->payment_process }}</strong><br>
                        <small> @lang('Date'): {{ date("d-m-Y", strtotime($payment->payment_date))}}<br>
                            @lang('Payment Method'): {{  $payment->payment_method }}</small>
                    </td>
                    <td>- {{ $payment->paid_amount }} Tk.</td>
                </tr>
            @endforeach

            <tr>
                <td><strong>@lang('Discount')</strong></td>
                <td>- {{$admission->payments->sum('discount_amount')}} Tk.</td>
            </tr>

            <tr>
                <td><strong>@lang('Total Due')</strong></td>
                <td><strong>{{$totalFee - ($totalPaid + $totalDiscount)}} Tk.</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-section-left">
            <p>Authorized Signature</p>
        </div>
        <div class="signature-section-right">
            <p>Received By</p>
        </div>
    </div>
</body>
</html>
