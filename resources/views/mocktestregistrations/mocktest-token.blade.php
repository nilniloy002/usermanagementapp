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
        .highlight {
            font-weight: bold;
            color: #000;
            background-color: #ffff99;
            padding: 2px 4px;
            border-radius: 3px;
        }

    </style>
</head>
<body>
    <!-- <div class="header">
        <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="@lang('Logo')">
        <h2>@lang('IELTS Mock Test Booking Token | STS')</h2>
        @if(isset($details['lrwTime']) && $details['lrwTime'] == "10:30AM-02:30PM")
            <h3>@lang('Reporting Date & Time'): {{ $details['examDate'] }} | <span class="highlight">@lang('09:45 AM')</h3>
        @else
            <h3>@lang('Reporting Date & Time'): {{ $details['examDate'] }} | <span class="highlight">@lang('02:45 PM')</h3>
        @endif
    </div> -->

    <div class="header">
    <img src="https://sts.institute/wp-content/uploads/2024/08/Logo-v2-01.png" alt="@lang('Logo')">
    <h2>@lang('IELTS Mock Test Booking Token | STS')</h2>

        @php
            $specialExamDates = ['04-03-2025', '11-03-2025', '18-03-2025', '25-03-2025'];
            $reportingTime = '';

            if (isset($details['lrwTime']) && isset($details['examDate'])) {
                if ($details['lrwTime'] == "10:30AM-02:30PM") {
                    $reportingTime = in_array($details['examDate'], $specialExamDates) ? '09:30 AM' : '09:45 AM';
                } elseif ($details['lrwTime'] == "3:30PM-6:30PM") {
                    $reportingTime = in_array($details['examDate'], $specialExamDates) ? '01:30 PM' : '02:30 PM';
                }
            }
        @endphp

        <h3>@lang('Reporting Date & Time'): {{ $details['examDate'] }} | 
            <span class="highlight">@lang($reportingTime)</span>
        </h3>
    </div>

    <div class="content">
        <p>@lang('Dear') {{ $details['name'] }},</p>
        <p>@lang('Here are your mock test details:')</p>
        <ul>
            <li><strong>@lang('Exam Date'):</strong> {{ $details['examDate'] }}</li>
            <!-- <li><strong>@lang('LRW Time'):</strong> {{ $details['lrwTime'] }}</li> -->
            <li><strong>@lang('Reporting Time'):</strong> 
                @if(isset($details['lrwTime']) && $details['lrwTime'] == "10:30AM-02:30PM")
                10:30 AM
                @else 02:30 PM
                @endif
            </li>

            <!-- <li><strong>@lang('Reporting Time'):</strong> 
                @php
                    $specialExamDates = ['04-03-2025', '11-03-2025', '18-03-2025', '25-03-2025'];
                    $reportingTime = '';

                    if (isset($details['lrwTime']) && isset($details['examDate'])) {
                        if ($details['lrwTime'] == "10:30AM-02:30PM") {
                            $reportingTime = in_array($details['examDate'], $specialExamDates) ? '09:30 AM' : '09:45 AM';
                        } elseif ($details['lrwTime'] == "3:30PM-6:30PM") {
                            $reportingTime = in_array($details['examDate'], $specialExamDates) ? '01:30 PM' : '02:45 PM';
                        }
                    }
                @endphp

                {{ $reportingTime }}
            </li> -->
            
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

        <p>@lang('Check your mock test results here:')
        <strong><a href="https://mocktest.sts.institute">https://mocktest.sts.institute</a></strong></p>
        
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
