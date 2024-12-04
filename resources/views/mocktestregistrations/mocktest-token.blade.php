<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('IELTS Mock Test Booking Token | STS')</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        .content { padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="@lang('Logo')">
        <h2>@lang('IELTS Mock Test Booking Token | STS')</h2>
    </div>
    <div class="content">
        <p>@lang('Dear') {{ $details['name'] }},</p>
        <p>@lang('Here are your mock test details:')</p>
        <ul>
            <li><strong>@lang('Exam Date'):</strong> {{ $details['examDate'] }}</li>
            <li><strong>@lang('LRW Time'):</strong> {{ $details['lrwTime'] }}</li>
            
            <li><strong>@lang('Speaking Time'):</strong> 
                @if ($details['speaking_time_slot_id_another_day'])
                    @lang('Another Day')
                @else
                    {{ $details['speakingTime'] ?? '-' }}
                @endif
            </li>

            <li><strong>@lang('Room'):</strong> 
                @if ($details['speaking_time_slot_id_another_day'])
                    -
                @else
                    {{ $details['room'] ?? '-' }}
                @endif
            </li>
            
            <li><strong>@lang('Candidate Number'):</strong> {{ $details['candidateNumber'] }}</li>
        </ul>
        
    </div>
    <!-- Footer Section -->
    <div class="footer">
        @lang('For any queries, please contact us at') 
        <a href="mailto:mocktest@sts.institute">mocktest@sts.institute</a>.
        <br>
        @lang('Thank you for choosing STS Institute. Best wishes for your test!')
    </div>
</body>
</html>
