<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speaking Test Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .content {
            font-size: 16px;
            color: #333;
        }
        .content ul {
            list-style: none;
            padding: 0;
        }
        .content ul li {
            margin-bottom: 10px;
        }
        .content ul li strong {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
        <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="@lang('Logo')">
            <h2>Speaking Test Schedule</h2>
        </div>
        <div class="content">
            <p>Your speaking test is scheduled as follows:</p>
            <ul>
                <li><strong>Date:</strong> {{ $speaking_date }}</li>
                <li><strong>Time:</strong> {{ $speaking_time }}</li>
                <li><strong>Zoom Link:</strong> <a href="{{ $zoom_link }}">{{ $zoom_link }}</a></li>
            </ul>
        </div>
        <div class="footer">
            <p>Thank you for choosing STS Institute. Best wishes for your test!</p>
            <img src="{{ $tracking_url }}" style="display:none;" />
        </div>
    </div>
</body>
</html>
