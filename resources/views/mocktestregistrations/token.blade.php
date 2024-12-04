<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('IELTS Mock Test Booking Token | STS')</title>
    <style>
        body {
            width: 3in;
            height: 4in;
            margin: 0;
            padding: 10px;
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header img {
            max-width: 1.0in;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16px;
            margin: 0;
            color: #333;
        }

        .section {
            margin-bottom: 10px;
        }

        .section h3 {
            font-size: 14px;
            margin: 0 0 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 2px;
            color: #555;
        }

        .details {
            margin-left: 10px;
        }

        .details strong {
            display: inline-block;
            width: 50%;
            color: #333;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            color: #777;
        }
        span.editing-token {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="@lang('Logo')">
        <h2>@lang('IELTS Mock Test Booking Token')</h2>
        <p><strong>@lang('Exam Date'):</strong> {{ $details['examDate'] }}</p>
        
    </div>

    <!-- Candidate Information Section -->
    <div class="section">
        <h3>@lang('Candidate Information')</h3>
        <div class="details">
            <p><strong>@lang('Candidate Number'):</strong> <span class="editing-token"> {{ $details['candidateNumber'] }}</span></p>
            
            <p><strong>@lang('Name'):</strong> {{ $details['name'] }}</p>
            <p><strong>@lang('Mobile'):</strong> {{ $details['mobile'] }}</p>
          
        </div>
    </div>

    <!-- Exam Details Section -->
    <div class="section">
        <h3>@lang('Exam Details')</h3>
        <div class="details">
            <p><strong>@lang('No. of Mock Tests'):</strong> {{ $details['no_of_mock_test'] }}</p>
            <p><strong>@lang('Current Mock Test'):</strong> {{ $details['current_mock_test'] }}</p>
            <p><strong>@lang('LRW Time'):</strong> <span class="editing-token">{{ $details['lrwTime'] }}</span></p>
            <p><strong>@lang('Speaking Time'):</strong> 
                @if ($details['speakingTimeAnotherDay'])
                    <em>@lang('Another Day')</em>
                @else
                <span class="editing-token">{{ $details['speakingTime'] ?? '-' }}</span>
                @endif
            </p>
            <p><strong>@lang('Room'):</strong> <span class="editing-token">{{ $details['room'] ?? '-' }}</span>
                
            </p>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>@lang('Please bring this token on your mock test date.')</p>
    </div>
</body>
</html>
